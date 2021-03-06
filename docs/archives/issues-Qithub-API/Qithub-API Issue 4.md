これは  リポジトリの[ issue をアーカイブ]()したものです。

# BOT の動作間隔（またはトリガー）を決めましょう

- 2017/09/25 04:19 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

BOTが動作する間隔およびトリガー（きっかけ）ですが、以下はいかがでしょう。

- cronによる定期実行 → 定例処理
- GitHubからのWebHook → 随時処理

## 定例処理で行う内容の例

- 新着Qiita記事の確認とお知らせトゥート処理
- BOT宛てのトゥートの確認と返信処理
- レビュー（ :thumbsup: のカウントなど）関連処理

## 随時処理で行う内容の例

- プルリクに対する処理

-----

## TL;DR（結論 2017/10/14 現在）

### BOTの実行間隔の仕様
- cronによる5分間隔の起動（定例処理）
- GitHubからのWebHookによる起動（随時処理）
- URL直打ちによる起動（臨時処理）

-----

## コメント

-----

##### 331779111

2017/09/25 05:15 by hidao80

cron の件、OKです。

### 随時対応の件

プルリクに対する処理って、BOTにできます!? ファイル名が規則通りならばレビューせずにマージするってことでしょうか？
その点が疑問です。

実装するときは、最低限の機能（１機能追加するごとでもいいと思う）ごとにしましようね。

-----

##### 331793299

2017/09/25 06:59 by KEINOS

> プルリクに対する処理って、BOTにできます!? 

どうやら「プルリク」「issue発行」「マージ」など、何かしらのアクションがGitHub上であると、GitHubの設定に登録した「WebHook」のURLにJSONらしきデータが飛んで来る**らしい**です。

なので、これ自体はcronで定期的に叩かなくても良いため「随時処理」としました。

WebHookが「プルリク」だった場合は、もにょもにょ（未定）して、フォロワーに「新しい投稿があったよ！みんなチェックいれてあげてね」とレビュー依頼をトゥート。までが随時処理の内容。

その後、cronで返信をチェックして 👍 をカウント。 

```
$count_max = 10;
if( count_lgtm( 👍 ) >= $count_max ){
    // プルリクを承認＆マージ
    // マージ内容をQiita記事に反映
    // 初版だった場合は公開トゥート
}
```
APIを使うか、サーバー側のGitコマンドを叩かせるかの２択だと思います。

> ファイル名が規則通りならばレビューせずにマージするってことでしょうか？

プルリクに対してどのように対応するかは、まだ決めていません。決めないとですね。

基本として「 👍 トゥートが特定数以上集まらないとマージしない」で良いような気がします。

> 実装するときは、最低限の機能（１機能追加するごとでもいいと思う）ごとにしましようね。

そうですね。「ステップ・バイ・ステップばい」で行きましょう！

「BOTの機能実装」と「Qiitaコラボ記事」の各々のmasterへのマージが不明瞭ですね。


-----

##### 331798639

2017/09/25 07:27 by hidao80

## 随時処理

なるほど、Github がトリガーを引いてくれるんですね。これは不勉強でした、

WebHook なるものがあれば、随時処理が可能ですね。動作に幅が出ますね。

## マージ基準

Qiita記事は :thumbsup: のカウントで対応できそうですね。

bot 開発の方は自動化は考えず、機能単位またはファイル単位でcore 層（items の ＃12 参照）の関係者をアサイン（github の機能）して、責任者を置き、責任者の責任でマージで良いかもしれません。（プルリク対応も含む）

コードレビューを必須とするとなお良しですが、まずは気楽に楽しく開発するためにレビューは無しで行きましょう！ 規模（システム、人数両方の意味で）が大きくなったらやるようにしたらいいと思います。

-----

##### 332307522

2017/09/26 19:22 by KEINOS

マージの基準はトリガーとは別件と思ったので別途 issue を立てました。（ #5 ）

BOT の動作間隔／トリガーですが、以下でOKクローズでしょうか。

- cronによる5分間隔の起動（定例処理）
- GitHubからのWebHookによる起動（随時処理）
- URL直打ちによる起動（臨時処理）



-----

##### 332356878

2017/09/26 22:40 by hidao80

:thumbsup:

-----

##### 332385460

2017/09/27 01:50 by KEINOS

# BOTの実行間隔の仕様
- cronによる5分間隔の起動（定例処理）
- GitHubからのWebHookによる起動（随時処理）
- URL直打ちによる起動（臨時処理）

-----

##### 336627145

2017/10/14 10:52 by KEINOS

TL;DR を追加しました。
