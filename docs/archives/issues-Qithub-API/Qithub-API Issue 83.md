これは  リポジトリの[ issue をアーカイブ]()したものです。

# Qithub のサブ・ドメインの役割について

- 2017/12/17 19:02 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 相談

issue #79 のドメインが決定したら、そのサブ・ドメインを以下のような用途に分けたいと思います。

ご意見お待ちしております。

## TL;DR（結論 2018/01/20 現在）

- 2017/12/21 にドメイン「Qithub.tk」を取得 ( https://github.com/Qithub-BOT/scripts/issues/79#issuecomment-353109079 )

| サブドメイン | 記述サンプル | 用途の概要 |
| :--- | :--- | :--- |
| なし | `https://qithub.tk/` | １ページもの「Qithub」紹介ページ。（関連 [issue #82](https://github.com/Qithub-BOT/items/issues/82)） |
| api | `https://api.qithub.tk/` | BOTの本体。本番環境（`scripts`リポジトリの`master`デプロイ先）|
| dev | `https://dev.qithub.tk/[user]/` |  開発のテスト環境。<br>各自がフォークした `Qithub/scripts` リポジトリの `master` がクローンされる。GitHub からの `webhook` により `git fetch origin`（反映）される。`[user]` は Organization のユーザー |
----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。


-----

## コメント

-----

##### 352285674

2017/12/17 21:08 by hidao80

> `user`は Organization のユーザー

あれ？ 私用のフォルダが掘られる？ これはありがたい。恐縮です。😅

-----

##### 352323952

2017/12/18 04:36 by KEINOS

### DEV.Qithub.xx について

1. `https://dev.qithub.xxx/?` に Webhook のトリガーによる `git fetch origin` の実行をさせるPHPを用意
2. 各々の**フォーク**・リポジトリを `https://dev.qithub.xxx/[user/` に Git Clone する
3. 各自のGitHub フォーク・リポジトリの設定で Webhook 先を下記に指定する
    `https://dev.qithub.xxx/?fetch=[user]&token=[something]`
4. 受け取った Webhook がマージだった場合は `fetch origin` を実行する
    Webwook の POST データの [raw_post_data] => ["action":"merged"] の場合に実行
5. 検証環境にデプロイされる
    `https://dev.qithub.xxx/[user/index.php` は、自身のフォーク・リポジトリの `master` の `scripts/index.php` と同じになる。

以上のような動きでどうでしょう？ブランチごとに動作させた方がよい？
例えば `https://dev.qithub.xxx/?fetch=[user]&token=[something]&branch=[name_branch]`とか。

- 参考： GitHub から受け取る Webhook の例（ Qithub DEV のログ）
https://blog.keinos.com/qithub_dev/?process=github&method=view

-----

##### 352391076

2017/12/18 10:47 by hidao80

Organization メンバーごとに clone (デプロイ) させてもらえそうなのはありがたいのですが、**Organization メンバーの数だけ BOT が動くというのはいかがなものか**とも思います。🤔

誰にもフォローされなければ害がないとはいえ、現在でも1日に数十トゥーとはしているはずですし、それを数倍にするというのは流石に Qiitadon に対して無駄に負荷をかけすぎのような気がします。😅

とはいえ対案が思いつかず、かつ Organization メンバーごとにやりたいことは違うかもしれないので、悩みどころです。🤔

-----

##### 352403280

2017/12/18 11:43 by KEINOS

> Organization メンバーの数だけ BOT が動くというのはいかがなものか

確かに負荷の問題もあるので、現在のように DEV 用に１アカウントというのも考えました。

しかし、BOT が稼働するのは （1）何かしらのトリガーが必要 ＋（2）誰が発行したトークンを使うか、によると思います。

（Qiita や Qiitadon 側でドメイン・ブロックされたら別問題ですが、おそらく、その前にイタズラがすぎるトークンがサーバー側で無効にされると思います）

つまり、各自のクローン BOT は以下の２点で稼働させるなら、Qithub 関係なく各々が自身で API を試すのと何ら変わらないという判断です。

1. cron で全メンバーのトリガーを叩かない（自身で任意のタイミングで手動で呼び出すか、自身で cron を用意する）
1. 各自、自身のアカウントで発行した Qiita や Qiitadon のアクセストークンを利用する

上記の２で、各自が発行したトークンをどうやってサーバーに UP するかの問題がありますが、自動化を検討しつつも、現在は `qithub.conf.json` に記述すれば動くので、当面は私が手動で記載する方向でなんとかなる気がします。


-----

##### 352419708

2017/12/18 13:03 by hidao80

**Oops!** 定期トゥートが cron であることを失念していました。😅

ただのパッシブな Qiitadon シェルエンジンだと思えば、複数起動していても不都合はありませんね👍

では、Organization メンバーごとに clone に👍

## ブランチを実行可能にするか否か

そこまでせんでもいいだろ、master でないもの動かすのどうなの？ とも考えましたが、逆に**master にできるかどうかのテスト環境がいるなぁ**と思い至りました。

ブランチ指定実行機能、やりましょう。#masason 😜

-----

##### 352422321

2017/12/18 13:15 by KEINOS

では、この路線で進めるとして、ドメイン取得とサブドメインの設定が済んだら、この Issue は一旦クローズする方向で。

-----

##### 352428362

2017/12/18 13:41 by hidao80

👍

-----

##### 353540558

2017/12/22 07:36 by KEINOS

TL;DR 更新。

サーバーの設定は済んでいないのですが、ドメインの取得とサブドメインの設定は済んだので、この issue はクローズしていただいてもよろしいかと存じあげ 💪 

-----

##### 353541908

2017/12/22 07:45 by hidao80

👍

ありがとうござい💪
