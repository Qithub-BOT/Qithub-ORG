<?php
// Ver to test 20180522-18:55

namespace KEINOS\Update;

const DIR_SEP = DIRECTORY_SEPARATOR;

header("Content-Type: text/plain");

$name_file_updater = 'clone_repo.php';
$name_dir_parent   = '..';
$path_dir_root     = $_SERVER['DOCUMENT_ROOT'];

if(empty($path_dir_root)){
    echo '❌ Error: ';
    die('Run it via Web server' . PHP_EOL);
}
$path_dir_updater  = $path_dir_root . DIR_SEP . $name_dir_parent . DIR_SEP;
$path_file_updater = $path_dir_updater . DIR_SEP . $name_file_updater;
$path_file_updater = realpath($path_file_updater);

if(! file_exists($path_file_updater)){
    dieMsg('Not found:', $path_file_updater);
}

$path_dir_updater = dirname($path_file_updater);

echo '✅ Updater found.', PHP_EOL;
echo "\t", 'Updating web site from Origin ...', PHP_EOL;

$cmd  = 'TERM=xterm && ';
$cmd .= 'cd ' . $path_dir_root . DIR_SEP . ' && ';
$cmd .= 'pwd && ';
$cmd .= "php {$path_file_updater} 2>&1";

echo "\t", '- Path updater  : ', $path_file_updater, PHP_EOL;
echo "\t", '- Command to run: ', $cmd, PHP_EOL, PHP_EOL;

echo `$cmd`, PHP_EOL, PHP_EOL;

@ob_flush();
@flush();


/* ============================================================== [Functions] */

function dieMsg($string)
{
    echo $string, PHP_EOL;
    die;
}



