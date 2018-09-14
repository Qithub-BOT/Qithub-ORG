これは  リポジトリの[ issue をアーカイブ]()したものです。

# テスト（PHPUnit）とドキュメント（PHPDoc）のディレクトリ

- 2018/01/01 18:22 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

明けましておめでとうございます。本年初の issue 作成でございます。今年もよろしくお願いし 💪 

## 提案

1. 関数やプラグインなど、テストを導入したい（`/test/`ディレクトリの作成）
2. `/index.php`や`functions.php.inc`などから自動ドキュメント作成したい（`/docs/`ディレクトリの作成）

## 自分の案

### テストの導入

ちまたには `PHPUnit` なる自動テストのフレームワークがあるそうです。

まだよく理解していないのですが、`引数` と `想定される結果` を記載したファイルを用意して、テスト・クラスを書けばテストしてくれるというしろものらしいです。

ゆくゆくは `call_plugin()` をベースにプラグインのテストもできるようにしたいのですが、まずは `/index.php` の本体スクリプトが使う関数（やラッパー）のテストができるようにしてみたいです。

### ドキュメントの導入

PHPDocumentor で作成したドキュメントの設置場所が欲しいです。

PHPDocumentor ：ソースコードに PHPDoc 記法で記入しておくと、関数やクラスなどのドキュメントを作成してくれるやつ。

以下のリンクは、私の macOS に [`phpDocumentor.phar`](http://docs.phpdoc.org/getting-started/installing.html#phar) をダウンロードして `php ~/phpDocumentor.phar -f /path/to/functions.php.inc -f /path/to/constants.php.inc` した結果です。

https://blog.keinos.com/qithub_dev/includes/manual/

## TL;DR（進捗 2018/01/08 現在）

- 審議中 ( ´・ω) (´・ω・) (・ω・｀) (ω・｀ )

----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。

  

-----

## コメント

-----

##### 355912351

2018/01/08 09:02 by KEINOS

### Travis CI 

新サーバーに Jenkins 入れようと思ったのですが、 Side CI 同様に OSS なら無料っぽい Travis CI でもいいんじゃないかと思ってきました。

- https://www.slideshare.net/yandod/20140628-travis


-----

##### 355924004

2018/01/08 10:00 by hidao80

### フォルダ新設の件 (/docs、/test ) 

おおー、いいですね！ 自動テスト、自動ドキュメント生成興味あります！ やって見ましょう！👍😁

### travis-CI

travis-CI と言えば、現在私 Qithub-BOT/items の textlint (日本語構文チェックプログラム) を運用しているサービスです！

私は問題なく移行できると思うので、使って見ましょう！
  

-----

##### 391568153

2018/05/24 02:32 by KEINOS

本リポジトリ（Qithub-API）の issue 整理でクロールしています。

ちょいちょい、各々のリポジトリで CI と UnitTest（PHPUnit）を試しているので、この issue も close していいのではないかと思いますが、ノープロでしたらおてすきに close お願いし 💪 

