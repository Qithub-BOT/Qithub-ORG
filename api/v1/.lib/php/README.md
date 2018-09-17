# PHP 共有ライブラリ

## `tootlib.php.inc`

Qiitadon でトゥートを行うためのライブラリです。インクルードして利用してください。

### 利用例

#### アクセス・トークンを指定する場合

自分のアカウントのアクセス・トークンでテストする場合に利用します。リポジトリにコミットする場合は、ハードコーディング（アクセス・トークンの埋め込み）をしないように注意ください。

```php
<?php

namespace Qithub\NameOfYourApp;

include_once('../lib/php/tootlib.php.inc');

$result = toot([
    'schema'       => 'https',        //
    'host'         => 'qiitadon.com', //
    'visibility'   => 'unlisted',     // 公開レベル: public, unlisted, private
    'access_token' => 'XXXXX..XXX',   // Qiitadon のアクセストークン
    'status'       => 'hello world',  // トゥート本体
]);

print_r($result);
```

#### サーバーからアクセストークンを取得する場合

サーバーの [`gettoken` シェルコマンド](https://github.com/Qithub-BOT/Qithub-ORG/wiki/%E3%82%A2%E3%82%AF%E3%82%BB%E3%82%B9%E3%83%88%E3%83%BC%E3%82%AF%E3%83%B3%E3%81%AB%E3%81%A4%E3%81%84%E3%81%A6)を利用してアクセス・トークンを取得します。

```
<?php

namespace Qithub\NameOfYourApp;

$result = toot([
    'schema'       => 'https',        //
    'host'         => 'qiitadon.com', //
    'visibility'   => 'unlisted',     //
    'name_service' => 'qiitadon',     // `gettoken` 第１引数
    'name_token'   => 'qithub-dev',   // `gettoken` 第２引数（開発用トークン利用）
    'status'       => 'hello world',  //
]);

print_r($result);
```

#### CW (Contents Warning) でトゥートする場合

```
<?php

namespace Qithub\NameOfYourApp;

$result = toot([
    'schema'       => 'https',        //
    'host'         => 'qiitadon.com', //
    'visibility'   => 'unlisted',     //
    'name_service' => 'qiitadon',     //
    'name_token'   => 'qithub-dev',   //
    'spoiler_text' => 'hello',        // CW 警告文
    'status'       => 'world',        // トゥート本体
]);

print_r($result);
```

## `dev_local_mac.php.inc`

PHP のビルトイン・ウェブサーバーを起動するスクリプトです。
スクリプトの動作

`qiitime` の `dev` ファイルを参考に、開発インクルードしてください。

### 使い方

```
cd /path/to/qi
