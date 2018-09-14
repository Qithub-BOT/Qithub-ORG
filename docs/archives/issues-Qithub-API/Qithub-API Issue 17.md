これは  リポジトリの[ issue をアーカイブ]()したものです。

# セキュリティ：_scripts/下のスクリプトの直接実行

- 2017/10/03 11:08 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 相談

プラグインは標準入力経由でデータを受け取るとは言え、現状ではURLを直接叩くと起動してしまいます。（少なくともPHPファイル）

現状は特に問題ないと思いますが、プラグインを実行するのは本体スクリプトのみと、ホワイトリスト的に制限した方が良い気がします。

## 自分の案

プラグイン側で判断・遮断してくれれば良いのですが、リスクやプラグイン実装側の労力もあるので、以下の２つのいずれかはいかがでしょう？

1. `.htaccess`ファイルを設置し`_scripts`へのWEBアクセスをブロックする
1. 拡張子を付けない（WEBサーバーは認識できなくなるが、CLIからは実行可能（なハズ）
    - 例：`.php` → `.inc` ∴ `php ./path/to/plugin/sample.inc ${arg}`

----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。

----------------

### TL;DR（結論 2017/10/14 現在）
#### ディレクトリ・アクセスの仕様
`plugins/`（旧`scripts/`）および `systems/`など、CLI経由のAPIのみで呼び出す（直接URLで呼び出さない）方針のディレクトリは、`.htaccess` でディレクトリ・アクセスの制限をかける事とする。
```.htaccess
Options -ExecCGI
order deny,allow
deny from all
```

-----

## コメント

-----

##### 333813506

2017/10/03 11:25 by hidao80

.htaccess を `_scripts` 内に置く方法に賛成です。

次ような設定はどうでしょう？

>order deny,allow　… 拒否・一部許可 
>deny from all　… とりあえず全てを拒否 
>allow from keinos.com　… BOT 運用サーバからのアクセスのみ可

この方法なら拡張子そのままでアクセス制限ができると思います。

プラグイン命名規則を**決めて周知する必要がない**アイデアのつもりです。

-----

##### 333816927

2017/10/03 11:42 by hidao80

htaccess に次の行を追加するだけでも良さそうです。

```
Options -ExecCGI
```

-----

##### 333881571

2017/10/03 15:36 by KEINOS

### 検証結果
DEV環境で`/qithub_dev/_scripts/`に`.htaccess`を作成し以下を設定してみました。
```
Options -ExecCGI
```
https://blog.keinos.com/qithub_dev/_scripts/issue10/hello_world.php
**前：**「Argument is empty.」← スクリプト側で判断して表示しているエラー
**後：**「403 Forbidden」← 👌 

ただ https://blog.keinos.com/qithub_dev/_scripts/ にアクセスすると、設置してあるindex.html（ブランクファイル）が表示されてしまいます。

そのためのファイルなので当然の動作なのですが、このディレクトリはアクセス禁止であるため"403"を返したいところです。（クローラー対策含め）

以下のようにガチガチに設定しつつ本体スクリプトの動作も確認できました。これで行きますかね？
```
Options -ExecCGI
order deny,allow
deny from all
```



-----

##### 333974915

2017/10/03 20:52 by hidao80

👍 

アクセス禁止 + CGI 不可にしておけば、ドメイン詐称に耐えられると思います。

私の案ではドメイン詐称（できるかどうかわかりませんが、リモートから「keinos.com としてリファラをつける」）される脆弱性があるなー、と思ってました。

-----

##### 334036595

2017/10/04 02:53 by KEINOS

では、当面触っちゃいやんなディレクトリはこれでということで 👍 

-----

##### 336627429

2017/10/14 10:58 by KEINOS

### .htaccessが設定されていません
`systems/xxxxx`が叩けてしまっています。
https://blog.keinos.com/qithub/system/get-qiita-new-items/main.php

下記 'master' の `.htaccess` を見ると空なので本issueの仕様にあわせるべきだと思います。
https://github.com/Qithub-BOT/scripts/blob/master/system/.htaccess

ので、再オープンします。 👐  \ﾊﾟｶｯ/

-----

##### 336627685

2017/10/14 11:03 by KEINOS

TL;DRを追加しました。
