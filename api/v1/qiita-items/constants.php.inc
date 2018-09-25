<?php
namespace Qithub\QiitaItems;

/* [Constants] ============================================================== */

// 本番・開発切り替えフラグ
const IS_MODE_DEV = false; // false の場合は本番アクセストークンを利用

// IS_MODE_DEV = true 時の返信先トゥートID
const ID_TOOT_INREPLY_WHEN_DEV = '100778081113682805';

// 内部環境定数
const FORMAT_THRESHOLD_MIN     = 'YmdHi'; // 201809212010 フォーマット
const FOTMAT_THRESHOLD_QIITIME = 'YmdH';  // 20180921
const FOTMAT_THRESHOLD_DATE    = 'Ymd';   // 201809
const NUM_PAGES_TO_FETCH = 10;            // 1リクエストで取得する新着記事数
const LEN_ID_TOOT        = 18;            // Qiitadon のトゥートIDの長さ
const LEN_ID_ITEM_QIITA  = 20;            // Qiita記事IDの長さ
const TAG_SUFFIX_QIITA   = '_qiita';      // トゥートタグのsuffix

define('TIME_NOW', time());
define('PATH_DIR_CURR', dirname(__FILE__));

// 比較用フラグ
const RETURN_AS_ARRAY     = 0b00000010;
const RETURN_AS_OBJECT    = 0b00000100; // PHP object  = 0b00000100
const RETURN_AS_JSON      = 0b00001000; // JSON object = 0b00001100 になる
const JSON_DECODE_FAIL    = null;       // json_decode 失敗時は null
const JSON_ENCODE_FAIL    = false;      // json_encode 失敗時は false
const MODE_IS_DEVELOPMENT = true;
const MODE_IS_PRODUCTION  = false;
