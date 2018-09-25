<?php
namespace Qithub\QiiTime;

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

const IS_MODE_DEV = false; // true = 開発モード, false = 本番モード

require_once('../.lib/php/tootlib.php.inc');
require_once('constants.php.inc');
require_once('functions.php.inc');

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

// ロック解除が終わるまで待機
while (true) {
    if (! isLocked()) {
        break;
    }

    // 待機しすぎたら強制解除＆キャッシュを返して終了
    if (time() > TIME_NOW + TIME_WAIT_TO_UNLOCK) {
        unlockFile();
        echoLatestTootInfo($settings);
        exit(STATUS_OK);
    }
}

lockFile();

/* トゥートの実行とキャッシュ作成 */

$msg = createTootMsg(); // トゥート内容の作成

// トゥートの実行（失敗時は Exit）
// アクセストークン取得許可済み（Issue #141 にて）
$visibility = (IS_MODE_DEV) ? 'unlisted'   : 'public';
$name_token = (IS_MODE_DEV) ? 'qithub-dev' : 'qiitime';
$settings['result'] = toot([
    'schema'       => 'https',
    'host'         => 'qiitadon.com',
    'visibility'   => $visibility,          // タイプ: public, unlisted, private
    'name_service' => 'qiitadon',           // `gettoken` 第１引数
    'name_token'   => $name_token,          // `gettoken` 第２引数
    'spoiler_text' => $msg['spoiler_text'], // CW 警告文
    'status'       => $msg['status'],       // トゥート本体
]);

// トゥート時間の更新
$settings['time']      = getTimeStampHourlyNow();
$settings['threshold'] = getTimeStampHourlyNow();

// トゥート済み情報の保存（キャッシュの更新）

if (saveData($settings) && unlockFile()) {
    // 保存データを再読み込みして表示
    include(getPathFileData($settings));
    echoLatestTootInfo($settings, TOOT_IS_NEW);
    exit(STATUS_OK);
}

dieMsg('Error: Failed saving data or unlocking file.', __LINE__); // End of Main
