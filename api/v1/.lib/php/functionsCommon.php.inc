<?php

namespace { // 汎用のため明示的にグローバル名前空間に設置


/* [Constants] ============================================================== */

// 内部環境定数
    const TIMEZONE          = 'Asia/Tokyo'; // Our time. Japan.
    const VER_PHP_REQUIRED  = '7.1.0'; // PHP v5.6 でも動くが v7.1の方が速いため
    const VER_HHVM_REQUIRED = '5.6.0'; // Qithub.tk の PHP は HHVM なため

// 既存定数のエイリアス
    const DIR_SEP = DIRECTORY_SEPARATOR;

/* [Functions] ============================================================== */

/* ---------------------------------------------------------------------- [D] */

    function dieMsg($msg, $status = STATUS_OK)
    {
        if (! is_string($msg)) {
            $msg = print_r($msg, true);
        }

        if (! isCli()) {
            header("Content-Type: text/plain");
        }

        $status_to_display = ($status === STATUS_OK) ? 'OK' : $status;

        echo trim($msg), " (status:{$status_to_display})", PHP_EOL;

        /** * @SuppressWarnings("PHPMD.ExitExpression") */
        exit($status);
    }

/* ---------------------------------------------------------------------- [G] */

    function getValue($key, array $array, $default = '')
    {
        if (empty($default)) {
            $default = false;
        }

        return (isset($array[$key])) ? $array[$key] : $default;
    }

/* ---------------------------------------------------------------------- [I] */

    function isCli()
    {
        /** * @SuppressWarnings("PHPMD.SuperGlobals") */
        return PHP_SAPI === 'cli' || empty($_SERVER['REMOTE_ADDR']);
    }

    function isCoveredVersionPHP()
    {
        $ver_current  = phpversion();
        $is_hhvm      = (false !== strpos(strtolower($ver_current), 'hhvm'));
        $ver_required = ($is_hhvm) ? VER_HHVM_REQUIRED : VER_PHP_REQUIRED;

        return version_compare($ver_current, $ver_required, '>');
    }


//End Of Namespace
}
