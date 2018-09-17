<?php
/**
 * このスクリプトは時報トゥートの実行と最終時報トゥートの情報を JSON で返します.
 *
 * 基本動作仕様:
 *
 * - トゥート済みの時間と同じ時間内の呼び出しの場合は最終トゥート情報を返します。
 * - 未トゥートの場合は、時報をトゥートし最終時報トゥートの情報を更新します。
 *
 * その他の仕様:
 *
 * - 最終トゥート情報は同階層の `.data/.tooted` に保存されます。
 * - `.tooted` は Include 可能な PHP ファイルです。
 * - `.tooted` には $settings の配列データが記載されています。（自動生成）
 *
 * Notes:
 * - 初期化： `.data/` ディレクトリを削除します。
 */

namespace Qithub\QiiTime;

include_once('../.lib/php/tootlib.php.inc');

/* [Constants] ============================================================== */

// 内部環境定数
const FORMAT_TIMESTAMP   = 'YmdH';  // 2018083020 フォーマット
const JSON_NO_OPTION     = 0;       // json_encode() のオプション初期化用
const KEY_TOKEN          = 'token'; //

// フラグ
const TOOT_IS_NEW       = false;
const TOOT_IS_CACHE     = true;

define('TIME_NOW', time());

/* [Settings] =============================================================== */

// Set time zone
date_default_timezone_set(TIMEZONE);

// トゥート済み情報のデフォルト（初回のみ適用）
$settings = [
    'name_file_data' => '.tooted',
    'name_dir_data'  => '.data',
    'path_dir_curr'  => dirname(__FILE__),
];

/* [Main] =================================================================== */

// トゥート済み情報の保存先を同階層に作成
if (! hasSavedData($settings)) {
    $path_dir_data = getPathDirData($settings);

    // ディレクトリ作成
    if (! is_dir($path_dir_data) && ! mkdir($path_dir_data, 0700)) {
        dieMsg('Error: Can not create data directory.', __LINE__);
    }
    // トゥート済み情報の初回保存（デフォルト値）
    if (! saveData($settings)) {
        dieMsg('Error: Can not save data.', __LINE__);
    }
}

// トゥート済み情報（$settings）の読み込み
include(getPathFileData($settings));

// 同時間内のアクセスの場合は何もせずキャッシュを返して終了
if (isRequestInTime($settings)) {
    echoLatestTootInfo($settings);
    exit(STATUS_OK);
}

/* トゥートの実行とキャッシュ作成 */

$msg = createTootMsg(); // トゥート内容の作成

// トゥートの実行（失敗時は Exit）
// アクセストークン取得許可済み（Issue #141 にて）
$settings['result'] = toot([
    'schema'       => 'https',
    'host'         => 'qiitadon.com',
    'visibility'   => 'unlisted',           // タイプ: public, unlisted, private
    'name_service' => 'qiitadon',           // `gettoken` 第１引数
    'name_token'   => 'qithub-dev',         // `gettoken` 第２引数
    'spoiler_text' => $msg['spoiler_text'], // CW 警告文
    'status'       => $msg['status'],       // トゥート本体
]);

// トゥート時間の更新
$settings['time']      = getTimeStampHourlyNow();
$settings['threshold'] = getTimeStampHourlyNow();

// トゥート済み情報の保存（キャッシュの更新）
if (! saveData($settings)) {
    dieMsg('Error: Failed saving data.', __LINE__);
} else {
    // 保存データを再読み込みして表示
    include(getPathFileData($settings));
    echoLatestTootInfo($settings, TOOT_IS_NEW);
    exit(STATUS_OK);
}

exit(__LINE__);// End of Main

/* [Functions] ============================================================== */

/* ---------------------------------------------------------------------- [C] */

function createTootMsg()
{
    // Set current time
    $date_today   = date('Y/m/d', TIME_NOW);
    $date_hour24  = date('H', TIME_NOW);
    $date_hour12  = (integer) date('h', TIME_NOW);
    // Hashtags
    $date_tag_Ym   = date('Y_m', TIME_NOW);
    $date_tag_Ymd  = date('Y_m_d', TIME_NOW);
    $date_tag_YmdH = date('Y_m_d_H', TIME_NOW);
    // Define hour icon
    $icon_hour = strtr($date_hour12, [
        12 => '🕛', 11 => '🕚', 10 => '🕙', 9 => '🕘',
        8  => '🕗',  7 => '🕖',  6 => '🕕', 5 => '🕔',
        4  => '🕓',  3 => '🕒',  2 => '🕑', 1 => '🕐',
        0  => '🕛',
    ]);

    // CW（Contents Warning, $spoiler_text）
    // -------------------------------------------------------------------------
    $spoiler_text =<<<EOD
${icon_hour} ${date_hour24} 時をお知らせします :qiitan: (${date_today})
EOD;

    // Status message
    // -------------------------------------------------------------------------
    $status =<<<EOD
QiiTime は Qiita/Qiitadon の同人サークル Qithub のコラボ作品です。詳細は https://qiitadon.com/@QiiTime/100691414720855633 へ。コラボ・メンバー募集中！ :qiitan: #${date_tag_Ym} #${date_tag_Ymd} #${date_tag_YmdH}
EOD;

    return ['spoiler_text' => $spoiler_text, 'status' => $status];
}

function convertArrayIncludable(array $array)
{
    $string  = '<?php' . PHP_EOL;
    $string .= '$settings = ' . var_export($array, true) . ';';

    return   $string . PHP_EOL;
}

/* ---------------------------------------------------------------------- [E] */

function echoLatestTootInfo(array $settings, $status_cache = TOOT_IS_CACHE)
{
    if (! isCli()) {
        $etag = getEtag($settings);
        header('content-type: application/json; charset=utf-8');
        header("ETag: \"{$etag}\"");
    }

    echo getJsonToReturn($settings, $status_cache, JSON_PRETTY_PRINT), PHP_EOL;
}

/* ---------------------------------------------------------------------- [G] */

function getEtag(array $settings)
{
    return hash('md5', getThreshold($settings));
}

function getJsonToReturn(array $settings, $status_cache = TOOT_IS_CACHE, $json_option = JSON_NO_OPTION)
{

    if (isCli()) {
        $json_option = $json_option | JSON_PRETTY_PRINT;
    }

    $result['threshold']  = getValue('threshold', $settings, 'unset');
    $result['is_cache']   = $status_cache;

    $result_toot = getValue('result', $settings);

    $result['id']           = getValue('id', $result_toot, 'unset');
    $result['uri']          = getValue('uri', $result_toot, 'unset');
    $result['url']          = getValue('url', $result_toot, 'unset');
    $result['created_at']   = getValue('created_at', $result_toot, 'unset');
    $result['requested_at'] = date('Y-m-d\TH:i:s.Z\Z', TIME_NOW); //Without TimeZone

    return json_encode($result, $json_option);
}

function getPathDirData(array $settings)
{
    $path_dir_curr  = getValue('path_dir_curr', $settings);
    $name_dir_data  = getValue('name_dir_data', $settings);

    return $path_dir_curr . DIR_SEP . $name_dir_data;
}

function getPathFileData(array $settings)
{
    $name_file_data = getValue('name_file_data', $settings);
    $path_dir_data  = getPathDirData($settings);

    return $path_dir_data . DIR_SEP . $name_file_data;
}

function getThreshold(array $settings)
{
    return getValue('threshold', $settings, getTimeStampHourlyNow());
}

function getTimeStampHourlyLast(array $settings)
{
    return getValue('time', $settings);
}

function getTimeStampHourlyNow()
{
    return date(FORMAT_TIMESTAMP, TIME_NOW);
}

/* ---------------------------------------------------------------------- [H] */

function hasSavedData(array $settings)
{
    return file_exists(getPathFileData($settings));
}

/* ---------------------------------------------------------------------- [I] */

function isRequestInTime(array $settings)
{
    return (getTimeStampHourlyLast($settings) === getTimeStampHourlyNow());
}


/* ---------------------------------------------------------------------- [S] */

function saveData(array $settings)
{
    $path_file_data = getPathFileData($settings);
    $data_to_save   = convertArrayIncludable($settings);

    if (file_put_contents($path_file_data, $data_to_save, LOCK_EX)) {
        $result = file_get_contents($path_file_data);

        return ($result === $data_to_save);
    };

    return false;
}

function setAccessTokenFromQuery()
{
    $access_token = getAccessTokenFromQuery();

    if (! $access_token) {
        return false;
    }

    global $settings;

    $settings[KEY_TOKEN] = $access_token;

    return saveData();
}
