# セットアップ・マニュアル

https://qithub.tk/ は、Qithub-ORG のリポジトリから clone されて動作しています。

GitHub 側でマージが行われると、GitHub からの Webhook により Origin のリポジトリ（https://github.com/Qithub-BOT/Qithub-ORG）がコピーされ、Web サーバ側の各種ソースコードはリポジトリの master と同じ状態に保たれます。（データや設定ファイルは除く）

## サーバ側の初回設定

以下は Web サーバ（httpd）と同じユーザ、もしくはグループで作業してください。この作業は１度きりの作業です。

1. 'DocumentRoot'（Nginxの場合）と同階層に、クローナー・スクリプト（'.templates/clone_repo.php'）を設置／コピーしてください。
1. 'DocumentRoot' ディレクトリを削除します. (クローナーはリポジトリを 'DocumentRoot' として clone を行います)
1. '$ php ./clone_repo.php' を実行して clone を行なってください。
1. 'https://qithub.tk/' にアクセスして表示されれば、準備は完了です。

## 更新作業

準備中

