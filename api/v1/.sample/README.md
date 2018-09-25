# サンプル・スクリプト

"Hello world" するだけのサンプル・スクリプトです。
また、PHP のビルトイン Web サーバーを利用してローカルで実行確認もできます。

## 簡単な使い方

```shellsession
$ # clone 先の下記ディレクトリに移動
$ cd /path/to/git/cloned/dir/of/Qithub-ORG/api/v1/
$
$ # サンプルをコピーする
$ cp -r .sample myApp
$ cd myApp
$ ls
README.md	dev		index.php
$
$ # 自分の検証用 Mastodon アクセストークンをセットする（置換）
sed -i '' -e "s/::replacetoken::/<your access token>/g" index.php
$
$ # Webサーバーのランチャーに実行権限を与える
$ chmod +x dev
$ 
$ # Webサーバーとブラウザ起動
$ ./dev safari index.php
$
$ # Web サーバーとブラウザとブラウザが起動し`index.php`が実行され "Hello world" がトゥートされます。
$ # 以降は任意のエディタでスクリプトを修正し、ブラウザをリロードの繰り返で開発します。
$ # サーバーの停止は `control + c`
```


1. このディレクトリ（`.sample`）をコピーしてリネームします。
1. Qiitadon の「開発」メニューからテスト用のアクセストークンを取得します。
1. `index.php` の "access_token" の値に取得したアクセストークンを設定します。
1. ターミナルから、このファイルと同じディレクトリに移動します。
1. 同階層にある `dev` に実行権限を与えます。（`$ chmod +x dev`）
1. `$ ./dev safari index.php` を実行すると、Safari ブラウザと PHP のビルトイン Web サーバが起動し、`index.php` が実行されます。<br>（chrome,opera,firefox もインストールされていれば利用可能です。`$ ./dev` で利用可能なブラウザが確認できます）
1. 非公開（フォロワーのみ）で "hello" "world" のトゥートがされます。
1. 続けて上記トゥートに "hello" "qiitadon" が返信トゥートされます。

なお、Web サーバー経由での実行でなく CLI での動作を試す場合は `$ php index.php` を実行します。


## 注意：

-アクセス・トークンを記載したまま PR をしないでください。
