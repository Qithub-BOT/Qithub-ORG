<?php
/**
 * このスクリプトはサンプルスクリプトです.
 *
 * 使い方は README.md をご覧ください。
 */

namespace Qithub\NameOfYourApp;

include_once('../.lib/php/tootlib.php.inc');

/**
 * 通常のトゥートの方法 （非掲載）.
 */
$result = toot([
    'schema'       => 'https',        //
    'host'         => 'qiitadon.com', //
    'visibility'   => 'unlisted',     // 公開レベル:public, unlisted, private, direct
    'access_token' => '::replacetoken::', // Qiitadon のアクセストークン
    'spoiler_text' => 'hello',        // CW 警告文
    'status'       => 'world',        // トゥート本体
]);

print_r($result);

/**
 * 連続投稿によるブロック防止.
 *
 * 5分間に1秒1アクセス以上あると、しばらくブロックされるため sleep(秒) させる
 */
sleep(1);

/**
 * 返信トゥートの場合.
 */
$id_to_reply = \getValue('id', $result); // 上記トゥートを返信先にする

$result = toot([
    'schema'         => 'https',        //
    'host'           => 'qiitadon.com', //
    'visibility'     => 'unlisted',     // 公開レベル: public, unlisted, private
    'access_token'   => '::replacetoken::', // Qiitadon のアクセストークン
    'spoiler_text'   => 'hello',        // CW 警告文
    'status'         => 'qiitadon',     // トゥート本体
    'in_reply_to_id' => $id_to_reply,   // 返信先のトゥートID
]);

print_r($result);
