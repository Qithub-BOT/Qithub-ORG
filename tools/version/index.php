<?php
/**
 * Displays server installed program lang and software.
 */

/* ============================================================= [Initialize] */

header("X-Robots-Tag: noindex, nofollow");

const PATH_DIR_CACHE = './.cache';
const DIR_SEP        = DIRECTORY_SEPARATOR;

define('UPDATE_CACHE', isset($_GET['update']));

if(! is_dir(PATH_DIR_CACHE)){
    mkdir(PATH_DIR_CAHE, 0600);
}

/* =============================================================== [Settings] */

// include lists
$lists = array();
include_once('list_version.php.inc');

/* =================================================================== [Main] */

$key  = isset($_GET['key'])  ? $_GET['key']  : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

/* Has request query. Show request as text. */

if (! empty($key) && ! empty($type)) {
    header('Content-Type: text/plain');
    if (isset($lists[$type][$key])) {
        $title  = ucfirst($type) . ": ";
        $title .= $key;
        echo_eol(trim($title). PHP_EOL);
        echo_version($lists[$type][$key]);
    } else {
        echo_eol('Not set');
    }
    die;
}

/* Show list menu as html */

echo_header();

// start.body.html
echo_eol('<body>');

// Category
foreach ($lists as $type => $list) {
    $type       = htmlentities($type);
    $type_head2 = ucfirst($type);

    echo_eol("<h2>${type_head2}</h2>");
    echo_eol('<ul>');

    ksort($list, SORT_NATURAL);

    // Menu
    foreach ($list as $menu => $cmd) {
        $href = create_link($menu);
        $menu = htmlentities($menu);
        $type = urlencode($type);
        echo_eol("<li><a href='${href}&type=${type}'>${menu}</a></li>");
    }
    echo_eol('</ul>');
}

/* Miscellaneous and/or custom menu */
echo_eol('<h2>Env</h2>');

$os     = runCmd('cat /etc/redhat-release');
$path   = runCmd('echo $PATH');
$whoami = runCmd('whoami');
$df     = runCmd('df -h');
$env    = runCmd('printenv');

echo_eol('<ul>');
echo_eol("<li>OS = {$os}</li>");
echo_eol("<li>PATH = {$path}</li>");
echo_eol("<li>WHOAMI = {$whoami}</li>");
echo_eol("<li>Diskspace(df)<ul><li><pre>{$df}</pre></li></ul></li>");
echo_eol("<li>環境変数一覧<ul><li><pre>{$env}</pre></li></ul></li>");
echo_eol('</ul>');

// end.body.html
echo_eol('</body>');
echo_eol('</html>');

/* ============================================================== [Functions] */

/* ---------------------------------------------------------------------- [C] */

function create_link($id_key)
{
    $id_key = (string) $id_key;
    $id_key = urlencode($id_key);

    return "?key=${id_key}";
}

/* ---------------------------------------------------------------------- [E] */

function echo_eol($string)
{
    echo $string . PHP_EOL;
}

function echo_header()
{
    echo <<<EOL
<!DOCTYPE html>
<html>
<head>
	<title>Version info - Qithub.tk</title>
	<meta name="robots" content="noindex">
</head>

EOL;
}

function echo_version($array)
{
    foreach ($array as $title => $cmd) {
        $title      = ucfirst(trim($title));
        $cmd_result = trim(runCmd($cmd));
        $cmd_result = indent($cmd_result);
        echo_eol("{$title}: {$cmd_result}");
    }
}

/* ---------------------------------------------------------------------- [G] */

function getCache($command)
{
    $path_file_cache = getPathCache($command);

    if (! file_exists($path_file_cache)) {
        return false;
    }

    return unserialize(file_get_contents($path_file_cache));
}

function getNameCache($command)
{
    return 'cache_' . md5(trim($command));
}

function getPathCache($command)
{
    $name_cache     = getNameCache($command);
    $path_dir_cache = realpath(PATH_DIR_CACHE);

    return $path_dir_cache . DIR_SEP . $name_cache;
}

/* ---------------------------------------------------------------------- [H] */

function hasCache($command)
{
    if (UPDATE_CACHE) {
        return false;
    }

    $path_file_cache = getPathCache($command);

    return file_exists($path_file_cache);
}

/* ---------------------------------------------------------------------- [I] */

function indent($string)
{
    $result = trim((string) $string);

    if (0 < mb_substr_count($result, PHP_EOL)) {
        $array  = explode(PHP_EOL, $result);
        $result = PHP_EOL;
        foreach ($array as $line) {
            $result .= "\t" . $line . PHP_EOL;
        }
        return $result;
    }

    return $result;
}

/* ---------------------------------------------------------------------- [R] */

function runCmd($command_original, &$output = '', $return_var = 0)
{
    if (hasCache($command_original)) {
        return getCache($command_original);
    }

    $command = (string) $command_original;
    $output  = array();
    $result  = '';
    $pipe    = '2>&1';

    if (false === strpos(str_replace(' ', '', $command), $pipe)) {
        $command = $command . ' ' . $pipe;
    }

    $last_line = exec($command, $output, $return_var);
    $output    = trim(implode(PHP_EOL, $output)) . PHP_EOL;

    if (0 !== $return_var) {
        $result .= 'ERROR:' . PHP_EOL;
    }

    $result .= $output . PHP_EOL;

    updateScreen();

    if (saveCache($command_original, $result)) {
        return $result;
    }

    return 'Error: Can not save cache file.';
}

/* ---------------------------------------------------------------------- [S] */

function saveCache($command, $data)
{
    $path_file_cache = getPathCache($command);

    return file_put_contents($path_file_cache, serialize($data));
}

/* ---------------------------------------------------------------------- [U] */

function updateScreen()
{
    @ob_flush();
    @flush();
}
