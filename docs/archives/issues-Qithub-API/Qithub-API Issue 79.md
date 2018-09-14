これは  リポジトリの[ issue をアーカイブ]()したものです。

# Qithub のサーバー移行（Xrea→さくらのVPS）とドメイン（ Qithub のRootエンドポイント）

- 2017/12/16 16:11 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 報告

Qithub のサーバーを さくらのVPS に引っ越そうと思います。あわせて Qithub へのアクセスおよび Qithub API へのエンドポイント名が変わります。

## 相談

`KEINOS`がドメインに入っちゃってるのも何だなと感じるので、[無料ドメイン](https://qiita.com/search?q=%E7%84%A1%E6%96%99%E3%83%89%E3%83%A1%E3%82%A4%E3%83%B3)を取得して割り当てるのもありかなと思うのですが、いかがでしょう。

あと、こんなアプリ入れたいとか。こんな風に使いたいとか。

## 詳細

現在（2017/12/17）、Value-Domain の Xrea のショボいサーバーで、ブログの１ディレクトリを使ってQithub を動かしています。（`https://blog.keinos.com/qithub`）

ブログが重いのでサーバーを引っ越すのですが、その際に Qithub を切り離したいと考えています。

切り離し先ですが、以前より諸々の検証に利用していた「さくらのVPS」を考えています。

というのも、最近仕事の内容も変わったため利用しておらず、もったいないので Qithub に使いたいと思うようになり、初期化してサブドメインを割り当てました。（CloudFlare の CDN も動いています）

https://qithub.keinos.com/

[セキュリティの設定](https://qiita.com/KEINOS/items/16de4e676fe3c3c8beea)もまだ行っておりませんが、以下のように、**スペックちょっと高め＋ VPS なので好きなようにカスタムできる**ため、あれこれできます。（機械学習とか他の言語やアプリのインストールも）

|項目|内容|
|:---|:---|
| OS | CentOS 7 |
| メモリ | 2 GB |
| ストレージ |  SSD  50 GB |
| CPU | 3コア |

----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。

----------------

## TL;DR（結論 2017/12/21 現在）

- Qithub の独自ドメインは「[Qithub.tk](https://Qithub.tk/)」とする（[2017/12/18 制定](#issuecomment-352427835)）
    - ドメインの情報・取り扱い・議論先 → Qithub-BOT/items/issues/87
    - サブドメインの情報・取り扱い・議論先 → `scripts`リポジトリ の issue #83

- Qithub のアプリ開発を行う場合のアプリ名は「ChQiita（チキータ）」とする（[2017/12/19 制定](#issuecomment-352672812)）
- インストールするプログラム言語 → #80 
- TeX のインストール → #81 
- Babel のインストール → #82 
- その他のアプリをインストールしたい場合は、別途、各々 issue をたてる


-----

## コメント

-----

##### 352215922

2017/12/16 22:18 by hidao80

人様のレンタルサーバ(VPN)に注文つけるのも気が引けるのですが、それは横においておいて。😅

### 無料ドメイン

面白そうですね！ こんなことができること自体知りませんでした。ハードルが高くないなら、やって見たいです!🤩\
「qithub.*」で取ると、後発の clone BOT のハードルを上げてしまいますかね。😅 公式っぽくていいですが。

### 入れたいアプリ

- TeX 数式コードを数式画像に変換するツール：幸せになれる人が増えそう。
- golang：私がプラグイン開発に使いたいだけ。
- babel：node.js 産トランスパイラ。各種ソースコードを別言語のソースコードに変換するコンパイラ。Qiita API ラッパーを翻訳するのに使える？

-----

##### 352277392

2017/12/17 19:03 by KEINOS

### 無料ドメイン候補

> ハードルが高くないなら、やって見たいです!🤩

私もやってみたいので、やってみましょう！ 👍 

Qiitaの記事を見ると無料ドメイン・レジストラでは [Freenom](http://www.freenom.com/ja/index.html?lang=ja) が一番多いようです。情報も多いので Freenom にしようかと思っているのですが、"Qithub" で以下のドメイン候補が出ました。

どれにしましょう？

1. qithub.tk
2. qithub.ml
3. qithub.ga
4. qithub.cf
5. qithub.gq

個人的にはドメインっぽくないですが "Qithub.gq" に１票です。もともと "Qithub" と "GitHub" の "Q" と "G" の違いが注意ポイントなので ".gq"とあっているのかな、と。

".tk" は小室ファミリーみたい、".ml" はメーリングリストみたい、".cf" はわけからかないので、次点は発音しやすい ".ga" といったところでしょうか。

### 入れたいアプリ

どれも 👍 

- **TeX**：TeX は Items の issue にも、[やってみたいリストにあがっている](https://github.com/Qithub-BOT/items/issues/14#issuecomment-342115532)ので入れる方向で行きましょう。 👉 issue #81
- **言語**： 👉 issue #80
- **Babel**：「Babelって純正JSをブラウザ互換のJSにするもんじゃないの？」と思っていたのですが、思い出した！Qiitadonでも流れた[あの記事](https://qiita.com/kotarella1110/items/064904b3269098938be8)ですか！面白そう！やってみましょう。 👉 issue #82

### サブドメインの候補

上記、ドメインが決定したら、サブドメインについても議論したいと思います。 👉 issue #83




-----

##### 352286633

2017/12/17 21:23 by hidao80

う〜ん、甲乙つけ難いですが、あえて推すなら次の順でしょうか。🤔

1. qithub.tk
2. qithub.gq
3. qithub.ga

でしょうか。"tk" は私的には "ToolKit" に見えるので、API or ユーティリティ臭が濃くて良いです。"gq" は @KEINOS さんの推し通りです。"ga" は発音しやすくて良いですね。👍

"cf" は "Communication Front-end" と解釈するこじつけも考えましたが苦しいです。🤔  
"ml" は…すみませんメーリングリストかミリリットルにしか見えません。無しです。 😅

-----

##### 352322222

2017/12/18 04:18 by KEINOS

となると、以下の３つドメインに絞り込めそうですね。

1.  qithub.tk
2. qithub.gq
3. qithub.ga
4. <del>qithub.ml</del>
5. <del>qithub.cf</del>

> "tk" は私的には "ToolKit" に見えるので、API or ユーティリティ臭が濃くて良いです。

なるほどー。プラグイン制というのにも合ってるかも！

票数的には "Qithub.ga" が優勢ですが、**「Qithub ToolKit API」と正式名称を変えてしまうならあり**かもしれませんね。

Qithub は BOT と API の提供に徹して、Toot 以外では Electron などの外部から API で使えるようにしたいんですよね。


-----

##### 352337499

2017/12/18 06:31 by KEINOS

むしろ「Qithub.ToolKit」でもいいか。

-----

##### 352383877

2017/12/18 10:18 by hidao80

「Qithub ToolKit」だと、ユーザが「Qithub」を未定義な状態で名前を知ってしまうので意味不明にならないでしょうか？🤔 ToolKit がなければ「Qithub」という固有名詞として押し通せるとも考えます。

「Qithub (Qiitadon and user's hub Toolkit)」とかならまだわかりますが。😅

-----

##### 352409620

2017/12/18 12:15 by KEINOS

> 「Qithub」という固有名詞として押し通せる

なるほど、サブタイトル系で攻める感じですね。

```
           Qithub
( For Beautiful Human Life )
```

とか、セシールの「フィオンソネムン・フィオンソナムン」みたいな、イミフのキャッチ・フレーズというか。

".tk" で進めるなら以下はどうでしょう？

1. Qithub.tk （Toolkit for Qiita and Qiitadon）
1. Qithub.tk （The hub toolkit of, by and for the Qiita users）
1. Qithub.tk （Collaboration of, by and for the Qiita users）
1. Qithub.tk （Collaboration toolkit of, by and for the Qiita users）
1. Qithub.tk （Collaborated toolkit by Qiita users）
1. Qithub.tk （WowWar Toolkit for Qiita users）

まぁ、"Qithub.ga" でもいいんですけどね。"Toolkit" がちょっと気に入ってw

-----

##### 352417996

2017/12/18 12:55 by hidao80

ドメインなので、「.tk」にそこまで意味を見出さずとも…😅

tk ドメイン取って、身内でニヤニヤするだけでもいいと思います。😁

BOT 名が「Qithub」で、デプロイ先アドレスが「qithub.tk」でいいと思います。サードパーティ(Organization外の開発者)は Qiitadon から自分が運営する BOT にメンションするだけなので、tk ドメイン見えませんし😜

-----

##### 352421138

2017/12/18 13:10 by KEINOS

まぁ、GitHubを見たユーザーに「テツヤ・コムロじゃないの！コムロじゃ」と言いたい気持ちだけなんですけどね。 😄 

では **".tk" で行きます**か。

まだ皮算用ですが、Qithub の RESTful API が安定・充実してきたら、Qiita や Qiitadon のパーサー的な使い方で Electron で Qithub APIから諸々のデータを引っ張って来て「Qiita/Qiitadonリーダー」や「ポストKobito」みたいなのができたらいいなと思ってたり。 😎 
（アプリは敷居が高い気がするので）


-----

##### 352427835

2017/12/18 13:39 by hidao80

👍

### ポストKobito について

あれって [Github に公開されているバージョン](https://github.com/increments/kobito-oss)があったはず。我々がフォークして「elfin」とか名前変えてメンテする手もありかも。

-----

##### 352448030

2017/12/18 14:55 by KEINOS

> 我々がフォークして「elfin」とか名前変えてメンテする手もありかも。

そうか！OSS で出してましたもんね。それを拡張する方向でもいいのか。なるほどー。

> 「elfin」

スペイン語だと「ザ・ラスト」という意味ですが、何か他の意味もあるんでしょうか。


-----

##### 352538527

2017/12/18 19:49 by hidao80

>Elfin

[weblio 英和和英辞典 の「Elfin」](https://ejje.weblio.jp/content/Elfin)によると、

>「小妖精の(ような)、いたずらな、ちゃめな」

（原文ママ）とあります。

「Kobito」(小人) と同種だけど別物であることがわかり、語呂が良さそうかな〜と思って5分くらいでサクッと仮名としました。「elf」(エルフ：妖精) が語源の形容詞です。

…なら「elf」でいいじゃん！∑(ﾟДﾟ)

P.S.  
ドワーフとかノームとかギズモとかグレムリンとかも考えましたが、マイナスイメージが強くてやめました。😜

-----

##### 352668625

2017/12/19 08:05 by KEINOS

>>> Elfin
>>
>> 「小妖精の(ような)、いたずらな、ちゃめな」
>
> 「Kobito」(小人) と同種だけど別物

あー、エフルの "elf" ですか！小さなエルフって事ですね！

「Kobito（小人）」から来る「小さな」というのはいいですね。

では、実際に "Kobito-OSS" を進めるかは別として、アプリ名を考える遊びとして、スペイン語で「小さなもの」や「小さな子」という意味の「**チキータ（ChQiita）**」はどうでしょう？

**正しいスペルは「Chiquita」** なのですが、Qiita の色んなチャンネル（ Qiita, Qiitadon, プラグイン, etc）の HUB としての役割の意味も込めて Channel ＋ Qiita = 「ChQiita」






-----

##### 352672812

2017/12/19 08:25 by hidao80

なるほど、妙案ですね😀 「ChQiita」、いいと思います！

ただ、私は Kobito ユーザから求められてるものは「Qiita の下書き置き場（エディタもあるでよ）」だと思ってるので、Qithub の類をくっつけるとやり過ぎかなぁ、と思わなくもないです😅

ですが Qiitadon や Qithub とのコラボは我々ならではのビジョンなので面白そうだと思います！

P.S.  
…あれ？ 名前を考える遊びでしたね。話を変な方向に持って行ってしまって申し訳ない。

-----

##### 353109079

2017/12/20 16:20 by KEINOS

### 報告

ドメイン「Qithub.tk」を取得しました。（ Qithub-BOT/items/issues/87 ）

サーバーの諸設定はまだ行なっておりません。

### 相談

以前 Qiitadon で、オープンソースで Web サイドで動く IDE で PHP にも対応したノマド向けの話が出てた思うのですが、何でしたっけ？ トゥート検索してもヒットしないのです。



-----

##### 353137741

2017/12/20 18:01 by KEINOS

> 以前 Qiitadon で、オープンソースで Web サイドで動く IDE で PHP にも対応したノマド向けの話が出てた思うのですが、何でしたっけ？ トゥート検索してもヒットしないのです。

これだ！ http://codiad.com/

### Codiad でやりたいこと

`https://dev.qithub.tk/<username>/` 下にクローンされた各自の Qithub フォーク・リポジトリを Codiad で編集＆変更を各自のフォークにマージできる環境。（ Origin への PR は未定）

1. `https://dev.qithub.tk/` （ルート）にアクセス
2. OAuth で GitHub にログイン（※）
3. Codiad が立ちあがる
4. 各自のDEV環境（`https://dev.qithub.tk/<username>/`）のみがいじれる
5. いじった内容が自分のフォークに反映される（HOW?）

※ むしろ Qiita に OAuth ログインさせて、ユーザー設定画面を用意し GitHub で発行したアクセストークンを入力させるのもありか？

-----

##### 353179594

2017/12/20 20:53 by hidao80

面白いアイデアですが、やはり Github との連携方法が不明ですね。🤔

**Github への push もなにがしかの自作 RESTful API にやらせれば**なんとかなるかもしれませんが。

ちょっと試したいんだけどな〜、くらいにはちょうどいいかもしれません。👍

そもそも、Web で編集するのなら **Github で編集してなにがしかの自作 RESTful API に clone させる**方が自然なのかもしれないですが。🤔

-----

##### 353232277

2017/12/21 01:32 by KEINOS

> そもそも、Web で編集するのなら **Github で編集してなにがしかの自作 RESTful API に clone させる**方が自然なのかもしれない

Σ(￣□￣；） ｿｳｶ！

1. 本番（ `https://api.qithub.tk/` ）もDEV環境（ `https://dev.qithub.tk/<yourname>/` ）も、各々の GitHub リポジトリのクローンである
2. 各々のリポジトリにマージされたら GitHub の WebHook を受け取れる

上記２点から、Qithub 自体が自身を更新（`git fetch origin`）する API （新プラグイン。旧プロセス）を用意して、 **各自のリポジトリの WebHook で叩かせればいいだけ**ですね。

ちょっと DEV環境で試してみてから、いけそうなら別 issue を立てたいと思います。

とりあえず、 **`Codiad` のインストールはペンディング** とし、必要なら別 issue をたてる方向で 。

本 issue の [TL;DR 更新した](#issue-282632887)ので 👌 なら Close お願いし 💪 。


-----

##### 353251206

2017/12/21 03:48 by hidao80

👍

-----

##### 354666875

2018/01/01 18:05 by KEINOS

@hidao80 

無駄に Kobito-OSS を Qithub-BOT にフォークしてみました。

https://github.com/Qithub-BOT/ChQiita


-----

##### 359029280

2018/01/19 17:08 by KEINOS

TK引退しちゃいましたね …( ･´ω･｀)ﾎﾞｿｯ
