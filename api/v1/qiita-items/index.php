<?php
/**
 * このスクリプトはリクエストされた時間内に投稿された Qiita 記事を JSON で返します.
 *
 * Note:
 *   - バックスラッシュ付きの関数や定数などは、下記 require 先で定義されています.
 */

namespace Qithub\QiitaItems;

require_once('../.lib/php/tootlib.php.inc');
require_once('constants.php.inc');
require_once('functions.php.inc');

/* [Settings] =============================================================== */

date_default_timezone_set(TIMEZONE); //TimeZone (Tokyo,Japan)

// 初期設定と初期キャッシュ内容
$settings = [
    'qiita' => [
        'schema'   => 'https',
        'host'     => 'qiita.com',
        'endpoint' => '/api/v2/items',
    ],
    'qiitime' => [
        'schema'   => 'https',
        'host'     => 'qithub.gq',
        'endpoint' => '/api/v1/qiitime',
    ],
    'items_per_page' => NUM_PAGES_TO_FETCH, // 取得する記事数
    'name_dir_cache' => '.cache', // 設定やキャッシュの保存先
    'path_dir_curr'  => PATH_DIR_CURR,
    'name_file_data' => 'settings.php.inc',
    'threshold'      => [
        'date'   => 0,
        'minute' => 0,
    ],
    'tooted' => [], // トゥート済みのitem情報 key=itemID => value=itemの内容
];

/* [Main] =================================================================== */

// データ・ディレクトリの確認/作成
initializeDirectories($settings);

// キャッシュ・データの読み込み。ファイルがなければデフォルト利用
$settings = initializeCache($settings);

// 現在のThesholdを取得
$threshold_min_now  = getThreshold(FORMAT_THRESHOLD_MIN);
$threshold_date_now = getThreshold(FOTMAT_THRESHOLD_DATE);

// キャッシュと現在の Threshold（分） 比較
if ($threshold_min_now === getThresholdCacheMin($settings)) {
    // 時間内なのでキャッシュを返して終了
    $json = json_encode(\getValue('tooted', $settings));
    echoJson($json);

    exit(STATUS_OK);
}

// 日付が変わったら 'tooted' リストをクリア
if ($threshold_date_now !== getThresholdCacheDate($settings)) {
    $settings['tooted'] = [];
}

// アクセストークンの取得（許可Issue番号は関数内を参照）
$access_token = getAccessTokenQiitadon(IS_MODE_DEV);

// QiiTime の現在の TootID を取得（in_reply_to -> $id_toot）
$id_toot_reply     = getIdItemToReply($settings);
$threshold_qiitime = getThresholdQiiTime($settings);

// 新着Qiita記事を取得
$items_new = getItemsFromQiita($settings);

// トゥート済み一覧取得
$ids_tooted = array_keys(\getValue('tooted', $settings));

// 新着のループとトゥート済みとの比較
foreach ($items_new as $item_new) {
    $item_info      = getInfoItemQiita($item_new);
    $id_item_qiita  = \getValue('id_item', $item_info);
    $threshold_item = \getValue('threshold_hour', $item_info);

    // トゥート済みならスキップ
    if (in_array($id_item_qiita, $ids_tooted)) {
        continue;
    }

    // トゥート時間外ならスキップ
    if ($threshold_item !== $threshold_qiitime) {
        continue;
    }

    // QiiTime の最新トゥートに返信（新着をトゥートする）
    $visibility = (IS_MODE_DEV) ? 'unlisted' : 'public';

    $result = toot([
        'schema'         => 'https',
        'host'           => 'qiitadon.com',
        'visibility'     => $visibility,
        'access_token'   => $access_token,
        'status'         => createMsgToot($item_info),
        'in_reply_to_id' => $id_toot_reply,
    ]);

    // ゥート済み情報から記事の本文を削除（容量確保のため）
    unset($item_new['rendered_body']);
    unset($item_new['body']);

    // トゥート済み履歴に追加
    $settings['tooted'][$id_item_qiita] = $item_new;
}

// キャッシュ情報の更新
$settings['is_cache_default']    = false;
$settings['threshold']['date']   = $threshold_date_now;
$settings['threshold']['minute'] = $threshold_min_now;

// キャッシュの更新とトゥート済みを返してスクリプト終了
if (saveSettings($settings)) {
    $json = json_encode(\getValue('tooted', $settings));
    echoJson($json);

    exit(STATUS_OK);
}

dieMsg('Unknown Error.', __LINE__);
