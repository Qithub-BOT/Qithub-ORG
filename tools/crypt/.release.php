<?php

if(isset($argv) && (3 !== count($argv))){
    $name_file = pathinfo(__FILE__, PATHINFO_BASENAME);;
    echo <<<EOL

使い方： ./${name_file} <github user> <private key>

- <github user>： GitHub のアカウント名
- <private key>： GitHub で公開している公開鍵のペアとなる秘密鍵のパス

EOL;
    dieMsg('');
}

$list_name_file = [
    'enc',
    'dec',
    'check',
    'sign',
];

$checksums = '';
$algorithm = 'SHA512';

echo '- Checking checksum files ';

foreach ($list_name_file as $name_file) {
    echo '.';

    if (! file_exists($name_file)) {
        dieMsg('Error: File not found.: ' . $name_file);
    }

    $hash = hash_file($algorithm, $name_file);

    if (empty($hash)) {
        dieMsg('Error: While creating checksum value. Check algorithm.');
    }

    $checksums .= "{$algorithm}({$name_file})= {$hash}" . PHP_EOL;

    echo '.';
}

echo ' OK', PHP_EOL;
echo '- Creating checksum list file ... ';

$name_file_checksums = 'checksum.' . strtolower($algorithm);

if (! file_put_contents($name_file_checksums, $checksums)) {
    dieMsg('Error: While creating checksum file. Check dir permission.');
}

echo 'OK' , PHP_EOL;

echo '- Signing checksum files:', PHP_EOL, PHP_EOL;

$github_user = $argv[1];
$private_key = $argv[2];

echo `./sign $github_user '$private_key' $name_file_checksums`;

function dieMsg($string)
{
    $string = (string) $string;

    die($string . PHP_EOL);
}
