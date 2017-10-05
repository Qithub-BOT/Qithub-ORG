## このリポジトリについて
プログラマのための技術情報共有サービス「[Qiita](https://qiita.com)」の記事を、Qiitaユーザーでコラボレーション（共同作成・編集）するための記事本文Markdownデータのリポジトリです。

## コラボレーションについて
**GitHubに置かれたMarkdownファイルが更新されるとQiitaの該当記事が更新される仕組み**になっており、`git`を使えるQiitaのユーザーであれば誰でも記事の編集・修正が行えます。

1. 編集したいQiitaコラボ記事の記事IDを探します。（Qiita上のURLの末尾の番号）
1. [`items`リポジトリ](https://github.com/Qithub-BOT/items)をCloneします。
1. ブランチ名を「＜Qiita記事ID＞-＜日付＞」のフォーマットで新しいブランチを作成します。
1. 作成したブランチ内の該当記事を編集します。
1. プルリクエストを行い、チェック後、特に問題がなければQiitaに修正が反映されます。

## チェックについて
プルリクエスト（修正の反映依頼）のあった記事は、[Qiitadon](https://qiitadon.com/ "Qiitaのマストドン・インスタンス") の BOT「[Qithub](https://qiitadon.com/@qithub)」のフォロワーに通知されます。

通知に対し「:thumbsup:」（いわゆる LGTM ）の投票数が 10 件以上得られると承認され、本体にマージされると同時にQiitaに反映されます。

## フォーマット

- ファイル名： `<Qiita記事ID>.md`
- 初回投稿先： `https://qiita.com/Qithub/private/<Qiita記事ID>`（限定共有で公開 ※）
- 最終投稿先： `https://qiita.com/Qithub/items/<Qiita記事ID>`
- タイトル　： 本文の１行目がQiita記事のタイトルになります。２行目には改行を必ず入れてください。
- タグ　　　： 本文の３行目がQiita記事に付けられるタグになります。４行目には改行を必ずいれてください。
- メインの記事内容： 本文の５行目から通常のQiita記事のMarkdownで記入してください。記述できる内容もQiitaのMarkdown記法に準拠します。（後述する画像を除く） 

※ 後述するBOTのフォロワーの :thumbsup: が１０以上得られると、Qiita記事のステータスが「限定共有」から「公開」に変わります。



