<?php

$list_name_file = [
    'enc',
    'dec',
    'check',
];

$checksums = '';
$algorithm = 'SHA512';

foreach($list_name_file as $name_file){

    $name_file_checksum = "{$name_file}." . strtolower($algorithm);

    echo 'Creating checksum file: ', $name_file , ' ... ';

    if(! file_exists($name_file)){
        dieMsg('Error: File not found.: ' . $name_file);
    }

    $hash = hash_file($algorithm, $name_file);

    if(empty($hash)){
        dieMsg('Error: While creating checksum value. Check algorithm.');
    }
    
    if(! file_put_contents($name_file_checksum, $hash)){
        dieMsg('Error: While creating checksum file. Check dir permission.');
    }

    echo 'OK' , PHP_EOL;
    $checksums .= "{$algorithm}({$name_file}) {$hash}" . PHP_EOL;
}

echo 'Creating checksum list file ...';

$name_file_checksums = 'checksum.' . strtolower($algorithm);

if(! file_put_contents($name_file_checksums, $checksums)){
    dieMsg('Error: While creating checksum file. Check dir permission.');
}

echo 'OK' , PHP_EOL;


function dieMsg($string)
{
    $string = (string) $string;
    
    die($string . PHP_EOL);
}