<?php
namespace Qithub\QiiTime;

/* [Constants] ============================================================== */

// 内部環境定数
const FORMAT_TIMESTAMP    = 'YmdH';  // 2018083020 フォーマット
const JSON_NO_OPTION      = 0;       // json_encode() のオプション初期化用
const KEY_TOKEN           = 'token'; //
const TIME_WAIT_TO_UNLOCK = 2;       // ロックファイルがある場合の待ち時間（秒）
const NAME_FILE_LOCK      = '.qiitime.lock'; //ロックファイル名

// フラグ
const TOOT_IS_NEW         = false;
const TOOT_IS_CACHE       = true;
const MODE_IS_DEVELOPMENT = true;
const MODE_IS_PRODUCTION  = false;

define('TIME_NOW', time());
