<?php
namespace Qithub\word2vec;

header("X-Robots-Tag: noindex, nofollow");

const DIR_SEP        = DIRECTORY_SEPARATOR;
const NAME_DIR_CURR  = '.';
const NAME_DIR_TPL   = '.template';
const NAME_DIR_DATA  = '.data';
const NAME_FILE_DATA = 'input.txt';
const NAME_FILE_BIN  = 'traind.bin';
const NAME_FILE_HEAD = 'header.php.tpl';
const NAME_FILE_MAIN = 'index.php.tpl';
const NAME_FILE_FOOT = 'footer.php.tpl';
const DO_NOT_RETURN  = false;
const DO_ECHO        = false;
const DO_RETURN      = true;
const DO_NOT_ECHO    = true;

include(getPathFile('header'));

$list_word = htmlspecialchars(getListToTrain());

include(getPathFile('index'));

echoEOL('<pre>');

switch(true){
    case isset($_GET['word']) && empty($_GET['word']):
        echoBR('用語が空です');
        break;
    case isset($_GET['train']):
        echoBR('学習をはじめます...');
        $result = '';
        if (train($result)) {
            echoBR($result);
        }
        flushContents();
        break;
    case isset($_GET['distance']):
        break;
    default:
        break;
}

echoEOL('</pre>');
echoEOL('</body>');

include(getPathFile('footer'));

/* [Functions] ============================================================== */

/* ---------------------------------------------------------------------- [D] */

function dieMsg($message)
{
    header('Content-Type: text/plain');

    echo (string) $message;

    exit(1);
}

/* ---------------------------------------------------------------------- [E] */

function echoEOL($string, $return = DO_NOT_RETURN)
{
    $result = $string . PHP_EOL;

    if(DO_RETURN === $return){
        return $result;    
    }
    
    echo $result;
}

function echoBR($string, $return = DO_NOT_RETURN)
{
    $result = echoEOL($string . '<br>', DO_RETURN);

    if(DO_RETURN === $return){
        return $result;    
    }
    
    echo $result;
}

function echoPRE($string, $return = DO_NOT_RETURN)
{
    $result = echoEOL('<pre>' . $string . '</pre>', DO_RETURN);

    if(DO_RETURN === $return){
        return $result;    
    }
    
    echo $result;
}


/* ---------------------------------------------------------------------- [F] */

function flushContents()
{
    @ob_flush();
    @flush();
}

/* ---------------------------------------------------------------------- [G] */

function getLineFromFile($line_number)
{
    $csv = new SplFileObject(PATH, 'r');
}

function getListToTrain()
{
    $path_file_train = getPathFile('train');

    if (false === $path_file_train) {
        dieMsg('Error: No train file found at: ' .  $path_file_train);
    }

    $list_word = file_get_contents($path_file_train);

    if (false === $list_word) {
        dieMsg('Error: Can not read train file or is empty.');
    }

    return $list_word;
}

function getPathDir($key)
{
    $key = strtolower((string) $key);

    switch ($key) {
        case 'template':
            $name = NAME_DIR_TPL;
            break;
        case 'data':
            $name = NAME_DIR_DATA;
            break;
        default:
            $name = 'n/a';
            break;
    }

    $path = realpath(NAME_DIR_CURR . DIR_SEP . $name);

    return (is_dir($path)) ? $path : false;
}

function getPathFile($key)
{
    $key = strtolower((string) $key);

    switch ($key) {
        case 'train':
            $name_file = NAME_FILE_DATA;
            $name_dir  = 'data';
            break;
        case 'bin':
            $name_file = NAME_FILE_BIN;
            $name_dir  = 'data';
            break;
        case 'header':
            $name_file = NAME_FILE_HEAD;
            $name_dir  = 'template';
            break;
        case 'index':
            $name_file = NAME_FILE_MAIN;
            $name_dir  = 'template';
            break;
        case 'footer':
            $name_file = NAME_FILE_FOOT;
            $name_dir  = 'template';
            break;
        default:
            $name_file = 'n/a';
            $name_dir  = 'n/a';
            break;
    }

    $path = getPathDir($name_dir);

    return is_dir($path) ? $path . DIR_SEP . $name_file : false;
}

/* ---------------------------------------------------------------------- [T] */

function train(&$result)
{
    flushContents();

    $path_file_input = getPathFile('train');
    $path_bin_output = getPathFile('bin');

    if (! file_exists($path_file_input)) {
        return "echo 'Train file not found at: {$path_file_input}'";
    }

    $msg_error = '';
    $result    = word2vec([
        // 'bin'    => '/usr/local/bin/word2vec ',
        'train'     => $path_file_input,
        'output'    => $path_bin_output,
        'size'      => '100',
        'sample'    => '1e-4',
        'binary'    => '1', // 0 for text output
        'min-count' => '1',
    ], $msg_error);

    if (false === $result) {
        $result = $msg_error;
    }

    return (false !== $result);
}

/* ---------------------------------------------------------------------- [W] */

function word2vec(array $option, $msg_error = '')
{
    // This is a prototype of word2vec wrapper function.
    // Do not include external user function in to this function.

    $getPathBin = function ($option) {

        $path = isset($option['bin']) ? trim($option['bin']) : '';

        if (isset($path) && file_exists($path) && is_file($path)) {
            return $path;
        }
        $output   = '';
        $status   = 0;
        $cmd      = 'which word2vec';
        $lastline = exec($cmd, $output, $status);

        return ( 0 === $status) ? $lastline : './word2vec';
    };

    $getOption = function ($key, $array, $default = '') {

        $result = empty($default) ? '' : " -{$key} {$default}";

        if (! isset($array[$key])) {
            return $result;
        }

        $value = $array[$key];

        if (empty(trim($value))) {
            return $result;
        }

        switch ($key) {
            case 'train':
            case 'output':
                $value = '"' . $value . '"'; //quote for paths within space
                break;
            default:
                //
        }

        return " -{$key} {$value}";
    };

    //bin path of external word2vec command
    $cmd  = $getPathBin($option) . ' ';

    // About the default values see original word2vec help.
    $cmd .= $getOption('train',      $option);
    $cmd .= $getOption('output',     $option);
    $cmd .= $getOption('size',       $option, '100');
    $cmd .= $getOption('window',     $option, '5');
    $cmd .= $getOption('sample',     $option, '1e-3');
    $cmd .= $getOption('hs',         $option, '0');
    $cmd .= $getOption('negative',   $option, '5');
    $cmd .= $getOption('threads',    $option, '12');
    $cmd .= $getOption('iter',       $option, '5');
    $cmd .= $getOption('min-count',  $option, '5');
    $cmd .= $getOption('alpha',      $option);
    $cmd .= $getOption('classes',    $option, '0');
    $cmd .= $getOption('debug',      $option, '2');
    $cmd .= $getOption('binary',     $option, '0');
    $cmd .= $getOption('save-vocab', $option);
    $cmd .= $getOption('read-vocab', $option);
    $cmd .= $getOption('cbow',       $option, '1');
    $cmd .= " 2>&1";

    // Execute word2vec
    $output      = array();
    $flag_result = 0;
    $lastline    = exec($cmd, $output, $flag_result);
    $result      = implode(PHP_EOL, $output);

    // On error retun by reference ($msg_error)
    if (0 !== $flag_result) {
        $msg_error = $result;
        return $lastline;
    }

    return (0 === $flag_result) ? $result : false;
}
