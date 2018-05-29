<?php

/* ============================================================= [Initialize] */

header("X-Robots-Tag: noindex, nofollow");

$cmd = 'export GOPATH=$HOME/go';
echo runCmd($cmd);

$lists = array();

/* =============================================================== [Settings] */

$lists['language'] = [
    'PHP5' => [
        'path'    => 'which php',
        'version' => 'php -v',
        ],
    'HHVM' => [
        'path'    => 'which hhvm',
        'version' => 'hhvm --version',
        ],
    'PHP7' => [
        'path'    => 'which php7',
        'version' => 'php7 -v',
        ],
    'Golang' => [
        'path'    => 'echo /usr/local/go/bin/go',
        'version' => '/usr/local/go/bin/go version',
        ],
    'Node.js' => [
        'path'    => 'which node',
        'version' => 'node --version',
        ],
    'Python2' => [
        'path'    => 'which python2.7',
        'version' => '/usr/bin/python2.7 --version',
        ],
    'Python3' => [
        'path'    => 'which python3.6',
        'version' => 'python3.6 --version',
        ],
    'Ruby' => [
        'path'    => 'which ruby',
        'version' => 'ruby -v',
        ],
];

$lists['program'] = [
    'MeCab' => [
        'version' => 'mecab --version',
        ],
    'MeCab dictionary info' => [
        'MeCab Directory info' => 'mecab --dictionary-info',
        ],
    'mecab-config dictionary path' => [
        'MeCab Directory info' => 'mecab-config --dicdir',
        ],
    'mecab-config dictionary list' => [
        'MeCab Directory info' => 'ls -la $(echo \`mecab-config --dicdir\`)',
        ],
    'npm' => [
        'version' => 'npm --version',
        ],
    'npm package' => [
        'version' => 'npm ls -g',
        ],
    'pip' => [
        'version' => 'pip --version',
        ],
    'pip package installed' => [
        'version' => 'pip list',
        ],
    'Python package' => [
        'list of Python package' => 'pip freeze',
        ],
    'phpinfo() - PHP5 cli' => [
        'info PHP5' => 'php -r \'phpinfo();\'',
        ],
    'phpinfo() - PHP7 cli' => [
        'info PHP7' => 'php7 -r \'phpinfo();\'',
        ],
    'phpinfo() - HHVM' => [
        'info HHVM' => 'hhvm --info',
        ],
    'Git' => [
        'version' => 'git --version',
        ],
    'RipGrep(rg)' => [
        'version' => 'rg --version',
        ],
    'Go env' => [
        "env value" => '/usr/local/go/bin/go env',
        ],
    'yum' => [
        "version" => 'yum --version',
        ],
    'yum package' => [
        "Version list of installed package" => 'yum list installed',
        ],
];

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

    ksort($list);

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

$path   = runCmd('echo $PATH');
$whoami = runCmd('whoami');

echo_eol('<ul>');
echo_eol("<li>PATH = {$path}</li>");
echo_eol("<li>WHOAMI = {$whoami}</li>");
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

function runCmd($cmd, $return = true)
{
    $cmd    = (string) $cmd;
    $result = `$cmd 2>&1`;

    if ($return) {
        return $result;
    }

    echo $result;
}
