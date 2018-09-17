<?php
// 汎用のため明示的にグローバル名前空間に設置
namespace {

/* [Constants] ============================================================== */

// 内部環境定数
    const LEN_ACCESS_TOKEN   = 64; // Mastodn アクセストークン長
    const NAME_USER_AGENT    = 'Qithub(bot) by Qithub ORG'; //これにAPP名を加えること
    const VER_PHP_REQUIRED   = '7.1.0'; // PHP v5.6 でも動くが v7.1の方が速いため
    const VER_HHVM_REQUIRED  = '5.6.0'; // Qithub.tk の PHP は HHVM なため
    const SLEEPTIME_ON_TOOT  = 1;      // API利用時の連投防止の待ち時間（秒）
    const TIMEZONE           = 'Asia/Tokyo'; // Our time. Japan.
    const DEFAULT_SCHEMA     = 'https';
    const DEFAULT_VISIBILITY = 'private';

// 既存定数のエイリアス
    const DIR_SEP = DIRECTORY_SEPARATOR;

// フラグ
    const IS_ERROR        = true;
    const IS_EXTERNAL_CMD = false;
    const IS_INTERNAL_CMD = true;
    const STATUS_OK       = 0; // 成功時のExit ステータス。失敗時は行番号のこと。

/* [Settings] =============================================================== */


/* [Trait] ================================================================== */

    trait PropertyValuesAllowed
    {
        // 設定可能なプロパティ値
        private $allow = [
            'schema'     => ['http','https'],
            'method'     => ['GET','POST','PATCH','PUT','DELETE'],
            'host'       => ['qiitadon.com','qiita.com','github.com'],
            'visibility' => ['public', 'unlisted', 'private'],
            'endpoint'   => [
                '/api/v1/statuses',
            ],
        ];
    }

    trait PropertyKeysForApi
    {
        // デフォルトのプロパティ値
        private $access_token = '';
        private $endpoint     = '';
        private $host         = '';
        private $method       = '';
        private $name_service = '';
        private $name_token   = '';
        private $schema       = DEFAULT_SCHEMA;
        private $spoiler_text = ''; // CW の警告文
        private $status       = ''; // トゥート本体
        private $visibility   = DEFAULT_VISIBILITY;

        // プロパティ値の汎用ゲッター
        public function __get($name_property)
        {
            return $this->$name_property;
        }
    }

    if (!isset($settings)) {
        // Default settings
        $settings = [
            'schema'     => 'https',
            'host'       => 'qiitadon.com',
            'method'     => 'POST',
            'endpoint'   => '/api/v1/statuses',
            'visibility' => 'public',  // タイプ: public, unlisted, private
        ];
    }

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

        echo trim($msg), " (status:${status_to_display})", PHP_EOL;

        exit($status);
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
        $result = execCmd('gettoken', "${name_service} ${name_token}");

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

        return "${schema}://${host}${endpoint}";
    }

    function getValue($key, array $array, $default = '')
    {
        if (empty($default)) {
            $default = false;
        }

        return (isset($array[$key])) ? $array[$key] : $default;
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

    function isCli()
    {
        return PHP_SAPI === 'cli' || empty($_SERVER['REMOTE_ADDR']);
    }

    function isCoveredVersionPHP()
    {
        $ver_current  = phpversion();
        $is_hhvm      = (false !== strpos(strtolower($ver_current), 'hhvm'));
        $ver_required = ($is_hhvm) ? VER_HHVM_REQUIRED : VER_PHP_REQUIRED;

        return version_compare($ver_current, $ver_required, '>');
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
        $settings['schama']   = 'https';
        $settings['endpoint'] = '/api/v1/statuses';
        $settings['method']   = 'POST';

        $properties = new Properties($settings);

        if (empty(trim($properties->status . $properties->spoiler_text))) {
            dieMsg('Error: No toot message specified.', __LINE__);
        }

        return requestApi($properties);
    }

/* [Class] ================================================================== */

    class Properties
    {
        use PropertyValuesAllowed;
        use PropertyKeysForApi;

        /* ------------------------------------------------------ [Construct] */

        public function __construct(array $settings)
        {
            $this->setProperties($settings);
        }

        /* -------------------------------------------------------------- [S] */

        private function setProperties(array $settings)
        {
            $this->access_token = $this->fetchAccessToken($settings);
            $this->endpoint     = $this->fetchEndpoint($settings);
            $this->host         = $this->fetchHost($settings);
            $this->method       = $this->fetchMethod($settings);
            $this->schema       = $this->fetchSchema($settings);
            $this->spoiler_text = $this->fetchSpoilerText($settings);
            $this->status       = $this->fetchStatus($settings);
            $this->visibility   = $this->fetchVisibility($settings);
        }

        /* -------------------------------------------------------------- [F] */

        private function fetchAccessToken(array $settings)
        {
            $string = getValue('access_token', $settings, '');

            $this->name_service = getValue('name_service', $settings, '');
            $this->name_token   = getValue('name_token', $settings, '');

            if (! empty($string)) {
                if (! isValidFormatToken($string)) {
                    dieMsg('Invalid access token format.', __LINE__);
                }

                return $string;
            }

            if (empty($this->name_service) || empty($this->name_token)) {
                $msg = 'No access token specified or bad `gettoken` request.';
                dieMsg($msg, __LINE__);
            }

            return getAccessToken($this->name_service, $this->name_token);
        }

        private function fetchEndpoint(array $settings)
        {
            $string    = getValue('endpoint', $settings);
            $endpoints = $this->allow['endpoint'];

            return in_array($string, $endpoints) ? $string : '';
        }

        private function fetchHost(array $settings)
        {
            $string = getValue('host', $settings);
            $hosts  = $this->allow['host'];

            return in_array($string, $hosts) ? $string : $this->host;
        }

        private function fetchMethod(array $settings)
        {
            $string  = strtoupper(getValue('method', $settings));
            $methods = $this->allow['method'];

            return in_array($string, $methods) ? $string : $this->method;
        }

        private function fetchSchema(array $settings)
        {
            $string  = strtolower(getValue('schema', $settings));
            $schemas = $this->allow['schema'];

            return in_array($string, $schemas) ? $string : $this->schema;
        }

        private function fetchSpoilerText(array $settings)
        {
            $string = getValue('spoiler_text', $settings, '');

            return trim($string);
        }

        private function fetchStatus(array $settings)
        {
            $string = getValue('status', $settings, '');

            return trim($string);
        }

        private function fetchVisibility(array $settings)
        {
            $string       = strtolower(getValue('visibility', $settings));
            $visibilities = $this->allow['visibility'];

            return in_array($string, $visibilities) ? $string : $this->visibility;
        }
    }

} //End Of Namespace
