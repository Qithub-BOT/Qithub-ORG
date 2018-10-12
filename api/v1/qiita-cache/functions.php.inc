<?php
namespace Qithub\QiitaCache;

/* [Functions] ============================================================== */

/* ---------------------------------------------------------------------- [E] */

function echoJson($json_string, $options = \JSON_COMMON_OPTION)
{
    if (! \isValidJson($json_string)) {
        \dieMsg('Invalid JSON given.(JSON empty)', __LINE__);
    }

    $json_string = json_encode(json_decode($json_string), $options);
    $etag        = hash('md5', $json_string);

    if (! \isCli() && ! headers_sent()) {
        header('Content-type: application/json; charset=utf-8');
        header('Content-Length: '.strlen($json_string));
        header('Etag: ' . $etag);
    }

    echo $json_string;
}

function echoTagsAsCommonFormat($name_tags, $return = RETURN_AS_ARRAY)
{
    if (is_string($name_tags)) {
        $name_tags[] = $name_tags;
    }

    $tags_cache = getTagsFromFileAsArray(getPathFileTags());

    foreach ($name_tags as $name_tag) {
        $key      = strip_item_tag_minimum($name_tag);
        $name_tag = strtolower($name_tag);
        $name_tag = getTagAsCommonFormat($name_tag);
        if ($tags_cache[$key][$name_tag]) {
        }
    }

    if (RETURN_AS_JSON === $return) {
        return echoJson(json_encode($result));
    }

    return $result;
}

/* ---------------------------------------------------------------------- [G] */

function getNameTagGiven($return = RETURN_AS_ARRAY)
{
    $name_tag = hasTagsInArgs($return);
    if (! empty($name_tag)) {
        return $name_tag;
    }

    $name_tag = hasTagsInGet($return);
    if (! empty($name_tag)) {
        return $name_tag;
    }

    return false;
}

function getItemIdGiven()
{
    static $result;

    if (isset($result)) {
        return $result;
    }

    $id = hasItemIdInArgs(RETURN_AS_ID);
    if (! empty($id)) {
        $result = $id;
        return $result;
    }

    $id = hasItemIdInGet(RETURN_AS_ID);
    if (! empty($id)) {
        $result = $id;
        return $result;
    }

    return false;
}

function getJsonItemFromApi($id_item)
{
    if (empty($id_item)) {
        return false;
    }

    $url  = "https://qiita.com/api/v2/items/{$id_item}";
    $json = \getContentsFromUrl($url);

    if (null === json_decode($json)) {
        return false;
    }

    return $json;
}

function getPathFileCache($id_item, array $settings)
{
    $path_dir_cache  = getPathDirCache($settings);
    $name_file_cache = $id_item . ".json";
    $path_file_cache = $path_dir_cache . \DIR_SEP . $name_file_cache;

    return $path_file_cache;
}

function getPathDirCache(array $settings)
{
    $path_dir_curr  = \getValue('path_dir_curr', $settings);
    $name_dir_cache = \getValue('name_dir_cache', $settings);
    $path_dir_cache = $path_dir_curr . \DIR_SEP . $name_dir_cache;

    return $path_dir_cache;
}

function getPathFileTags(array $settings = [])
{
    if (empty($settings)) {
        global $settings;
    }

    $path_dir_curr  = \getValue('path_dir_curr', $settings, '.');
    $name_file_tags = \getValue('name_file_tags', $settings);
    $path_file_tags = $path_dir_curr . \DIR_SEP . $name_file_tags;

    return $path_file_tags;
}

function getTagAsCommonFormat(string $name_tag)
{
    static $list_tags;

    if (! isset($list_tags)) {
        // 出現タグ一覧の再利用に static 変数に代入
        $list_tags = getContentsFromFile(getPathFileTags());
    }

    $name_tag = strip_item_tag_minimum($name_tag);
    $key      = strtolower($name_tag);

    // 初出現の場合は同じタグを返す
    if (! isset($list_tags[$key])) {
        return $name_tag;
    }

    // 該当タグの出現リストから最大出現数のタグを返す
    $array = $list_tags[$key];
    $keys  = $array_keys($array, max($array));

    ksort($keys, SORT_NATURAL);

    return $keys[0];
}

function getUrlOfItem($id_item)
{
    if (empty($id_item)) {
        return false;
    }

    $url    = "https://qiita.com/items/{$id_item}";
    $status = getStatusCodeFromUrl($url);

    switch ($status) {
        case 404:
            return false;
        case 301: // リダイレクト
            return getUrlToRedirect($url);
        case 200:
            return $url;
        default:
            return false;
    }
}

/* ---------------------------------------------------------------------- [H] */

function hasItemIdGiven()
{
    return hasItemIdInArgs() || hasItemIdInGet();
}

function hasItemIdInArgs($return = RETURN_AS_BOOL)
{
    global $argv;

    if (! isset($argv)) {
        return false;
    }

    if (false === strpos(getValue(1, $argv), '-id')) {
        return false;
    }

    $id_item = getValue(2, $argv);

    if (! isValidFormatId('item_qiita', $id_item)) {
        return false;
    }

    return (RETURN_AS_BOOL === $return) ?: $id_item;
}

function hasItemIdInGet($return = RETURN_AS_BOOL)
{
    global $_GET;

    $id_item = getValue('id', $_GET);

    if (! isValidFormatId('item_qiita', $id_item)) {
        return false;
    }

    return (RETURN_AS_BOOL === $return) ?: $id_item;
}

function hasTagsInArgs($return = RETURN_AS_BOOL)
{
    global $argv;

    if (! isset($argv)) {
        return false;
    }

    if (false === strpos(getValue(1, $argv), '-tag')) {
        return false;
    }

    $tags = getValue(2, $argv);
    $tags = str_replace(' ', ',', trim($tags));

    if (RETURN_AS_STRING === $return) {
        return $tags;
    }

    if (RETURN_AS_ARRAY === $return) {
        if (empty($tags)) {
            return [];
        }

        return explode(',', $tags);
    }

    if (RETURN_AS_JSON === $return) {
        $array = (empty($tags)) ? [] : explode(',', $tags);
        return json_encode($array, JSON_COMMON_OPTION);
    }

    return ! empty($tags);
}

function hasTagsInGet($return = RETURN_AS_BOOL)
{
    global $_GET;

    $tags = getValue('tag', $_GET);
    $tags = str_replace(' ', ',', trim($tags));

    if (RETURN_AS_STRING === $return) {
        return $tags;
    }

    if (RETURN_AS_ARRAY === $return) {
        if (empty($tags)) {
            return [];
        }

        return explode(',', $tags);
    }

    if (RETURN_AS_JSON === $return) {
        $array = (empty($tags)) ? [] : explode(',', $tags);
        return json_encode($array, JSON_COMMON_OPTION);
    }

    return ! empty($tags);
}

/* ---------------------------------------------------------------------- [I] */

function initializeDirectories($settings)
{
    // キャッシュ・ディレクトリ確認/作成
    $path_dir_cache = getPathDirCache($settings);
    if (! is_dir($path_dir_cache) && ! mkdir($path_dir_cache, 0700)) {
        \dieMsg('Error: Can not create cache directory.', __LINE__);
    }
}

function isItem404($id_item)
{
    $url = getUrlOfItem($id_item);

    return false === $url;
}

function isUser404($id_user)
{
    $url = "https://qiita.com/{$id_user}";

    return 404 === \getStatusCodeFromUrl($url);
}

function isUserOfItem404($id_item)
{
    $url    = "https://qiita.com/api/v2/items/{$id_item}";
    $json   = \getContentsFromUrl($url);
    $object = json_decode($json);

    if (! isset($object->user->id)) {
        return null;
    }

    return isUser404($object->user->id);
}

/* ---------------------------------------------------------------------- [S] */

function strip_item_tag_minimum($name_tag)
{
    $name_tag = trim($name_tag);
    $name_tag = str_replace(' ', '_', $name_tag);
    $name_tag = str_replace('#', '', $name_tag);

    return $name_tag;
}
