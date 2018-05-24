<?php

namespace Qithub\fn;

function echoParrotry($message, $return=false)
{
    $message = (string) $message;

    if($return){
        return $message;
    }

    echo $message, PHP_EOL;
}