<?php
/**
 * このスクリプトはサンプルスクリプトです.
 *
 * 使い方は README.md をご覧ください。
 */

namespace Qithub\NameOfYourApp;

include_once('../lib/php/tootlib.php.inc');

$result = toot([
    'schema'       => 'https',        //
    'host'         => 'qiitadon.com', //
    'visibility'   => 'unlisted',     // 公開レベル: public, unlisted, private
    'access_token' => 'XXXXX..XXX',   // Qiitadon のアクセストークン
    'spoiler_text' => 'hello',        // CW 警告文
    'status'       => 'world',        // トゥート本体
]);

print_r($result);
