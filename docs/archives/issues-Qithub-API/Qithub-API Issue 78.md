これは  リポジトリの[ issue をアーカイブ]()したものです。

# Qithub における各所の名称（固有名詞）について

- 2017/12/16 15:08 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 相談

issue #77 で[言及されている](https://github.com/Qithub-BOT/scripts/issues/77#issuecomment-351946006) ように、各種 API の名称がわかりづらいので定義が必要だと思います。

ある程度まとまったらドキュメントにコピペできるように TL;DR 内に進捗を更新する形で進めたいと思います。

## TL;DR（決定 2017/12/21 現在）

| ID | 用語 | 説明や用途 | 備考 |
|---|:---|:---|:---|
| 1 | RESTful API | URLアクセス（REST）による Qithub の機能実行とその仕様のこと。| ルート・エンドポイントは `https://api.qithub.tk/` で、プラグインの実行メソッドはクエリの `?name_plugin=` で指定。APIの仕様は準備中 |
| 2 | コマンド | Mastodon のトゥートによる Qithub のプラグイン実行機能のこと | `@qithub:{プラグイン名}␣{引数}`の書式でダイレクト・トゥート。引数はプラグイン名より後の文末までの全トゥート内容がプラグインに渡される。なお、トゥートは`@qithub:`で始まる必要がある。[[実行のオンライン・サンプル](https://paiza.io/projects/GjvECG7S_-wSbc1WdSHyZw)]|
| 3 | プラグイン API | 本体スクリプト（`/index.php`）とプラグインのデータ送受の仕様 | 詳細は [`scripts/plugins/README.md`](https://github.com/Qithub-BOT/scripts/blob/master/plugins/README.md)を参照 |
| <del>4</del> | <del>システム API</del> |<del> 本体スクリプト（`/index.php`）とアクセストークンなどが必要なプラグインとのデータ送受の仕様</del>| <del>スクリプトの設置箇所が違うだけでプラグインと同じ</del> |
| 5 | プロシージャー | プラグイン API を組み合わせた処理のこと | `./includes/proc/`下に設置。（旧名：プロセス、[参考](https://github.com/Qithub-BOT/scripts/issues/78#issuecomment-353185545)） |
| 6 | Qithub エンコードデータ | JSON 形式のデータを UTF-8 で URL エンコードした文字列データのこと。| Qithub 内の各スクリプト間のデータ送受は、このフォーマットで行われる。|



-----

## コメント

-----

##### 352189284

2017/12/16 15:11 by KEINOS

### プラグインの概要図①

<kbd>![qithub](https://user-images.githubusercontent.com/11840938/34071744-8c39307a-e2be-11e7-95b1-82d95eaa927b.png)</kbd>


-----

##### 352189427

2017/12/16 15:14 by KEINOS

<del>「審議中①」</del> ID 6 ですが、概要図の「名称①」は「プラグインAPI」がいいと思うのですが、プラグインAPIのデータ・フォマット、概要図で言う「名称④」は何と言えば直感的でしょうか。

-----

##### 352214803

2017/12/16 21:59 by hidao80

## 案A

|ID|用語|理由|備考|
|---|---|---|---|
|1|RESTful API|URL アクセスであることを明示するため|名称②|
|2|オーダー|用例）オーダーで指定されたコマンド|名称③|
|5|レシピ|Unix 的なプロセスと混同するため。「プロセス」では「実行中のプログラム」のイメージがある。||
|6|ディッシュ|dish。「調理済み(not raw)でひとまとまりになって渡される」比喩表現から。かつ時制のないメジャーな名詞が良かったから。俗称「皿」|名称④|

用語を料理によせて見ました。どうでしょうか。😊  
ご意見お待ちして💪

P.S.\
Qiita と Qithub で重複する用語は prefix に「Qiita」か「Qithub」をつけましょう。

例) Qiita RESTful API を叩く Qithub RESTful API

-----

##### 352326416

2017/12/18 04:59 by KEINOS

ディッシュやレシピは面白いと思うのですが、ちょっとピンと来ません。（直感的に把握しにくい）

ただ、「プロセス」が「Unix 的なプロセスと混同する」というのは、その通りだと思いました。

Pascal や Delphi のように「Procedure」でもいいのですが、馴染みがないと思いますし。うーん。他にどんなのがいいかしら。

> Qiita と Qithub で重複する用語は prefix に「Qiita」か「Qithub」をつけましょう。

👍 


-----

##### 352386199

2017/12/18 10:27 by hidao80

やはりやりすぎでしたか。😅

ID:2 の「オーダー(号令)」は Mastodon を従えているコマンド(命令)なので、良い感じだと思ってます。  
ので、方向性を変えます。

|ID|用語|理由|
|---|---|---|
|5|ジョブ(job)|一連の動作感を出すため。OS のプロセスと混同しないため。|
|6|チャンク(chunk)|必要なデータがひとかたまりになっているため。|

今度はよそのコンピュータ用語から拝借しました。馴染みがあってそれでいて紛らわしくない線です。😄

-----

##### 353021719

2017/12/20 10:10 by KEINOS

### ID 6 の対案

#### 名称
> 「Qithub エンコード」データ

#### 説明
> JSON 形式のデータを UTF-8 で URL エンコードした文字列データのこと。
> Qithub 内の各スクリプト間のデータ送受は、このフォーマットで行われる。

プラグインのスニペットを書いていて、この表現がしっくりきました。

-----

##### 353069109

2017/12/20 13:55 by hidao80

なるほど😮 私はなぜだか単語にすることにこだわっていたようです。

では、ID 6 は「Qithub エンコードデータ」という名称で 👌❓

P.S.  
ID 5 を「ジョブ」とすると、またフォルダ名の変更が必要ですね。

P.P.S.
ID 5、「Procedure」にすれば、フォルダ名変更の必要がない？😜

-----

##### 353119642

2017/12/20 16:55 by KEINOS

> ID 6 は「Qithub エンコードデータ」という名称で 👌❓

ID 6 は「Qithub エンコードデータ」で 👍 （ 👉  TL;DR 済み）

> ID 5、「Procedure」にすれば、フォルダ名変更の必要がない？😜

Σ(･ω･ノ)ノ!

そうか、そもそも "Procedure" がレシピとか式次第みたいなもんですもんね。Pascal用語だと思い込んでました。 盲点でやんした。

"Process" → "Procedure" に変更で 👌 ❓ 


-----

##### 353185545

2017/12/20 21:20 by hidao80

👍

-----

##### 353225865

2017/12/21 00:51 by KEINOS

> "Process" → "Procedure" に変更で 👌 ❓

👉 TL;DR 済

## [案A](#issuecomment-3522148030) の対案（ID 1 & 2）

ID 1 （図解 名称②）の RESTful と、 ID 2 （図解 名称③）の TOOT ですが、どちらもリクエストなので以下はどうでしょう。

|ID|用語|理由|備考|
|---|---|---|---|
|1|RESTful API | URL リクエストであることを明示するため | 名称② |
|2|TOOTful API | TOOT リクエストであることを明示するため | 名称③ |

### 用例

- `Qithub の RESTful API にリクエストをする`
- `Qithub RESTful API の仕様に準拠する`
- `Qithub の TOOTful API でリクエストする`
- `Qithub TOOTful API のヘルプを叩く`


-----

##### 353252409

2017/12/21 03:59 by hidao80

「RESTful API」は👌ですが、「TOOTful API」は違和感があります。

**TOOTful APIを叩くのはプログラムではなく人**なので、しっくりこない感じです。

対案は次のコメントで書きます。時間がないので申し訳ない！

-----

##### 353277430

2017/12/21 07:23 by hidao80

> アプリケーションプログラミングインタフェース（API、英: Application Programming Interface）とは、ソフトウェアコンポーネントが互いにやりとりするのに使用するインタフェースの仕様である。

[Wikipedia より](https://ja.m.wikipedia.org/wiki/%E3%82%A2%E3%83%97%E3%83%AA%E3%82%B1%E3%83%BC%E3%82%B7%E3%83%A7%E3%83%B3%E3%83%97%E3%83%AD%E3%82%B0%E3%83%A9%E3%83%9F%E3%83%B3%E3%82%B0%E3%82%A4%E3%83%B3%E3%82%BF%E3%83%95%E3%82%A7%E3%83%BC%E3%82%B9)引用

違和感の正体はこれですね。😅

### 案 B

「@qithub:proc」の書式を **「Qithub式（Qithub formula）」** と呼び、ID 2 の事を「Qithub式でメンション（or トゥート）」とするのはどうでしょう？

「固有名詞は単語たるべし」という呪縛から解き放たれていろいろ考えつきましたが、やはり**Mastodon 用語を残した方が直感的ではないか**という結論です。

ご意見お待ちして💪

-----

##### 353290245

2017/12/21 08:34 by KEINOS

> API
> ソフトウェアコンポーネントが互いにやりとりするのに使用するインタフェースの仕様である。

「API を叩く」→「コマンドを叩く」→「API=コマンド」という脳内変換がなされていたようです。

確かに「TOOTful リクエスト」と言われてもプログラム側なら「あ、トゥート経由のリクエストね」とわかるだけで、ユーザーから見ればピンと来ないですね。

### ID 1（RESTful API）

|ID|用語|理由|備考|
|---|---|---|---|
|1| Qithub RESTful API | URL リクエストであることを明示するため | 名称② |

RESTful API に関してはわかりやすいので上記の通りで 👌 なら決定として、問題はトゥートですよね。お互いがピンと来ない理由に以下の２つがある気がします。

1.「プログラム内で該当処理を指すときの開発者が呼ぶ名称」
2.「トゥートによるプラグインの実行を利用者がどう呼ぶかの名称」

上記２つを、同じ扱いにしようとしているからのような気がしてきました。

後者の名称は、「トゥートによるプラグイン実行のコマンドが可能になった」際のアナウンスで、**ユーザーを惹きつける要だと思う**ので、まずは先に後者を十分に議論したいところです。

アナウンス時に Qiitadon ユーザーがピンとくるようなものにしたいので「メンション」や「トゥート」などの「**Mastodon 用語を残した方が直感的ではないか**」に同意します。

その上で、「Qithub式でトゥート」は何かピンとこないのです。「アミノ式でトゥート」と急に言われてもわからない感じ？

 Qiitadon ユーザーは、プログラマ寄りなので「コマンド」「フォーマット」「実行」といった汎用的な用語がいいと思うのです。「シェル・コマンドの Qiitadon 版」くらいの気軽さを与えられるような。

アナウンスの例文を先に考えるといいのかしら。

> 時報と新着Qiita記事の通知をしている BOT ですが、ユーザー・プラグインをトゥートで実行できるようにしました！
> 
> 以下の書式でダイレクト・メンションすると実行できます。
> ```
> ＠qithub:コマンド␣引数
> ```
> 詳しくは`＠qithub:help`で！ #はじめてのQithub





-----

##### 353290937

2017/12/21 08:37 by KEINOS

って、 ☝️ で「コマンド」すら使ってないじゃないかい！
ダイレクトメンションすると実行できる「それ」の名称が欲しいのに！

(|||_ _)ノ[電柱]

-----

##### 353304115

2017/12/21 09:36 by hidao80

> 「トゥートによるプラグイン実行のコマンドが可能になった」

たぶん、この認識が本質だと思います。

我々が他の人（友達とか同僚とか）に**最も端的に説明する言葉**を採用するのが正解で、それが冒頭の引用文だと考えます。

で、この際ユーザにはプラグインで機能追加できることも隠蔽して、 **「コマンドをトゥートしたら動くぜベイベ」** くらいのノリでちょうど良いのかもしれません。

よって、ID 2 は単に「コマンド」で良いと考えます。考えすぎて1周してしまった。

> 用例）Qithub にコマンドをトゥート！ 超〜エキサイティング！！！！

😜

### ID 1 について

👌👍

### 提案：内部 API 名の統一

#77 の結論より、「プラグイン API」と「システム API」の境界が曖昧になりました。どちらかに統一しませんか？
私は「プラグイン API」に👍

-----

##### 353360986

2017/12/21 14:14 by KEINOS

> 私は「プラグイン API」に👍

👍 今後は「プラグインAPI」もしくは「Qithub Plugins API」で。 👉 TL;DR

> ID 2 は単に「コマンド」で良いと考えます。

💋 👍 👉 TL;DR

とりあえず、今のところの用語は決定したので、[用語集](https://github.com/Qithub-BOT/items/blob/master/7157c17765e328917667.md)に反映したらクローズで 👌 ❓ 

P.S.  
PR Qithub-BOT/items/pull/88 で反映をうpしました。


-----

##### 353453891

2017/12/21 20:46 by hidao80

👌👍

-----

##### 357198911

2018/01/12 10:16 by KEINOS

クローズしマスター
