<?php
namespace Qithub\QiiTime;

/**
 * This script toots one time signal toot per hour on request.
 * And/or returns the last tooted info. Such as the Toot ID (Status ID) and time.
 *
 * Notes:
 * - You need to set access token before use as below:
 *   https://qithub.tk/api/v1/qiitime/?token=<accesstoken>
 *   Only once required.
 * - This scripts works stand-alone. To use it on your instance, copy this script
 *   and change default settings on "Settings" section.
 */

/* [Constants] ============================================================== */

const FORMAT_TIMESTAMP   = 'YmdH';  // 2018083020 format
const JSON_NO_OPTION     = 0;       // To reset JSON_ENCODE option
const LEN_ACCESS_TOKEN   = 64;      // String length of access token
const NAME_USER_AGENT    = 'QiiTime(HourlyTootBot)';
const QUERY_TOKEN        = 'token'; // Query key name for access token
const SLEEPTIME_ON_TOOT  = 1;       // 1 sec pause after toot
const STATUS_OK          = 0;       // Exit status on success. Line num on fail.
const TIMEZONE           = 'Asia/Tokyo'; // Our time. Japan.

//Aliases and flags
const DIR_SEP           = DIRECTORY_SEPARATOR;
const TOOT_IS_NEW       = false;
const TOOT_IS_CACHE     = true;

define('TIME_NOW', time());

/* [Settings] =============================================================== */

// Set time zone
date_default_timezone_set(TIMEZONE);

// Default settings
$settings = [
    'schema'         => 'https',
    'host'           => 'qiitadon.com',
    'method'         => 'POST',
    'endpoint'       => '/api/v1/statuses',
    'visibility'     => 'public', //public, unlisted, private
    'name_file_data' => '.tooted',
    'name_dir_data'  => '.data',
    'path_dir_curr'  => dirname(__FILE__),
];

/* [Main] =================================================================== */

// Prepare data directory and file
if (! hasSavedData()) {
    if (! is_dir(getPathDirData()) && ! mkdir(getPathDirData(), 0700)) {
        dieMsg('Error: Can not create data directory.', __LINE__);
    }

    if (! saveData()) {
        dieMsg('Error: Can not save data.', __LINE__);
    }
}

// Include last tooted info（load variable $settings）
include(getPathFileData());

// Check if access token is in $settings
if (! isSetAccessToken()) {
    // Update $settings, if access token is in request query.
    if (! setAccessTokenFromQuery()) {
        exitNoAccessTokenSet(__LINE__);
    }
    // Exit if success saving access token.
    $msg = 'Success: Access Token set. Re-access the URL with no query.';
    dieMsg($msg, STATUS_OK);
}

// Return cache. If in-time request then return last tooted info and exit.
if (isRequestInTime()) {
    returnLatestTootInfo();
    exit(STATUS_OK);
}

// Create message to toot.
$msg = createTootMsg();

// Toot the message and exit on fail.
$settings['result'] = toot($msg);

// Update time tooted
$settings['time']      = getTimeStampHourlyNow();
$settings['threshold'] = getTimeStampHourlyNow();

// Save variable $settings
if (! saveData()) {
    dieMsg('Error: Failed saving data.', __LINE__);
} else {
    // Reload and return latest tooted info and exit
    include(getPathFileData());
    returnLatestTootInfo(TOOT_IS_NEW);
    exit(STATUS_OK);
}

// Exit on unknown error
exit(__LINE__);

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

/* ---------------------------------------------------------------------- [D] */

function dieMsg($msg, $status = STATUS_OK)
{
    if (! is_string($msg)) {
        $msg = print_r($msg, true);
    }

    if (! isCli()) {
        $etag = getEtag();
        header("Content-Type: text/plain");
        header("ETag: \"${etag}\"");
    }

    echo $msg, PHP_EOL;

    exit($status);
}

/* ---------------------------------------------------------------------- [E] */

function exitNoAccessTokenSet($status)
{
    $key = QUERY_TOKEN;
    $msg = <<<EOL
No access token set.

Access with "?{$key}=<YOUR ACCESS TOKEN>" query to this URL again.

* This action is required only once.
EOL;

    dieMsg($msg, $status);
}

/* ---------------------------------------------------------------------- [G] */

function getAccessToken()
{
    static $result;

    if (isset($result)) {
        return $result;
    }

    global $settings;

    if (! isSetAccessToken()) {
        return false;
    }

    $result = $settings[QUERY_TOKEN];

    return $result;
}

function getAccessTokenFromQuery()
{
    $access_token = getValue(QUERY_TOKEN, $_GET);

    if (empty($access_token)) {
        return false;
    }

    if (! isTokenFormatValid($access_token)) {
        dieMsg('Error: Invalid access token format.', __LINE__);
    }

    return $access_token;
}

function getDefaultVisibility()
{
    global $settings;

    return getValue('visibility', $settings);
}

function getEtag()
{
    return hash('md5', getThreshold());
}

function getJsonToReturn($status_cache = TOOT_IS_CACHE, $json_option = JSON_NO_OPTION)
{
    global $settings;

    if (isCli()) {
        $json_option = $json_option | JSON_PRETTY_PRINT;
    }

    $result['threshold']  = getValue('threshold', $settings, 'unset');
    $result['is_cache']   = $status_cache;

    $result_toot = getValue('result', $settings);
    $result['id']         = getValue('id', $result_toot, 'unset');
    $result['uri']        = getValue('uri', $result_toot, 'unset');
    $result['url']        = getValue('url', $result_toot, 'unset');
    $result['created_at'] = getValue('created_at', $result_toot, 'unset');
    $result['requested_at'] = date('Y-m-d\TH:i:s.Z\Z', TIME_NOW); //Without TimeZone

    return json_encode($result, $json_option);
}

function getPathDirData()
{
    global $settings;

    $path_dir_curr  = getValue('path_dir_curr', $settings);
    $name_dir_data  = getValue('name_dir_data', $settings);

    return $path_dir_curr . DIR_SEP . $name_dir_data;
}

function getPathFileData()
{
    static $result;

    if (isset($result)) {
        return $result;
    }

    global $settings;

    $name_file_data = getValue('name_file_data', $settings);
    $path_dir_data  = getPathDirData();

    $result = $path_dir_data . DIR_SEP . $name_file_data;

    return $result;
}

function getTimeStampHourlyLast()
{
    global $settings;

    return getValue('time', $settings);
}

function getTimeStampHourlyNow()
{
    return date(FORMAT_TIMESTAMP, TIME_NOW);
}

function getThreshold()
{
    global $settings;

    return getValue('threshold', $settings, getTimeStampHourlyNow());
}

function getUrlApiToot()
{
    global $settings;

    $schema   = getValue('schema', $settings);
    $host     = getValue('host', $settings);
    $endpoint = getValue('endpoint', $settings);
    $url      = "${schema}://${host}${endpoint}";

    return $url;
}

function getValue($key, array $array, $default = '')
{
    if (empty($default)) {
        $default = false;
    }

    return (isset($array[$key])) ? $array[$key] : $default;
}

/* ---------------------------------------------------------------------- [H] */

function hasSavedData()
{
    return file_exists(getPathFileData());
}

/* ---------------------------------------------------------------------- [I] */

function isCli()
{
    return PHP_SAPI === 'cli' || empty($_SERVER['REMOTE_ADDR']);
}

function isHeaderResponseOK($response_header)
{
    foreach ($response_header as $line) {
        if (false !== strpos(strtoupper($line), '200 OK')){
            return true;
        }
    }

    return false;
}

function isRequestInTime()
{
    return (getTimeStampHourlyLast() === getTimeStampHourlyNow());
}

function isSetAccessToken()
{
    global $settings;

    return isset($settings[QUERY_TOKEN]);
}

function isTokenFormatValid($string)
{
    return ctype_alnum($string) && (LEN_ACCESS_TOKEN === strlen($string));
}

/* ---------------------------------------------------------------------- [R] */

function returnLatestTootInfo($status_cache = TOOT_IS_CACHE)
{
    if (! isCli()) {
        $etag = getEtag();
        header('content-type: application/json; charset=utf-8');
        header("ETag: \"${etag}\"");
    }

    echo getJsonToReturn($status_cache, JSON_PRETTY_PRINT), PHP_EOL;
}

/* ---------------------------------------------------------------------- [S] */

function saveData()
{
    global $settings;

    $path_file_data = getPathFileData();
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

    $settings[QUERY_TOKEN] = $access_token;

    return saveData();
}

/* ---------------------------------------------------------------------- [T] */

function toot(array $toot_msg, $visibility = null)
{
    sleep(1); // Avoid too many toots limitation (1toot/sec max)

    if (null === $visibility) {
        $visibility = getDefaultVisibility();
    }

    $status       = getValue('status', $toot_msg);
    $spoiler_text = getValue('spoiler_text', $toot_msg);

    if (empty(trim($status) . trim($spoiler_text))) {
        dieMsg('Error: No toot message specified.', __LINE__);
    }

    global $settings;

    $method       = getValue('method', $settings);
    $access_token = getAccessToken();
    $data_post    = [
            'status'     => $status,
            'visibility' => $visibility,
    ];

    if (! empty($spoiler_text)) {
        $data_post['spoiler_text'] = $spoiler_text;
    }

    $data_post      = http_build_query($data_post, "", "&");
    $name_useragent = NAME_USER_AGENT;

    $header    = implode("\r\n", [
        'Content-Type: application/x-www-form-urlencoded',
        "Authorization: Bearer ${access_token}",
        "User-Agent: ${name_useragent}",
    ]);

    $context = [
        'http' => [
            'method'  => $method,
            'header'  => $header,
            'content' => $data_post,
        ],
    ];

    $url    = getUrlApiToot();
    $result = @file_get_contents($url, false, stream_context_create($context));

    if (! isHeaderResponseOK($http_response_header)) {
        $msg  = 'Error: Bad response from Mastodon server.' . PHP_EOL;
        $msg .= 'Response header:' . PHP_EOL;
        $msg .= print_r($http_response_header, true);
        dieMsg($msg, __LINE__); // Should we log here?
    }

    return json_decode($result, JSON_OBJECT_AS_ARRAY);
}
