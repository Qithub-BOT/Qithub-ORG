<?php
namespace Qithub\dev;

/* [Constants] ============================================================== */

const DIR_SEP       = DIRECTORY_SEPARATOR;
const DO_NOT_RETURN = false;
const DO_ECHO       = false;
const DO_RETURN     = true;
const DO_NOT_ECHO   = true;
const STATUS_OK     = 0;

/* [Functions] ============================================================== */

include_once(dirname(__FILE__) . DIR_SEP . 'functions_dev.php.inc');

/* [Settings] =============================================================== */

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
