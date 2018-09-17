<?php
namespace Qithub\dev;

/* [Constants] ============================================================== */

const DIR_SEP       = DIRECTORY_SEPARATOR;
const DO_NOT_RETURN = false;
const DO_ECHO       = false;
const DO_RETURN     = true;
const DO_NOT_ECHO   = true;
const STATUS_OK     = 0;

/* [Settings] =============================================================== */

disable_ob();

echo 'Starting ...', PHP_EOL;

$browser_default = 'safari';

$list_browser['safari']  = 'Safari';
$list_browser['chrome']  = 'Google Chrome';
$list_browser['firefox'] = 'Firefox';
$list_browser['opera']   = 'Opera';

/* [Main] =================================================================== */

if (! isCli()) {
    header("Content-Type: text/plain");

    $msg = 'This is a CLI application. Run this script via terminal on macOS.';
    dieMsg($msg, __LINE__);
}

if (! isOSmacOS()) {
    $msg = 'Not macOS: Currently this script works only on macOS.';
    dieMsg($msg, __LINE__);
}

//echo isServerAlive();
//die;

if (1 === $argc) {
    dieMsg(getMsgHelp(), __LINE__);
}

$browser = isset($argv[1]) ? strtolower($argv[1]) : $browser_default;
$index   = isset($argv[2]) ? $argv[2] : getPathIndexDefault();

if (file_exists($browser)) {
    echo 'ERROR: ';
    echoEOL('File must be set after the browser name. (See help)'. PHP_EOL);
}

if (! file_exists($index)) {
    dieMsg('No index file found. Create one or specify the index name. (See help)');
}

if (! isset($list_browser[$browser])) {
    dieMsg(getMsgHelp(), __LINE__);
}


$browser = $list_browser[$browser];
$port    = getPortRandom();

echo 'Launching browser ... ';
if (! openBrowser($browser, $port)) {
    dieMsg('Fail to open browser.', __LINE__);
}
echoEOL('OK');

echoEOL('Launching PHP built-in web server ... ');
echoEOL('(Press ctl+c to stop the server)');

$cmd = "php -S localhost:{$port} {$index}";

echo `$cmd`;

exit(STATUS_OK);

/* [Functions] ============================================================== */

/* ---------------------------------------------------------------------- [D] */

function dieMsg($msg, $status = STATUS_OK)
{
    if (! is_string($msg)) {
        $msg = print_r($msg, true);
    }

    if (! isCli()) {
        header("Content-Type: text/plain");
    }

    $status_to_display = ($status === STATUS_OK) ? 'OK' : $status;

    echo trim($msg), " (status:{$status_to_display})", PHP_EOL;
    /**
     * @SuppressWarnings(PHPMD)
     */
    exit($status);
}


function disable_ob()
{
    // Turn off output buffering and compression and auto flush buffer
    ini_set('output_buffering', 'off');
    ini_set('zlib.output_compression', false);
    ini_set('implicit_flush', true);

    ob_implicit_flush(true);

    // Clear, and turn off output buffering
    while (ob_get_level() > 0) {
        // Get the curent level
        $level = ob_get_level();
        // End the buffering
        ob_end_clean();
        // If the current level has not changed, abort
        if (ob_get_level() == $level) {
            break;
        }
    }

    // Disable apache output buffering/compression
    if (function_exists('apache_setenv')) {
        apache_setenv('no-gzip', '1');
        apache_setenv('dont-vary', '1');
    }
}

/* ---------------------------------------------------------------------- [E] */

function echoEOL($msg, $return = DO_NOT_RETURN)
{
    $msg = $msg . PHP_EOL;

    if ($return) {
        return $msg;
    }

    echo $msg;
    flushNow();
}

/* ---------------------------------------------------------------------- [F] */

function findSupportedBrowser()
{
    static $result;

    if (isset($result)) {
        return $result;
    }

    global $list_browser;

    $result = array();
    foreach ($list_browser as $key => $name_app) {
        if (isAppValidToCall($name_app)) {
            $result[$key] = $name_app;
        }
    }

    return $result;
}

function flushNow()
{
    flush();
    @ob_flush();
}

/* ---------------------------------------------------------------------- [G] */

function genSupportedBrowserList()
{
    global $list_browser;

    $list_browser = findSupportedBrowser();
    $browsers     = array_keys($list_browser);

    return trim(implode(' ', $browsers));
}

function getPathIndexDefault()
{
    $files = [
        'index.php',
        'default.php',
    ];

    foreach ($files as $file) {
        if (file_exists($file)) {
            return $file;
        }
    }

    return false;
}

function getMsgHelp()
{
    $browserlist = genSupportedBrowserList();

    $msg =<<<EOL
-------------------
Easy develop server
-------------------
Usage:
	dev <name browser> [<name file index>]

Name browser:
	Your available browser names are below:
	{$browserlist}

Name file index:
	Specify a name as index (home) page.
	Defult: index.php

EOL;

    return trim($msg) . PHP_EOL;
}

function getPortRandom($port_min = 50000, $port_max = 65535)
{
    echo 'Fetching unused random port number .';

    $cmd   = 'lsof -i -P | grep -i "listen" 2>&1';
    $rows  = array_filter(explode(PHP_EOL, `$cmd`));
    $ports = array_map(
        function ($row) {
            $row  = str_replace(':', ' ', $row);
            $cols = explode(' ', $row);
            $cols = array_values(array_filter($cols));
            return (int) $cols[9];
        },
        $rows
    );

    while (true) {
        echo '.'; //display progress marker
        flushNow();
        $port = rand($port_min, $port_max);
        if (false === array_search($port, $ports)) {
            break;
        }
    }

    echoEOL(' Port: ' . $port);

    return $port;
}

/* ---------------------------------------------------------------------- [I] */

function isCli()
{
    /**
     * @SuppressWarnings(PHPMD)
     */
    return PHP_SAPI === 'cli' || empty($_SERVER['REMOTE_ADDR']);
}

function isOSmacOS()
{
    return (false !== strpos(strtoupper(PHP_OS), 'DARWIN'));
}

function isAppValidToCall($name_app)
{
    $path_dir_lsregister = '/System/Library/Frameworks/CoreServices.framework/Versions/A/Frameworks/LaunchServices.framework/Versions/A/Support';
    $path_file_lsregister = $path_dir_lsregister . DIR_SEP . 'lsregister';

    $cmd = $path_file_lsregister . " -dump | grep -o \"/.*\\${name_app}.app/\" | head -1";

    $output = [];
    $status = STATUS_OK;

    if (! runCmd($cmd, $output, $status)) {
        return false;
    }
    return (STATUS_OK===$status) && (! empty($output));
}

function isServerAlive()
{
    return `if [ $$ -ne $(pgrep -fo "$0") ]; then echo "既に起動されています"; exit 1; fi`;
}

/* ---------------------------------------------------------------------- [O] */

function openBrowser($browser, $port)
{
    $cmd  = "open -a \"{$browser}\" \"http://localhost:{$port}\" ";

    $output = [];
    $return_var = 0;

    exec($cmd, $output, $return_var);

    return ( 0 === $return_var);
}

/* ---------------------------------------------------------------------- [R] */

function runCmd($command, &$output = [], &$return_var = 0)
{
    $command   = $command . ' 2>&1';
    exec($command, $output, $return_var);

    return (0 === $return_var);
}
