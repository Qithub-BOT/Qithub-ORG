<?php

namespace { // 汎用のため明示的にグローバル名前空間に設置


/* [Constants] ============================================================== */

// 内部環境定数
    const LEN_ACCESS_TOKEN   = 64; // Mastodn アクセストークン長
    const NAME_USER_AGENT    = 'Qithub(bot) by Qithub ORG'; //これにAPP名を加えること
    const SLEEPTIME_ON_TOOT  = 1;      // API利用時の連投防止の待ち時間（秒）
    const DEFAULT_SCHEMA     = 'https';
    const DEFAULT_VISIBILITY = 'private';

// フラグ
    const IS_ERROR        = true;
    const IS_EXTERNAL_CMD = false;
    const IS_INTERNAL_CMD = true;
    const STATUS_OK       = 0; // 成功時のExit ステータス。失敗時は行番号のこと。

/* [Includes] =============================================================== */

    $path_dir_self = dirname(__FILE__) . DIRECTORY_SEPARATOR;

/* ------------------------------------------------------- [Common functions] */

    include_once($path_dir_self . 'functionsCommon.php.inc');

/* ---------------------------------------------------------------- [Classes] */

    include_once($path_dir_self . 'traitPropertyKeysForApi.php.inc');
    include_once($path_dir_self . 'classProperties.php.inc');

/* [Functions] ============================================================== */

/* ---------------------------------------------------------------------- [C] */

    function createStreamContext(Properties $properties)
    {
        $data_post = [
            'status'     => $properties->status,
            'visibility' => $properties->visibility,
        ];

        $spoiler_text = $properties->spoiler_text;

        if (! empty($spoiler_text)) {
            $data_post['spoiler_text'] = $spoiler_text;
        }

        $data_post      = http_build_query($data_post, "", "&");
        $name_useragent = NAME_USER_AGENT;

        $header = implode("\r\n", [
            'Content-Type: application/x-www-form-urlencoded',
            "Authorization: Bearer {$properties->access_token}",
            "User-Agent: {$name_useragent}",
        ]);

        $context = [
            'http' => [
                'method'  => $properties->method,
                'header'  => $header,
                'content' => $data_post,
            ],
        ];

        return stream_context_create($context);
    }

/* ---------------------------------------------------------------------- [E] */

    /**
     * 外部コマンド実行（exec関数のラッパー）.
     *
     * 許可された関数, 外部コマンド以外は実行させません。また本スクリプト・ファ
     * イルにある関数以外からの呼び出しも受け付けません。専用の呼び出し関数を作
     * 成してください。（`getAccessToken()`参照）
     *
     * @param  mixed  $command            外部コマンド名
     * @param  string $args (default: '') コマンドの引数
     * @return array                      処理結果。配列のフォーマットは以下
     *
     * - 戻り値の配列フォーマット:
     *   [
     *     'output'    => string. コマンド実行結果の全行（複数行出力される場合）
     *     'is_error'  => bool. コマンド実行が失敗したか（true=失敗）
     *     'status'    => int. コマンドからの実行結果ステータス（0=成功）
     *     'last_line' => string. コマンド実行後の最後の表示行
     *   ]
     */
    function execCmd($command, $args = '')
    {
        // 呼び出し元の関数の情報取得（トレース）
        $backtrace   = debug_backtrace();
        $caller      = getValue(1, $backtrace, array());
        $caller_name = getValue('function', $caller);

        // 同じファイル内で定義された関数からしか反応させない
        if (! isInSameFileCaller('execCmd', $backtrace, __FILE__)) {
            dieMsg('Invalid call. You can not call this function directly.', __LINE__);
        }

        // 許可された関数からの呼び出しにしか反応させない
        if (! isAvailableCmd($caller_name, IS_INTERNAL_CMD)) {
            dieMsg('Permission deny. Not an allowed function.', __LINE__);
        }

        // 許可された外部コマンド（シェルコマンド）しか実行させない
        if (! isAvailableCmd($command, IS_EXTERNAL_CMD)) {
            dieMsg('Permission deny. Not an allowed command to use.', __LINE__);
        }

        // 以下コメントアウト: 'ls -l -a' の場合 'ls "-l -a"' となるため
        //$args  = escapeshellarg($args);

        $args .= hasStdErrRedirect($args) ? '' : ' 2>&1'; // STDERRも取得

        $output     = [];
        $return_var = 0;
        $cmd        = $command . ' ' . $args;

        // 外部コマンド実行
        $last_line = exec($cmd, $output, $return_var);

        return [
            'output'    => implode(PHP_EOL, $output),
            'is_error'  => (0 !== $return_var),
            'status'    => $return_var,
            'last_line' => $last_line,
        ];
    }

/* ---------------------------------------------------------------------- [G] */

    function getAccessToken($name_service, $name_token, $default_token = '')
    {
        // ローカル開発用に第３引数をおうむ返し
        if (! empty($default_token)) {
            return $default_token;
        }

        // サーバーの `gettoken` シェルコマンドから現在の AccessToken 取得
        // 参照: https://github.com/Qithub-BOT/Qithub-ORG/issues/128
        $result = execCmd('gettoken', "{$name_service} {$name_token}");

        if (getValue('is_error', $result, IS_ERROR)) {
            $msg  = 'Error while requesting access token.' . PHP_EOL . PHP_EOL;
            $msg .= 'Returned message:' . PHP_EOL;

            dieMsg($msg . getValue('output', $result, 'error'), __LINE__);
        }

        return trim(getValue('last_line', $result, 'blank'));
    }

    function getPathDirParent()
    {
        // 呼び出し元のディレクトリ・パスを取得
        $result = getValue(0, debug_backtrace());

        return dirname(getValue('file', $result, __FILE__));
    }

    function getUrlApiToot(Properties $properties)
    {
        $schema   = $properties->schema;
        $host     = $properties->host;
        $endpoint = $properties->endpoint;

        return "{$schema}://{$host}{$endpoint}";
    }

    function getVisibilityDefault()
    {
        return DEFAULT_VISIBILITY;
    }

/* ---------------------------------------------------------------------- [H] */

    function hasStdErrRedirect($string)
    {
        $string = str_replace(' ', '', $string);

        return false !== strpos($string, '2>&1');
    }

/* ---------------------------------------------------------------------- [I] */

    function isAvailableCmd($string, $flag)
    {
        // 内部コマンドの実行許可ホワイトリスト
        if ($flag === IS_INTERNAL_CMD) {
            return isAvailableCmdInternal($string);
        }

        // 外部コマンドの実行許可ホワイトリスト
        return isAvailableCmdExternal($string);
    }

    function isAvailableCmdInternal($string)
    {
        // 内部コマンドの実行許可ホワイトリスト
        switch (true) {
            case ('getAccessToken' === $string):
                return true;
            default:
                return false;
        }
    }

    function isAvailableCmdExternal($string)
    {
        // 外部コマンドの実行許可ホワイトリスト
        switch (true) {
            case ('gettoken' === $string): // Qithubサーバ独自シェルコマンド
                return true;
            default:
                return false;
        }
    }

    function isHeaderResponseOK($response_header)
    {
        foreach ($response_header as $line) {
            if (false !== strpos(strtoupper($line), '200 OK')) {
                return true;
            }
        }

        return false;
    }

    function isInSameFileCaller($name_function, array $backtrace, $name_file)
    {
        $caller = $backtrace[0]; //呼び出し元の関数の情報

        $isFunctionSame = $caller['function'] === $name_function;
        $isFileSame     = $caller['file'] === $name_file;

        // 呼び出し元の関数と呼び出し先の関数が同じファイル内なら true
        return ($isFunctionSame) && ($isFileSame);
    }

    function isValidFormatToken($string)
    {
        return ctype_alnum($string) && (LEN_ACCESS_TOKEN === strlen($string));
    }

/* ---------------------------------------------------------------------- [R] */

    function requestApi(Properties $properties)
    {

        $access_token = $properties->access_token;

        if (empty($access_token)) {
            dieMsg('Error: No access token specified.', __LINE__);
        }

        $url     = getUrlApiToot($properties);
        $context = createStreamContext($properties);

        // トゥートの連投制限 (5分間で 1toot/sec 以上越えると一時制限されるため)
        sleep(SLEEPTIME_ON_TOOT);

        // トゥートの実行
        $result = file_get_contents($url, false, $context);

        if (! isHeaderResponseOK($http_response_header)) {
            $msg_error .= 'Error: Bad response from Mastodon server.' . PHP_EOL;
            $msg_error .= 'Response header:' . PHP_EOL;
            $msg_error .= print_r($http_response_header, true);

            dieMsg($msg_error, __LINE__); // Shouldn't we log here?
        }

        return json_decode($result, JSON_OBJECT_AS_ARRAY);
    }

/* ---------------------------------------------------------------------- [T] */

    /**
     * Qiitadon にトゥートする.
     *
     * @param  array  $toot_info  トゥートに関する情報（要素の詳細は以下参照）
     * @return string             トゥート結果の JSON レスポンス
     */
    function toot(array $settings)
    {
        $settings['schema']   = 'https';
        $settings['endpoint'] = '/api/v1/statuses';
        $settings['method']   = 'POST';

        $properties = new Properties($settings);

        if (empty(trim($properties->status . $properties->spoiler_text))) {
            dieMsg('Error: No toot message specified.', __LINE__);
        }

        return requestApi($properties);
    }

/* [Class] ================================================================== */


//End Of Namespace
}
