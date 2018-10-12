<?php
namespace Qithub\QiitaCache;

/* [Constants] ============================================================== */

// 内部環境定数
const FORMAT_THRESHOLD_FETCH = 'YmdHi'; // 201808302059 フォーマット
const FOTMAT_THRESHOLD_HOUR  = 'YmdH';  //
const REQUEST_LIMIT_PER_SEC  = 1;       // QiitaAPI は 1リクエスト/sec (60/hour)
const NUM_PAGES_TO_FETCH     = 2;       // 1リクエストで取得する記事数

define('TIME_NOW', time());
define('PATH_DIR_CURR', dirname(__FILE__));
