<?php

$list_name_file = [
    'enc',
    'dec',
    'check',
];

foreach($list_name_file as $name_file){
    $name_file_original = "{$name_file}.sh.txt";
    $name_file_signed   = "{$name_file}.sh.sig";

    echo 'Creating signature file: ', $name_file , ' ... ';

    if(! file_exists($name_file_original)){
        die('Error: File not found. ' . $name_file);
    }

    $hash_file = hash_file('sha512', $name_file_original);
    
    if(! file_put_contents($name_file_signed, $hash_file)){
        die('Error: While creating file. Check permission.');
    }

    echo 'OK' , PHP_EOL;
}


