これは  リポジトリの[ issue をアーカイブ]()したものです。

# プラグイン： `info.json` ファイルのフォーマット（記述の仕様）

- 2017/12/22 07:10 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 提案

> `info.json` に記載できる内容と、そのフォーマットについて決めたい。

現在（2017/12/21 ）issue #84 により、各プラグインの同階層に `info.json` ファイルが設置されていた場合、Qithub 本体スクリプト（`Qithub-BOT/scripts/index.php`）がプラグインの諸情報を拾えるようにすることになりました。

その際の記述できる項目や書き方の仕様をこの issue で議論し TL;DR にまとめて行きたいと思います。

なお、**これらの設置や各項目は必須ではなく努力目標**で、「記述があれば本体側が拾えるよ」というものです。

----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。
- 関連issue #84 プラグイン：help とバージョンの確認について

----------------

## TL;DR（進捗 2018/05/24 現在）

- プラグインから Qithub コマンドに名称変更
- [Qithub コマンド専用のリポジトリ](https://github.com/Qithub-BOT/Qithub-CMD/)を設置
- 現在の最新仕様は [Qithub-CMDリポジトリの Wiki](https://github.com/Qithub-BOT/Qithub-CMD/wiki)で確認。
- 仕様に関する相談はメインリポジトリ[Qithub-ORG の issue](https://github.com/Qithub-BOT/Qithub-ORG/issues) に統一

### サンプル（全部盛り状態）

```
{
    "version": "1.0.0b",
    "help": "Usage: @qithub:draw-an-oracle [options]\nおみくじをひけます",
    "default": {
        "extension": "php"
    },
    "require": {
        "access_token": [
            "mastodon_qithub",
        ],
        "plugins": [
            {
                "name": "roll-dice",
                "args": "2d6",
                "callback_key": "roll-dice1"
            },
            {
                "name": "roll-dice",
                "args": "2d6",
                "callback_key": "roll-dice2"
            },
            {
                "plugin_name": "another-plugin",
                "plugin_args": "another argument",
                "callback_key": "another-plugin1"
            }
        ]
    }
}
```

- [ジェネレーター @ paiza.IO](https://paiza.io/projects/g_MjO3MIFnTc_wfZIJ6Rcw)

### 第１階層

コマンドの第１引数が `--[キー名]` で始まった場合、下記項目の値が返される。配列の場合は展開して返される。

| ID | キー名 | 型 | 概要 | 備考 |
| :--: | :--- | :--: | :--- | :--- |
| 1   | `version` | string | プラグインのバージョン | |
| 2  | `help`      | string  | プラグインのヘルプ | 使い方。引数のフォーマットなど |
| 3  | `default`  | array  | プラグインの各種デフォルト値 | 内容は下記 `default` を参照 |
| 4  | `require`  | array  | プラグインが必要な特殊引数の指定　| 内容は下記 `require` を参照 |
| 5  | etc | string or array | その他のプラグイン固有で `:[プラグイン名] --[キー名]` で渡したいデータ | `help`プラグインの `:help --extensions` や `:help --plugins` など |

### 第２階層

#### `default`

| ID | キー名 | 型 | 概要 | 備考 |
| :--: | :--- | :--: | :--- | :--- |
| 1   | `extension` | string | プラグインのデフォルト実行言語 | 複数プログラム言語の`main`スクリプトがある場合、デフォルト言語のドットなしの拡張子を小文字で記載。例）`php` |

#### `require`

| ID | キー名 | 型 | 概要 | 備考 |
| :--: | :--- | :--: | :--- | :--- |
| 1   | `access_token` | array | アクセストークンが必要な場合に記載 | 値は下記 `access_token_request_name` を参照 |
|21   | `plugins` | array | 他のプラグインの実行結果が必要な場合に記載 | 値は下記 `request_plugin` を参照 |

### 第３階層

#### `request_plugin`

| ID | キー名 | 型 | 概要 | 備考 |
| :--: | :--- | :--: | :--- | :--- |
| 1   | `name` | string | 実行したいプラグイン名 |  |
| 2   | `args` | string | プラグインに渡す引数 |  |
| 3   | `callback_key` | string | 実行結果を受け取るときのキー名 |  |

### 設定値一覧

#### `access_token_request_name`

| ID | 値 | 型 | 概要 | 備考 |
| :--: | :--- | :--: | :--- | :--- |
| 1 | `qiita_qithub` | string | Qiita API を利用するのに必要なアクセストークン | [`qithub.conf.json`](https://github.com/Qithub-BOT/scripts/blob/master/_samples/qithub.conf.json.sample)に記載されている `qiita_api` の `access_token` の値 |
| 2 | `qiita_user` | string | 予約語 | 現在は何もしない |
| 3 | `mastodon_qithub` | string | Mastodon API を利用するのに必要なアクセストークン | `qi thub.conf.json`に記載されている `mastodon_api` の `access_token` の値  |
| 4 | `mastodon_user` | string | 予約語 | 現在は何もしない |

  

-----

## コメント

-----

##### 353544246

2017/12/22 08:01 by hidao80

LGTM👍

ひとつ気になることが。**有効な extension のリストをシステム側に持たせると良い**と思います。

運用するサーバごとにアナウンスが必要なので、Qithub-BOT/scripts で管理されるソース or 文章に埋めることはできませんよね。

Git 上ではブランクにしておき、各々の実行環境(=clone 先)でコードを改変し `@qithub:help` に吐かせるようにしたらどうでしょうか。

-----

##### 353545784

2017/12/22 08:11 by KEINOS

> **有効な extension のリストをシステム側に持たせると良い**と思います。

👍 確かに、現在は [**今の設定をハードコードしている**](https://github.com/Qithub-BOT/scripts/blob/master/index.php#L40-L46)ので、どこぞの `conf` に記載する方向にした方がいいですね。  🙆‍♂️ 対応し 💪 

### ヘルプ・プラグインの仕様

`@qithub:help` でヘルプの概要、`@qithub:help extension` で拡張子一覧の方向でいいですか？




-----

##### 353546265

2017/12/22 08:14 by KEINOS

違った。`@qithub:help --help`がヘルプの概要で、`@qithub:help` は Qithub のコマンドの使い方が表示されるべきですね。


-----

##### 353546963

2017/12/22 08:19 by KEINOS

###  コマンドの進捗
#### `@qithub:help` の動作
https://blog.keinos.com/qithub_dev/?plugin_name=help&args=

#### `@qithub:help --help` の動作
https://blog.keinos.com/qithub_dev/?plugin_name=help&args=--help

#### `@qithub:help extension` の動作
https://blog.keinos.com/qithub_dev/?plugin_name=help&args=extension


-----

##### 353553903

2017/12/22 09:00 by hidao80

LGTM👍

### 相談

`@qithub:help extention` とするのならば、`@qithub:help --help` と同じ出力をする `@qithub:help help` も有効にした方が良い？

-----

##### 353562374

2017/12/22 09:47 by KEINOS

> `@qithub:help extension` とするのならば、`@qithub:help --help` と同じ出力をする `@qithub:help help` も有効にした方が良い？

`:help extension` は`help`プラグイン内で "extension" を引数に処理するものですが、`:help --help`は他プラグイン同様`info.json`に記載する内容なので、`:help help`を有効にする場合は同じ項目を２箇所に記載しないといけなくなります。

が、「プラグインは自プラグイン・ディレクトリ外へのアクセスは不可」というルールから、そもそも
 `help`プラグインは "extension" 一覧をどのように取得するのか**新たな課題がある**ことに気づきました。

`:help` コマンドのみ特例動作でもいいのですが、できれば同じプラグインの仕様で動かしたいです。

### 確認

確認ですが、`@qithub:help roll-dice` が `@qithub:roll-dice --help` と同じという認識でしょうか？

現在は `@qithub:help roll-dice` とした場合、`help`のオプションにはないため`@qithub:roll-dice --help`を試してどうかといった表示をするようになっています。

https://blog.keinos.com/qithub_dev/?plugin_name=help&args=roll-dice

### "extension" の取得方法 提案

> `info.json` の "require" に "extensions" を指定すると、プラグイン呼び出し時に拡張子一覧を渡しながら呼び出される。

```info.json
{
    "version": "2017-12-22.1644",
    "help": "Usage: @qithub:help [options]\n  [options]\n  --help           This help\n  --version        Version number",
    "default": {
        "extension": "php"
    },
    "require": {
        "access_token": "",
        "info": {
            "server": [
                "extension"
            ]
        }
    }
}
```

-----

##### 353602061

2017/12/22 13:50 by hidao80

>確認ですが、@qithub:help roll-dice が @qithub:roll-dice --help と同じという認識でしょうか？

確認してくださってありがとうございます🙇‍♂️。当方**圧倒的勘違い**をしておりました。

`@qithub:help extension`を`@qithub:help --plugins`と勘違いしていました。

冒頭への回答としては、「`@qithub:help roll-dice` と `@qithub:roll-dice --help` は同じ」という認識でしたが、今現在は **`@qithub:help {プラグイン名}`というコマンドは実行させない** というのが私の結論です。

前述の`@qithub:help extension` は**BOT が実行可能な拡張子のリストを表示する**ものであって、**動作可能なプラグインのリストではない**のが正しい認識ですよね？

であれば、`help`プラグインの`info.json`に

```diff
{
    "version": "2017-12-22.1644",
    "help": "Usage: @qithub:help [options]\n  [options]\n  --help           This help\n  --version        Version number",
    "default": {
        "extension": "php"
    },
+    "extension": [
+        ".php",
+        ".rb",
+        ".py",
+        ".go"
+    ],
    "require": {
        "access_token": "",
    }
}
```

出力例

```
@qithub:help --extension

.php
.rb
.py
.go
```

とすれば良いと思います。

### プラグインのリスト取得方法

ホワイトリスト式で良いと考えます。**管理用/ベータリリース用に隠しコマンドを仕込めるからです**😎

```diff
{
    "version": "2017-12-22.1644",
    "help": "Usage: @qithub:help [options]\n  [options]\n  --help           This help\n  --version        Version number",
    "default": {
        "extension": "php"
    },
+    "plugins": [
+        "help",
+        "roll-dice"
+    ],
    "require": {
        "access_token": "",
    }
}
```

出力例

```
@qithub:help --plugins

help
roll-dice
```

プラグイン追加の度に手入れが必要なのが面倒といえば面倒ですが、大変 KISS だと思われます。また、**プラグイン間のスコープにも干渉しません。**

ご意見お待ちしており💪

-----

##### 353621779

2017/12/22 15:40 by KEINOS

> 前述の@qithub:help extension はBOT が**実行可能な拡張子のリストを表示する**ものであって、**動作可能なプラグインのリストではない**のが正しい認識ですよね？

はい。"extension" は実行可能なプログラムの拡張子なので、確かに "plugins" も必要かと良いと思います。

1. `help`プラグインが "extension" （実行可能な拡張子）一覧をどのように取得するのか
2. `help`プラグインが "plugins" （実行可能なプラグイン）一覧をどのように取得するのか

上記２点に関しては、当面「`help`プラグイン（の`info.json`）にハードコーディングする」ことで暫定処置して、一旦先に進むことにしましょう！ 👉 TL;DR 済

ペンディングとなる事案として「運用するサーバごとに`help`プラグインの`info.json`を書き換えないといけない」つまり「運用するサーバごとにアナウンスが必要」な問題は依然として残ります。

しかし、今は他のサーバーで動かす人もいないと思いますし、**アクセストークン以外の情報も必要になるプラグイン（旧 `system/`のスクリプト）も出てくる**ので、`system/` を `plugins/` に統合するフェーズで再議論しましょう。

滞りなく `system/` が `plugins/` に統合されれば、この issue は close で！

-----

##### 353698155

2017/12/23 01:15 by hidao80

👍

-----

##### 355929756

2018/01/08 10:26 by KEINOS

### `info.json` に `require` が必要かも（プラグインから他のプラグイン呼び出し）

`system/` と `plugins/` のスクリプト統合にあたって、toot 済みかのデータを保持する `data-io` や、実際に toot するためにアクセストークンが必要など、プラグインによっては[本体スクリプト側で保持している情報が必要なもの](https://github.com/Qithub-BOT/scripts/tree/master/system)があります。

つまり、**プラグインから他のプラグインの結果を取得する仕組み**が必要と思われます。

そこで、`info.json` の第一階層の `require` に以下の項目をつけて、プラグインが呼び出される際に事前に他のプラグインの実行結果をリクエストする `callback` 的な仕組みを導入してはどうでしょう？

```diff
{
    "require": {
+        "plugins": [
+            {
+                "name": "<PLUGIN_NAME>",
+                "args": "<PLUGIN_ARGS>",
+                "callback_key": "<CALLBACK_KEY>"
+            },
+        ]
    },
}
```

上記のように `plugins` 項目があった場合、プラグインが呼び出された際の引数（Qithubエンコードされたデータの中）に `<CALLBACK_KEY>=<プラグインの実行結果>` も同時に渡されるイメージです。

例えば、`roll-dice` プラグインを２回振って使って得た数値を何かしらトゥートするプラグインがあった場合には `info.json` は以下のような内容になります。

```info.json
{
    "version": "0.0.1a",
    "help": "HERE HELP TEXT",
    "default": {
        "extension": "php"
    },
    "require": {
        "access_token": [
            "mastodon_srv",
        ],
        "plugins": [
            {
                "name": "roll-dice",
                "args": "2d6",
                "callback_key": "roll-dice1"
            },
            {
                "name": "roll-dice",
                "args": "2d6",
                "callback_key": "roll-dice2"
            },
            {
                "plugin_name": "another-plugin",
                "plugin_args": "another argument",
                "callback_key": "another-plugin1"
            }
        ]
    },
}
```

上記の記載があるプラグイン `sample1` の場合、プラグインが受け取る Qithub エンコードのデータ（JSONの）内容は以下。

```result.json
{
    "name_plugin" : "sample1",
    "args" : "???????",
    "callback": {
        "access_token": [
            {
                "name": "mastodon_srv",
                "fqdn": "qiitadon.com",
                "value": "XXXXXXXXXXXXXXXXXXXX",
                "status": "OK",
                "status_msg": ""
            },
        ],
        "plugins": [
            {
                "name": "roll-dice1",
                "value": "13",
                "status": "OK",
                "status_msg": ""
            },
            {
                "name": "roll-dice2",
                "value": "18",
                "status": "OK",
                "status_msg": ""
            },
            {
                "name": "another-plugin1",
                "value": "",
                "status": "NG",
                "status_msg": "Invalid argument. Second arg must be numeric."
            }
        ],
    }
}
```

-----

##### 355946710

2018/01/08 11:50 by hidao80

callback システムおよびフォーマット 👍 。

`system/` に対応するプラグインから `plugins/` が呼べるということは、その逆もできないと統合できないですものね。

### 疑問（要調査）

「プラグインからプラグインが呼べる」ということは再帰が可能になりますよね？ 再帰呼び出しされたときは、サーバリソースを使いつくす or プロセスの寿命限界まで動作することになるのでしょうか？ 🤔 
PHP はデフォルトで 30 秒後にプロセスを強制終了させますが、**ほかの言語はサーバをダウンさせることが可能なのではないか**と懸念しています。

Python、Ruby、Golang、Perl などは**危険が危ない**と考えます。言語仕様的に**再帰セーフ**になっているような気もします。
  

-----

##### 355963022

2018/01/08 13:14 by KEINOS

### 再帰問題

> 「プラグインからプラグインが呼べる」ということは再帰が可能になりますよね？ 再帰呼び出しされたときは、サーバリソースを使いつくす or プロセスの寿命限界まで動作することになるのでしょうか？ 🤔

確かに！ 😨   現在のままだと**再帰呼び出しは可能**ですね。自身の呼び出しは考えていませんでした。おそらくプロセスが複数できるタイプの再帰呼び出しになってしまうと思います。

自身の呼び出しを NG にするにしても、呼び出し先から呼び出しを食らうとピンポンが始まるので、結果同じだし。hop 数を管理する仕組みも面倒だしなぁ。 🤔 

ただ、**本体スクリプトは PHP なので、それを逆手に取っては**どうでしょう？

プラグイン間は必ず本体スクリプトを介してデータの送受をするので、5秒とか**デフォルトの処理時間を短めに設定して再帰を抜けられない処理は捨てる**というpaiza.IO 的な仕様にして、長時間処理がかかるプラグイン処理は NG みたいな。


  

-----

##### 355975265

2018/01/08 14:06 by hidao80

### 対案 A

> `timeout 1 sleep 3`
>  sleep 3`が途中でkillされて、1秒で処理が完了する。timeoutした場合の返り値は124

参考：[timeoutをシェルスクリプトで実現する by @yohm in Qiita](https://qiita.com/yohm/items/db6aea3cbc71ab2d55bd#_reference-6b5f93f0bcb406a498fb)

に詳しいですが、`scripts/includes/functions.php.inc` の [``$log = `$command > /dev/null &`;``](https://github.com/Qithub-BOT/scripts/blob/736dd2bbce08276b96d52d5d1979a8fce1ec7708/includes/functions.php.inc#L98) や [``$result = `$command`;``](https://github.com/Qithub-BOT/scripts/blob/736dd2bbce08276b96d52d5d1979a8fce1ec7708/includes/functions.php.inc#L106) に上記引用を細工すれば、**再帰も含めたコマンド全体の寿命を Qithub でコントロールできるのではないか**と考えています。 :thinking:

応用すれば、あらゆる対応言語でコマンドタイムアウトが設定できるかもしれません。 :smile:
  
追記：
これだと親プロセスから順番に死んでいきますね。いいんだか悪いんだか。「俺の屍を越えてゆけ！ BOT は値を返さないけどな！」

-----

##### 355990375

2018/01/08 15:03 by KEINOS

>> `timeout 1 sleep 3`
>> sleep 3`が途中でkillされて、1秒で処理が完了する。timeoutした場合の返り値は124

なるほど、これはアイデアですね！まさに**短命の呪い**ってやつですな。 😄 

新サーバーの方は `coreutils` が入っているっぽいので、`timeout`コマンド使えそうですね。工夫しだいで実現できそう！

```
[root@kusanagi ~]$ ls --version
ls (GNU coreutils) 8.22
Copyright (C) 2013 Free Software Foundation, Inc.
ライセンス GPLv3+: GNU GPL version 3 or later <http://gnu.org/licenses/gpl.html>.
This is free software: you are free to change and redistribute it.
There is NO WARRANTY, to the extent permitted by law.

作者 Richard M. Stallman および David MacKenzie。
```

### 中間まとめ

- callback システムおよびフォーマット 👉 TL;DR
- 再帰処理の対策
    - `timeout`コマンドを利用する（[参考](https://github.com/Qithub-BOT/scripts/issues/86#issuecomment-355975265)）
    - サーバーの引越しがおわったら、タイムアウトの実装をはじめる
    - その際、まずはタイムアウトを定数で持たせておいて、ブラグイン側で延長指定できるかは、後から考える



-----

##### 356182158

2018/01/09 05:11 by KEINOS

#### `timeout()` は使えそう

新サーバーで下記 paiza.IO のテストと同じことをテストしてみましたが、想定通りの動きでした。
https://paiza.io/projects/JbuWtns0O0W445TP48oZhg

`timeout` 👍 


-----

##### 360001793

2018/01/24 02:37 by KEINOS

## 相談：プラグインの戻り値の型

プラグインが別のプラグイン呼び出しができるようになったことで、プラグインの処理結果の戻り値の型（Qithub**デコード**を行った後の`value`値の型）をどう伝えるか悩んでいます。

というのも、`roll-dice` は戻り値がサイコロを振った回数ぶんの結果と合計値の配列、`hello-world`は文字列、そして `qiita-items-new` などは配列です。

### パティーン案

1. プラグインのソースを読んで開発者が判断する
2. プラグインの戻り値（Qithubエンコード）内に `content_type` キーを設け `text/json`, `text/text` などの値入りで返す [^1]
3. プラグインの `info.json` 内に `Content-Type` 項目を設ける

「`value`の値も配列で統一」という仕様にしたとしても、(1) と同じことなので、悩んでいます。

[^1] :`result`,`value`,`content_type`の３つの要素のJSON配列をQithubエンコードする

-----

##### 366415895

2018/02/17 04:32 by hidao80

## プラグインの戻り値の型：B案

パティーン 1. に 👍 

すべてのプラグインは単体ではQiitadonにテキストとして出力されることが念頭に置かれていると考えますので、**標準出力のように1本のStringとして返す**のが良いのではないでしょうか。

パースするのは呼び出し側プラグインの責任で、入力となる既存プラグインの出力仕様は呼び出し側プラグインの開発者がハックしたら良いと考えます。Unixのコマンドラインにおけるパイプラインのイメージです。
プラグインの入出力仕様までガチガチに決めると、プラグイン開発を気楽に楽めなくなるような気がします。 🤔 

-----

##### 366417929

2018/02/17 05:18 by KEINOS

> **標準出力のように1本のStringとして返す**のが良いのではないでしょうか

つまり、プラグイン A が他のプラグイン B の実行結果を取得したい場合、

1. 本体スクリプト（index.php）はプラグイン A の info.json の記述を読み込む
2. プラグイン B のリクエスト（プラグイン引数）の記述があれば、その値をプラグイン B に渡す
3. B がヒヒリ出してきた Qithub エンコードの１本 String をそのまま元プラグイン A に渡す
4. プラグイン A の結果を本体スクリプトが受け取る

だけで、Qithub デコード＆内容に関してはノータッチという流れですね？

それがシンプルでいいかもですね。プラグインの仕様がわかりづらければ、追記してプルリクするというのが正しい。


-----

##### 391566414

2018/05/24 02:21 by KEINOS

プラグインが Qithub コマンドに名前変更され、専用リポジトリが設置されたことにともない、`info.json` のフォーマットに関する仕様も [Qithub コマンドのリポジトリの Wiki](https://github.com/Qithub-BOT/Qithub-CMD/wiki) に統合したいと思います。

問題なければ close お願いし 💪 

