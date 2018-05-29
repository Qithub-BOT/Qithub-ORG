<?php
// Ver to test 20180522-18:55

namespace KEINOS\Update;

const DIR_SEP = DIRECTORY_SEPARATOR;
const DO_NOT_ECHO    = true;
const DO_RETURN      = true;
const RETURN_AS_ECHO = false;

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
echo "\t", '- Path updater  : ', $path_file_updater, PHP_EOL;

// Show whoami
echo "\t", '- whoami: ', runCmd('whoami'), PHP_EOL;

// Show server envs
echo "Current envs are:", PHP_EOL;
echo runCmd('env'), PHP_EOL;

// Run updater
echo 'Running updater ...', PHP_EOL;
$cmd  = '';
$cmd .= 'cd ' . $path_dir_updater . DIR_SEP . ' && ';
$cmd .= 'echo -n "Current dir is: " && ';
$cmd .= "/bin/php {$path_file_updater}";
echo runCmd($cmd), PHP_EOL, PHP_EOL;

dieMsg('Done');

/* ============================================================== [Functions] */

function dieMsg($string)
{
    echo $string, PHP_EOL;
    die;
}

function runCmd($cmd, $return = DO_NOT_ECHO)
{
    $cmd    = 'export TERM=xterm && ' . $cmd;
    $result = `$cmd 2>&1`;

    @ob_flush();
    @flush();
    
    if($return){
        return $result;
    }
    
    echo $result;
}


