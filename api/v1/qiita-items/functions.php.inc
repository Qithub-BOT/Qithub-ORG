<?php
namespace Qithub\QiitaItems;

/* [Functions] ============================================================== */

/* ---------------------------------------------------------------------- [C] */

function convertArrayIncludable(array $array)
{
    $string  = '<?php' . PHP_EOL . PHP_EOL;
    $string .= '$settings = ' . var_export($array, true) . ';';

    return $string . PHP_EOL;
}

function convertTagsAsMastodonStyle(array $tags)
{
    foreach ($tags as $tag) {
        $tag = getTagAsCommonName($tag);
        $tag = escapeTagForMastodon($tag);

        $result[] = $tag . TAG_SUFFIX_QIITA; // タグを php -> php_qiita
    }

    return $result;
}

function createMsgToot(array $item_info)
{
    $title = escapeTitleBrackets(\getValue('title', $item_info));
    $url   = \getValue('url', $item_info);
    $user  = \getValue('user', $item_info);
    $user  = (IS_MODE_DEV) ? "@ {$user}" : "@{$user}";

    $tags  = generateTagsToToot(\getValue('tags', $item_info));

    $msg = <<<EOT
🆕 新着Qiita記事をお知らせします。​:qiitan:

:qiita:​『{$title}』
{$url} by {$user} @​Qiita

{$tags}
EOT;

    return trim($msg) . PHP_EOL;
}

/* ---------------------------------------------------------------------- [E] */

function echoJson($string_json, $options = \NO_OPTIONS)
{
    if (JSON_ENCODE_FAIL === $string_json) {
        \dieMsg('Invalid JSON given.(JSON empty)', __LINE__);
    }

    if (\NO_OPTIONS === $options) {
        $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
    }

    $string_json= json_encode(json_decode($string_json), $options);

    if (JSON_ENCODE_FAIL === $string_json) {
        \dieMsg('Malformed JSON given: ' . $string_json, __LINE__);
    }

    $etag = hash('md5', $string_json);

    if (! \isCli() && ! headers_sent()) {
        header('Content-type: application/json; charset=utf-8');
        header('Content-Length: '.strlen($string_json));
        header('Etag: ' . $etag);
    }

    echo $string_json;

    return $etag;
}

function escapeTagForMastodon($string)
{
    if ('node.js' === strtolower($string)) {
        $string = 'NodeJs';
    }

    $string = str_replace(
        ['.', '-', ' ','/'],
        '_',
        $string
    );
    $string = str_replace(
        ['@', '#'],
        ['at', ''],
        $string
    );

    return $string;
}

function escapeTitleBrackets($string)
{
    $string = str_replace(
        ['『','』'],
        ['「','」'],
        $string
    );

    return $string;
}

/* ---------------------------------------------------------------------- [G] */

function generateTagsToToot(array $tags)
{
    $result = '';

    foreach ($tags as $tag) {
        $result .= "#{$tag} ";
    }

    return trim($result);
}

function generateUrl(array $array)
{
    $schema   = \getValue('schema', $array);
    $host     = \getValue('host', $array);
    $endpoint = \getValue('endpoint', $array);
    $per_page = (int) \getValue('items_per_page', $array);
    $query    = '';

    if (0 < $per_page && $per_page < 100) {
        $query = "?per_page={$per_page}";
    }

    return "{$schema}://{$host}{$endpoint}{$query}";
}

function getAccessTokenQiitadon($mode = IS_MODE_DEV)
{
    /**
     * アクセストークン許可 Issue: #153
     */
    $name_service = 'qiitadon';
    $name_token   = (MODE_IS_DEVELOPMENT === $mode) ? 'qithub-dev' : 'qithub';

    return \getAccessToken($name_service, $name_token);
}

function getColumns($key, array $array)
{
    return array_column($array, $key);
}

function getIdItemToReply(array $settings)
{
    $info_qiitime = getInfoTootOfQiiTime($settings);
    $id_replyto   = \getValue('id', $info_qiitime);

    if (MODE_IS_DEVELOPMENT === IS_MODE_DEV) {
        $id_replyto = ID_TOOT_INREPLY_WHEN_DEV; //dev用 Temp
    }

    return $id_replyto;
}

function getInfoItemQiita(array $array)
{
    $time_created   = strtotime(\getValue('created_at', $array));
    $threshold_item = getThreshold(FOTMAT_THRESHOLD_QIITIME, $time_created);

    return [
        'id_item' => \getValue('id', $array),
        'title'   => \getValue('title', $array),
        'url'     => \getValue('url', $array),
        'user'    => getUserFromInfoAsTag($array),
        'tags'    => getTagsFromInfo($array),
        'threshold_hour' => $threshold_item,
    ];
}

function getInfoTootOfQiiTime(array $settings)
{
    $url_info = \getValue('qiitime', $settings);

    if (empty($url_info)) {
        $settings = PHP_EOL . print_r($settings, true);
        dieMsg('No valid url info given for QiiTime.' . $settings, __LINE__);
    }

    $json = \getContentsFromUrl(generateUrl($url_info));

    return json_decode($json, JSON_OBJECT_AS_ARRAY);
}

function getItemsFromApi(array $settings)
{
    $url_api_items = getUrlApiItem($settings);

    return \getContentsFromUrl($url_api_items);
}

function getItemsFromQiita(array $settings, $return = RETURN_AS_ARRAY)
{
    $json  = getItemsFromApi($settings);
    $array = json_decode($json, JSON_OBJECT_AS_ARRAY);

    if (JSON_DECODE_FAIL === $array) {
        \dieMsg('Invalid JSON given from Qiita API.', __LINE__);
    }

    if (RETURN_AS_ARRAY === $return) {
        return $array;
    }

    if (RETURN_AS_OBJECT === $return) {
        return json_decode($json);
    }

    return $json;
}

function getPathDirCache(array $settings)
{
    $path_dir_curr  = \getValue('path_dir_curr', $settings, './');
    $name_dir_cache = \getValue('name_dir_cache', $settings, '.undefind_dir');

    return $path_dir_curr . \DIR_SEP . $name_dir_cache;
}

function getPathFileCache(array $settings)
{
    $name_file_data = \getValue('name_file_data', $settings, '.undefind_file');
    $path_dir_data  = getPathDirCache($settings);

    return $path_dir_data . \DIR_SEP . $name_file_data;
}

function getTagAsCommonName($string)
{
/*
    「golang -> GoLang」の変換を自動化。
    タグをキャッシュし、出現回数の多い記述方法を返す。

    $str_lower = strtolower($string)
    $tags = [
        $str_lower => [
            $string => 出現回数
        ],
    ];

    出現回数の一番多い $string を返す
*/
    return $string;
}

function getTagsFromInfo(array $array)
{
    $tags = getColumns('name', \getValue('tags', $array));
    $tags = convertTagsAsMastodonStyle($tags);

    return $tags;
}

function getThreshold($format_time, $time = null)
{
    if (null === $time) {
        $time = TIME_NOW;
    }

    return (int) date($format_time, $time);
}

function getThresholdCache(array $settings)
{
    return (array) \getValue('threshold', $settings);
}

function getThresholdCacheDate(array $settings)
{
    return (int) \getValue('date', getThresholdCache($settings));
}

function getThresholdCacheMin(array $settings)
{
    return (int) \getValue('minute', getThresholdCache($settings));
}

function getThresholdQiiTime(array $settings)
{
    $info_qiitime = getInfoTootOfQiiTime($settings);

    return (int) \getValue('threshold', $info_qiitime);
}

function getUserFromInfoAsTag(array $array)
{
    $id_user = \getValue('id', \getValue('user', $array));
    $id_user = escapeTagForMastodon($id_user);

    return $id_user;
}

function getUrlApiItem(array $settings)
{
    $url_info_items = \getValue('qiita', $settings);
    $url_api_items  = generateUrl($url_info_items);

    return $url_api_items;
}

/* ---------------------------------------------------------------------- [I] */

function initializeCache(array $settings)
{

    $path_file_cache   = getPathFileCache($settings);
    $is_file_available = true;

    // デフォルト値を保存
    if (! file_exists($path_file_cache)) {
        $settings['is_cache_default'] = true;
        $data_to_include   = convertArrayIncludable($settings);
        $is_file_available = \putContentsToFile($path_file_cache, $data_to_include);
    }

    // $settings の値を読み込む
    if ($is_file_available) {
        include($path_file_cache);
    }

    return $settings;
}

function initializeDirectories($settings)
{
    // キャッシュ・ディレクトリ確認/作成
    $path_dir_cache = getPathDirCache($settings);
    if (! is_dir($path_dir_cache) && ! mkdir($path_dir_cache, 0700)) {
        \dieMsg('Error: Can not create cache directory.', __LINE__);
    }
}

/* ---------------------------------------------------------------------- [S] */

function saveSettings(array $settings)
{
    $path_file_cache = getPathFileCache($settings);
    $data_to_include = convertArrayIncludable($settings);

    return \putContentsToFile($path_file_cache, $data_to_include);
}
