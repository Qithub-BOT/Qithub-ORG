<?php
namespace Qithub\crypt;

echoTitle("
================================================================================
  GitHub Cyript Downloader
================================================================================
by Qithub-ORG ( https://github.com/Qithub-BOT/Qithub-ORG )

");

/* [SETTINGS] =============================================================== */

const BR       = PHP_EOL;
const DIR_SEP  = DIRECTORY_SEPARATOR;
const IS_EMPTY = false;
const SIGNER   = 'KEINOS'; // '.sig' ファイルの署名者の GitHub アカウント名
const TAB      = "\t";

$options = [
    'url_base'      => 'https://qithub.tk/tools/crypt/',
    'name_dir_dl'   => 'crypt',
    'path_dir_home' => $_SERVER['HOME'],
    'list_files'    => [
        'enc','dec','sign','verify','check',
        'checksum.sha512','checksum.sha512.sig',
    ],
    'name_file_signed'    => 'checksum.sha512',
    'name_file_signature' => 'checksum.sha512.sig',
];

/* [MAIN] =================================================================== */

download($options);
verify($options);
//chmod


/* [FUNCTIONS] ============================================================== */

/* ---------------------------------------------------------------------- [C] */

function createDirDownload($options)
{
    static $isCreated;

    echo '- Creating download dir ... ';
    
    $path_dir_dl = getPathDownload($options);

    if (isset($isCreated) || is_dir($path_dir_dl)) {
        echo 'OK', BR;
        echo "\t", '(Dir already exists: ', $path_dir_dl, ' )', BR;
        return $isCreated;
    }

    if (! $path_dir_home || ! $name_dir_dl) {
        echo 'Error: Can NOT create download dir.', BR;
        echo 'Invalid option settings.', BR;
        return false;
    }

    $isCreated = mkdir($path_dir_dl);

    if (! $isCreated) {
        echo 'Error: Fail to create dir at: ', $path_dir_dl, BR;
        echo 'Check permission.', BR;
        return false;
    }

    echo ' OK', BR;
    echo "\t", 'PATH:', $path_dir_dl, BR;

    return $isCreated;
}

/* ---------------------------------------------------------------------- [D] */

function download($options)
{
    $isSuccess  = true;
    $list_files = getValue('list_files', $options);

    if (false === $list_files) {
        return 'Error: No file list specified to download.';
    }

    createDirDownload($options);

    echo '- Downloading files ...';

    foreach ($list_files as $name_file) {
        $result = downloadEach($name_file, $options);
        $isSuccess = $isSuccess && $result;
    }
    
    echo ' ', ($isSuccess) ? 'OK' : 'NG', BR;
    
    return $isSuccess;
}

function downloadEach($name_file, $options)
{
    $url_base      = getValue('url_base', $options);
    $path_dir_home = getValue('path_dir_home', $options);
    $name_dir_dl   = getValue('name_dir_dl', $options);

    if (! $url_base || ! $path_dir_home || ! $name_dir_dl) {
        echo 'Error: Can NOT downlod file. Invalid option settings.', BR;
        return false;
    }

    $url_fetch   = $url_base . $name_file;
    $path_dir_dl = $path_dir_home . DIR_SEP . $name_dir_dl;
    $path_file   = $path_dir_dl . DIR_SEP . $name_file;

    $result = copy($url_fetch, $path_file);

    echo ($result) ? '.' : 'x'; // 進捗表示 '.'->成功 'x'->失敗

    return $result;
}

/* ---------------------------------------------------------------------- [E] */

function echoTitle($title)
{
    if (! isCLI()) {
        header('Content=type: text/plain; charset=utf=8');
        echo  'このスクリプトは CLI （ターミナル/コマンドライン）経由で実行して'
             .'ください。';
        exit(0);
    }

    echo (string) $title;
}

/* ---------------------------------------------------------------------- [G] */

function getValue($key, $array)
{
    return (isset($array[$key])) ? $array[$key] : false;
}

function getPathDownload($options)
{
    static $path_dir_dl;
    
    if(isset($path_dir_dl)){
        return $path_dir_dl;
    }
    
    $path_dir_home = getValue('path_dir_home', $options);
    $name_dir_dl   = getValue('name_dir_dl', $options);

    if (! $path_dir_home || ! $name_dir_dl) {
        echo 'Error: Missing name and/or path for downloading.', BR;
        echo TAB, 'Invalid option settings.', BR;
        return false;
    }

    $path_dir_dl = $path_dir_home . DIR_SEP . $name_dir_dl;

    return $path_dir_dl;
}

function getPublicKey($github_user, $index=0)
{
    $keys = file_get_contents(getUrlGitHubPublicKeys($github_user));
    print_r($keys);
    return trim( explode(BR, $keys)[$index]);
}

function getUrlGitHubPublicKeys($github_user){
    $github_user = (string) $github_user;
    
    return "https://github.com/${github_user}.keys";
}

/* ---------------------------------------------------------------------- [I] */

function isCLI()
{
    return PHP_SAPI === 'cli' || empty($_SERVER['REMOTE_ADDR']);
}

/* ---------------------------------------------------------------------- [V] */

function verify($options)
{
    $isSuccess = true;

    echo '- Verifying files:', BR;

    $result    = verifySign($options);
    $isSuccess = $isSuccess && $result;

}

function verifySign($options)
{
    echo TAB, 
    $name_user_github = SIGNER;
    $path_dir_dl      = getPathDownload($options);
    
    echo getPublicKey(SIGNER), BR;
}


