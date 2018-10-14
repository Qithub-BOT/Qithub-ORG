<?php
namespace Qithub\QiitaCache;

/**
 * このスクリプトはリクエストされた Qiita 記事のキャッシュを JSON で返します.
 */

require_once('../.lib/php/function_Common.php.inc');
require_once('constants.php.inc');
require_once('functions.php.inc');

/* [Settings] =============================================================== */

date_default_timezone_set(TIMEZONE); //TimeZone (Tokyo,Japan)

$settings = [
    'name_dir_cache' => '.cache', // キャッシュの保存先
    'name_file_tags' => '.tags',  // タグ一覧のファイル名
    'path_dir_curr'  => PATH_DIR_CURR,
    'url_redirect'   => 'https://github.com/Qithub-BOT/Qithub-ORG/tree/master/api/v1/qiita-cache/',
];

/* [Main] =================================================================== */

// 保存ディレクトリの確認／作成
initializeDirectories($settings);

/**
 * タグ名の表記検索と表示.
 * 指定されたタグ名のキャッシュされた記事で最も使われている表記表記方を返す。
 */

// タグ検索のタグ名をクエリもしくは引数から取得
$name_tags = getNameTagGiven(RETURN_AS_ARRAY);

// 指定されたタグの最も使われている表記方法（キャッシュ内に限る）を返して終了
if (! empty($name_tags)) {
    echoTagsAsCommonFormat($name_tags, RETURN_AS_JSON);
    exit(STATUS_OK);
}

/**
 * Qiita 記事のキャッシュ表示.
 * キャッシュがない場合やアップデート指示があった場合は Qiita API から取得・更新
 */

// Qiita 記事 ID（以下 "item ID"）をクエリもしくは引数から取得
$id_item = getItemIdGiven();

// Item ID のフォーマットが無効ならリポジトリ（GitHub）の README に転送
if (! isValidFormatId('item_qiita', $id_item)) {
    $url = \getValue('url_redirect', $settings);
    header("Location: {$url}", true, 302);
    \dieMsg('No valid item ID specified.', __LINE__);
}

// キャッシュのアップデート・フラグを取得
$do_update_cache = \getValue('update', $_GET) ? true : false;

// キャッシュの表示
$path_file_cache = getPathFileCache($id_item, $settings);
if (file_exists($path_file_cache)) {
    $json = file_get_contents($path_file_cache);

    // アップデート指示がない場合はキャッシュを表示
    if (! $do_update_cache) {
        echoJson($json);
        exit(STATUS_OK);
    }

    // アップデート指示があっても記事が削除済みの場合はキャッシュを表示
    if (isItem404($id_item)) {
        echoJson($json);
        exit(STATUS_OK);
    }
}

/* アップデート処理 */

// Qiita API からデータを取得
$json = getJsonItemFromApi($id_item);
if (! isValidJson($json)) {
    dieMsg('Invalid JSON returned from Qiita API.', __LINE__);
}

// キャッシュの保存（全体）
if (! putContentsToFile($path_file_cache, $json)) {
    dieMsg('Fail to save cache file.', __LINE__);
}

// キャッシュの保存（タグ）
if (! putTagsToFile(getPathFileTags(), $json)) {
    dieMsg('Fail to save tags to file.', __LINE__);
}

// キャッシュを表示して終了
echoJson($json);
exit(STATUS_OK);
