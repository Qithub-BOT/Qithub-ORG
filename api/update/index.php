<?php
// Ver to test 20180522-1415
const DIR_SEP = DIRECTORY_SEPARATOR;

header("Content-Type: text/plain");

$name_file_updater = 'clone_repo.php';
$name_dir_parent   = '..';
$path_dir_parent   = $name_dir_parent . DIR_SEP;

// DocumentRoot/api/update/index.php
$path_dir_root     = $path_dir_parent . $path_dir_parent . $path_dir_parent;
$path_dir_root     = realpath($path_dir_root) . DIR_SEP;
$path_file_updater = $path_dir_root . $name_file_updater;

if(file_exists($path_file_updater)){
    $cmd  = 'cd ' . $name_dir_parent . DIR_SEP . ' && ';
    $cmd .= "php {$name_file_updater} 2>&1";
    echo 'Updating ...', PHP_EOL;
    echo 'PathDirExe: ', $path_file_updater, PHP_EOL;

    //$cmd = 'cd ' . $path_dir_root . ' && pwd';
    echo 'CMD: ', $cmd, PHP_EOL;
    echo `$cmd`, PHP_EOL;

    @ob_flush();
    @flush();
}else{
    echo 'Not found:', $path_file_updater, PHP_EOL;
}