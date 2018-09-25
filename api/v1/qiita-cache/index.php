<?php
namespace Qithub\QiitaCache;

/**
 * このスクリプトはリクエストされた Qiita 記事を JSON で返します.
 */

require_once('../.lib/php/function_Common.php.inc');

/* [Settings] =============================================================== */

date_default_timezone_set(TIMEZONE); //TimeZone (Tokyo,Japan)

$settings = [
    'qiita' => [
        'schema'   => 'https',
        'host'     => 'qiita.com',
        'endpoint' => '/api/v2/items',
    ],
    'name_dir_cache' => '.cache', // キャッシュの保存先
    'path_dir_curr'  => PATH_DIR_CURR,
];

/* [Main] =================================================================== */

// ! has Qiita item ID in request query
    // Redirect to GitHub's README of the repo.
// ! is valid format of Qiita item ID
    // Redirect to GitHub's README of the repo.
// has update flag in request query
    // update flag = true
// file_exists <Qiita item ID>.json
    // ! update flag || Is item web page 404
        // Return JSON from cache
        // Exit(STATUS_OK)
// Fetch from Qiita API
// Save cache
// Return JSON from cache
// Exit(STATUS_OK)




