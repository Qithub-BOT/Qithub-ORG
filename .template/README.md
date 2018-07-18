# セットアップ・マニュアル

以下は、Qithub サークルのお遊びサーバー（ https://qithub.tk/ ）の再インストール時の覚書です。

## 基本仕様

https://qithub.tk/ のサイトは、[Qithub-ORG のリポジトリ](https://github.com/Qithub-BOT/Qithub-ORG)から clone されて動作しています。

GitHub 側でマージが行われると、GitHub からの Webhook により [Origin のリポジトリ](https://github.com/Qithub-BOT/Qithub-ORG)を `$ git fetch` し、Web サーバは `origin` と同じ状態に保たれます。（データや設定ファイルは除く）

## サーバ側の初回設定

以下はサーバを新しく立ち上げる（再インストールする）場合の初回手順です。Web サーバ（httpd）と同じユーザ、もしくはグループで作業してください。この作業は１度きりの作業です。

1. 'DocumentRoot'（Nginxの場合）と同階層に、クローナー・スクリプト（'.templates/clone_repo.php'）を設置／コピーしてください。
1. 'DocumentRoot' ディレクトリを削除します. (クローナーはリポジトリを 'DocumentRoot' として clone を行います)
1. '$ php ./clone_repo.php' を実行して clone を行なってください。
1. 'https://qithub.tk/' にアクセスして表示されれば、準備は完了です。

## 更新作業

下記 URL が更新のエンドポイントです。この URL が叩かれると Web サーバ側の `clone` リポジトリが更新されます。

- 更新 URL: `https://qithub.tk/tools/update/`
- 関連情報: [issue #98 サイト Qithub.tk のディレクトリ構造とサイトの自動更新](https://github.com/Qithub-BOT/Qithub-ORG/issues/98)
