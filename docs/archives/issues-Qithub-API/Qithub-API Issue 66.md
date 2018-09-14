これは  リポジトリの[ issue をアーカイブ]()したものです。

# プロセス：「Qiitaコラボ記事」をデプロイするだけの機能

- 2017/11/20 14:08 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 提案

記事ID をクエリに渡すと GitHub の items → Qiita の items に記事をアップデート（デプロイ）する機能が欲しい。

`scripts`の機能が充実してきたのは良いのですが`items`の機能もね。まずはシンプルにアップロードするだけから始める系で。

## 自分の案

1. リクエスト受付（記事ID取得）
2. 該当記事IDがあれば Qiita API で記事をアップデート

## その後のToDo

1. 共同著者の取得 Qithub-BOT/items/issues/63
2. 共同著者とライセンスの自動挿入 Qithub-BOT/items/issues/63
3. Qiita互換記法への画像タグ置換 Qithub-BOT/items/issues/31

----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。

----------------

## TL;DR（進捗 2017/12/12 現在）

- 実装決定。[ブランチ](https://github.com/Qithub-BOT/scripts/tree/%2366-process-add-deploy_item_to_qiita-20171207)しながら、のんびり対応中ヾ(`･ω･´)ﾉ



-----

## コメント

-----

##### 345980240

2017/11/21 10:15 by hidao80

👍

まずは ID 取得とアップデートからですね！

-----

##### 352337798

2017/12/18 06:33 by KEINOS

そろそろアップに入りました。

-----

##### 353051306

2017/12/20 12:31 by KEINOS

PR #85 で実装しました。

Qithub RESTful API のリクエストの場合、Qiita記事IDをクエリの引数 `id=` に渡すと GitHub → Qiita に更新されるはずです。

トゥートによる互換のために、本来は `args=` の引数で記事 ID を渡さないといけないのですが、トゥートによるコマンド実行の仕様がもう少し固まったら修正します。

### Qithub RESTful API の Items デプロイ・メソッド

エンドポイント： `?process=deploy-item-to-qiita`
引数1：`&id=` ：Qiita記事ID
引数2：`&mode=`  ： "debug" の場合、詳細な結果が表示される

#### DEV環境での動作テスト

DEV環境での動作の場合は、Qiita の Qithub アカウントでなく DEV アカウントのダミーの記事（※１）を更新します。

##### リクエスト・サンプル
- Qiita記事ID "7157c17765e328917667" のリクエスト
    https://blog.keinos.com/qithub_dev/?process=deploy-item-to-qiita&id=7157c17765e328917667&mode=debug
- Qiita記事ID "1a52282f0b132347c2b1" のリクエスト
    https://blog.keinos.com/qithub_dev/?process=deploy-item-to-qiita&id=1a52282f0b132347c2b1&mode=debug

##### 反映先（@KEINOS の DEV用アカウント）
https://qiita.com/KEINOS_BOT/private/58ca66a808aa774c8233

※１：現在、ダミーの記事IDはスクリプト・ディレクトリ内の定数定義ファイル（`constants.php.inc`）にハードコードしていますが、本体スクリプトから渡す予定です。


-----

##### 353281212

2017/12/21 07:45 by KEINOS

### 報告

本番環境に `deploy-item-to-qiita` を適用（デプロイ）しました。以下、検証リンクです。Origin の master を直接編集して反映を確認してみてください。

#### 検証①

- 元データ：https://github.com/Qithub-BOT/items/blob/master/7157c17765e328917667.md
- 反映実行： https://blog.keinos.com/qithub/?process=deploy-item-to-qiita&id=7157c17765e328917667
- 反映確認：https://qiita.com/Qithub/private/7157c17765e328917667

#### 検証②

- 元データ：https://github.com/Qithub-BOT/items/blob/master/1a52282f0b132347c2b1.md
- 反映実行：https://blog.keinos.com/qithub/?process=deploy-item-to-qiita&id=1a52282f0b132347c2b1
- 反映確認：https://qiita.com/Qithub/private/1a52282f0b132347c2b1

#### 検証③

- 元データ：https://github.com/Qithub-BOT/items/blob/master/3c1f06f93eb03463c470.md
- 反映実行：https://blog.keinos.com/qithub/?process=deploy-item-to-qiita&id=3c1f06f93eb03463c470
- 反映確認：https://qiita.com/Qithub/private/3c1f06f93eb03463c470

### P.S.

検証して気づいたのですが、以下の仕様を固めないといけないですね。Items リポジトリに issue を別途立てたいと思います。

- Qiita 記事につけるタグの記述
- 「この記事は Qiita ユーザーのコラボ記事です。GitHub にプルリクをあげると編集できます」の文言と自動挿入について
- コラボレーターの挿入。将来的には自動挿入の予定だがコメント欄など Commit していない人を追加で入れたい場合など。
