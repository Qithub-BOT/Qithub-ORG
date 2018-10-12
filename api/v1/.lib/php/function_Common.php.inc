<?php
namespace { // 汎用のため明示的にグローバル名前空間に設置

/* [Constants] ============================================================== */

// 内部環境定数
    const REQUEST_LIMIT_PER_SEC = 1; // API リクエスト時の sleep 時間（秒）
    const TIMEZONE          = 'Asia/Tokyo';
    const VER_PHP_REQUIRED  = '7.1.0'; // PHP v5.6 でも動くが v7.1の方が速いため
    const VER_HHVM_REQUIRED = '5.6.0'; // Qithub.tk の PHP は HHVM なため
    const STATUS_OK         = 0; // 成功時のExit ステータス。失敗時は行番号のこと。
    const NO_FLAGS          = 0;
    const NO_OPTIONS        = 0;
    const NO_CONTEXT        = null;
    const LEN_ID_TOOT       = 18; // Qiitadon のトゥートIDの長さ
    const LEN_ID_ITEM_QIITA = 20; // Qiita記事IDの長さ
    const JSON_DECODE_FAIL  = null;  // json_decode 失敗時は null
    const JSON_ENCODE_FAIL  = false; // json_encode 失敗時は false
    const RETURN_AS_BOOL    = 0b00000000;
    const RETURN_AS_STRING  = 0b00000001;
    const RETURN_AS_ARRAY   = 0b00000010;
    const RETURN_AS_JSON    = 0b00000100;
    const RETURN_AS_ID      = 0b00001000;
    
    define('JSON_COMMON_OPTION',JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// 既存定数のエイリアス
    const DIR_SEP = DIRECTORY_SEPARATOR;

/* [Functions] ============================================================== */

/* ---------------------------------------------------------------------- [D] */

    function dieMsg($msg, $status = STATUS_OK)
    {
        if (! is_string($msg)) {
            $msg = print_r($msg, true);
        }

        if (! \isCli() && ! headers_sent()) {
            header("Content-Type: text/plain");
        }

        $status_to_display = ($status === STATUS_OK) ? 'OK' : $status;

        echo trim($msg), " (status:{$status_to_display})", PHP_EOL;

        /** * @SuppressWarnings("PHPMD.ExitExpression") */
        exit($status);
    }

/* ---------------------------------------------------------------------- [F] */

    function findStausCodeFromHeader(array $headers)
    {
        foreach ($headers as $header) {
            // ステータスヘッダを検索('HTTP/1.1 404 Not Found'など）
            if (false === strpos(strtolower($header), 'http/1')) {
                continue;
            }

            return (int) explode(' ', $header)[1]; //404,301,200 などを抜き出す
        }

        return false;
    }

    function flushNow()
    {
        flush();
        @ob_flush();
    }

/* ---------------------------------------------------------------------- [G] */
    /**
     * file_get_contents 関数のラッパー.
     *
     * ファイル保存と読み込みのバッティングが起きた場合はここで回避予定。
     */
    function getContentsFromFile($path_file)
    {
        if (! file_exists($path_file)) {
            return null;
        }

        return file_get_contents($path_file);
    }

    function getContentsFromUrl($url)
    {
        static $results;

        $crc = hash('crc32', $url);

        if (isset($results[$crc])) {
            return $results[$crc];
        }

        sleep(REQUEST_LIMIT_PER_SEC); //アクセス過多による BAN 防止

        // コンテンツの取得
        $http_response_header = null;
        $result = @file_get_contents($url);

        if (false === $result) {
            $error  = "Error occurred while fetching url: {$url}" . PHP_EOL;
            $error .= print_r($http_response_header, true) . PHP_EOL;
            dieMsg($error);
        }

        $results[$crc] = $result;

        return $result;
    }

    function getPathDirParent()
    {
        // 呼び出し元のディレクトリ・パスを取得
        $result = getValue(0, debug_backtrace());

        return dirname(getValue('file', $result, __FILE__));
    }

    function getStatusCodeFromUrl($url)
    {
        $headers = @get_headers($url);

        if (false === $headers) {
            return false;
        }

        return (int) findStausCodeFromHeader($headers);
    }

    function getUrlToRedirect($url)
    {
        $headers = @get_headers($url);

        if (false === $headers) {
            return false;
        }

        foreach ($headers as $header) {
            // Location ヘッダーから URL を抽出（'Location: https://...'）
            if (false === strpos(strtolower($header), 'location: ')) {
                continue;
            }
            return getValue(1, explode(' ', $header), false);
        }

        return false;
    }

    function getValue($key, array $array, $default = null)
    {
        return (isset($array[$key])) ? $array[$key] : $default;
    }

/* ---------------------------------------------------------------------- [I] */

    function isCli()
    {
        /** * @SuppressWarnings("PHPMD.SuperGlobals") */
        return PHP_SAPI === 'cli' || empty($_SERVER['REMOTE_ADDR']);
    }

    /**
     * @todo この関数名は isVersionPHPCovered() が妥当と思われる.
     */
    function isCoveredVersionPHP()
    {
        $ver_current  = phpversion();
        $is_hhvm      = (false !== strpos(strtolower($ver_current), 'hhvm'));
        $ver_required = ($is_hhvm) ? VER_HHVM_REQUIRED : VER_PHP_REQUIRED;

        return version_compare($ver_current, $ver_required, '>');
    }

    function isUrl404($url)
    {
        $status = getStatusCodeFromUrl($url);

        if (false === $status) {
            dieMsg('Invalid URL given: ' . $url, __LINE__);
        }

        return 404 === $status;
    }

    function isValidFormatId($service, $id_verify)
    {
        if (! ctype_alnum($id_verify)) {
            return false;
        }

        switch ($service) {
            case 'user_qiita':
                return is_numeric($id_verify);
            case 'toot_qiitadon':
                return LEN_ID_TOOT === strlen($id_verify);
            case 'item_qiita':
                return LEN_ID_ITEM_QIITA === strlen($id_verify);
        }

        return false;
    }

    function isValidJson($json_string)
    {
        $json_string = trim($json_string);
        if ('{' !== $json_string[0]) {
            return false;
        }

        return JSON_DECODE_FAIL !== json_decode($json_string);
    }

/* ---------------------------------------------------------------------- [P] */

    /**
     * file_put_contents関数の排他処理付きラッパー.
     *
     * ファイル保存と読み込みのバッティングが起きた場合はここで回避予定。
     */
    function putContentsToFile($path_file, $data, $flags = LOCK_EX, $resource = NO_CONTEXT)
    {
        return file_put_contents($path_file, $data, $flags, $resource);
    }


//End Of Namespace
}
