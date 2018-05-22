<?php
// Ver to test 20180522-18:55

namespace KEINOS\Update;

const DIR_SEP = DIRECTORY_SEPARATOR;

header("Content-Type: text/plain");

$name_file_updater = 'clone_repo.php';
$name_dir_parent   = '..';
$path_dir_parent   = $name_dir_parent . DIR_SEP;

// DocumentRoot/api/update/index.php
$path_dir_root     = $path_dir_parent . $path_dir_parent . $path_dir_parent;
$path_dir_root     = realpath(__DIR__ . DIR_SEP . $path_dir_root);
$path_file_updater = $path_dir_root . DIR_SEP . $name_file_updater;

if(file_exists($path_file_updater)){
    echo 'Updating web site from Origin ...', PHP_EOL;

    $cmd  = 'TERM=xterm && ';
    $cmd .= 'cd ' . $path_dir_root . DIR_SEP . ' && ';
    $cmd .= 'pwd &&';
    $cmd .= "php {$name_file_updater} 2>&1";

    echo 'PathDirExe: ', $path_file_updater, PHP_EOL;
    echo 'CMD: ', $cmd, PHP_EOL, PHP_EOL;

    echo `$cmd`, PHP_EOL, PHP_EOL;

    @ob_flush();
    @flush();
}else{
    echo 'Not found:', $path_file_updater, PHP_EOL;
}

/* ============================================================== [Functions] */





