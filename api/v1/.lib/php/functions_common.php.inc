<?php

namespace { // 汎用のため明示的にグローバル名前空間に設置

/* [Constants] ============================================================== */

// 既存定数のエイリアス
    const DIR_SEP = DIRECTORY_SEPARATOR;

// 統一すべき情報の定数
    const TIMEZONE = 'Asia/Tokyo'; // Our time. Japan.

/* [Functions] ============================================================== */

/* ---------------------------------------------------------------------- [D] */

    /** * @SuppressWarnings(PHPMD.ExitExpression) */
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

        exit($status);
    }

/* ---------------------------------------------------------------------- [G] */

    function getPathDirParent()
    {
        // 呼び出し元のディレクトリ・パスを取得
        $result = getValue(0, debug_backtrace());

        return dirname(getValue('file', $result, __FILE__));
    }

    function getValue($key, array $array, $default = '')
    {
        if (empty($default)) {
            $default = false;
        }

        return (isset($array[$key])) ? $array[$key] : $default;
    }

/* ---------------------------------------------------------------------- [I] */

    /** * @SuppressWarnings(PHPMD.SuperGlobals) */
    function isCli()
    {
        return PHP_SAPI === 'cli' || empty($_SERVER['REMOTE_ADDR']);
    }


}
