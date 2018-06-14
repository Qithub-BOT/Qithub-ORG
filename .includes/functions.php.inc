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

/* NG function that's not camelCase */
function echo_sample()
{
    return 'hoge';
}