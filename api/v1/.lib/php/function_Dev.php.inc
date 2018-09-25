<?php
namespace Qithub\dev;

/* [Functions] ============================================================== */

/* ---------------------------------------------------------------------- [D] */

/** * @SuppressWarnings(PHPMD.ExitExpression) */
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

    exit($status);
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

/** * @SuppressWarnings(PHPMD.Superglobals) */
function isCli()
{
    return PHP_SAPI === 'cli' || empty($_SERVER['REMOTE_ADDR']);
}

function isOSmacOS()
{
    return (false !== strpos(strtoupper(PHP_OS), 'DARWIN'));
}

function isAppValidToCall($name_app)
{
    $path_dir_lsregister  = '/System/Library/Frameworks/CoreServices.framework';
    $path_dir_lsregister .= '/Versions/A/Frameworks/LaunchServices.framework';
    $path_dir_lsregister .= '/Versions/A/Support';

    $path_file_lsregister = $path_dir_lsregister . DIR_SEP . 'lsregister';

    $args = " -dump | grep -o \"/.*\\{$name_app}.app/\" | head -1";
    $cmd  = $path_file_lsregister . $args;

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
