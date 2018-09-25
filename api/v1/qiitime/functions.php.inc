<?php
namespace Qithub\QiiTime;

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

function getNameFileLock()
{
    return NAME_FILE_LOCK;
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

function getPathFileLock()
{
    $name_file = getNameFileLock();

    return dirname(__FILE__) . DIR_SEP . $name_file;
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

function isLocked()
{
    return file_exists(getPathFileLock());
}

function isRequestInTime(array $settings)
{
    return (getTimeStampHourlyLast($settings) === getTimeStampHourlyNow());
}

/* ---------------------------------------------------------------------- [L] */

function lockFile()
{
    $path_file = getPathFileLock();

    \putContentsToFile($path_file, '');

    return file_exists($path_file);
}

/* ---------------------------------------------------------------------- [S] */

function saveData(array $settings)
{
    $path_file_data = getPathFileData($settings);
    $data_to_save   = convertArrayIncludable($settings);

    if (\putContentsToFile($path_file_data, $data_to_save)) {
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

/* ---------------------------------------------------------------------- [U] */

function unlockFile()
{
    $path_file = getPathFileLock();

    if (file_exists($path_file)) {
        unlink($path_file);
    }

    return ! file_exists($path_file);
}
