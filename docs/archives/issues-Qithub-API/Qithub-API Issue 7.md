これは  リポジトリの[ issue をアーカイブ]()したものです。

# 'scripts'リポジトリ のディレクトリ構成

- 2017/09/26 21:06 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

いよいよBOTの開発が始まるわけですが、BOTのスクリプトを書くにあたってディレクトリ構成を決めたいと思います。

まずは以下の構成からスタートでいかがでしょう。

**`README.md`：** BOTに関するREADME
**`index.php`：** BOT本体（cronやWebHookで叩かれる）スクリプト
**`_samples/`：** 設定ファイルなどのサンプルを置くディレクトリ
**`qithub.conf.sample`：** 設定ファイルのサンプル
**`_scripts/`：** BOTのエンジンとなるスクリプト類を置くディレクトリ

```
- `items`リポジトリ /
　　　　⊢ README.md
　　　　⊢ index.php
　　　　⊢ _samples /
　　　　　　　　　　　　∟ qithub.conf.sample
　　　　∟ _scripts /
```

## 留意点

- デプロイ先のサーバーに`git clone`するだけで動くようにしたい
- BOT動作の要であり、GitHub上に置けないものとして「アクセストークン」がある

### Clone後のサーバー側（デプロイ先）のディレクトリ構成の例
２階層上（wwwディレクトリ外）にある"qithub.conf"は"_samples"ディレクトリからコピーしたファイルで、アクセストークンなどの設定が記載されている。

```
⊢ www /
∣　　　　⊢ qithub /
∣　　　　∣　　　　⊢ README.md
∣　　　　∣　　　　⊢ index.php
∣　　　　∣　　　　⊢ _samples /
∣　　　　∣　　　　　　　　∟ qithub.conf.sample
∣　　　　∣　　　　∟ _scripts /
∣　　　　∣
∣　　　　⊢ etc /
∣　　　　∣
∣　　　　⋮
∣
⊢ qithub.conf
∣
⋮
```


-----

## コメント

-----

##### 332398239

2017/09/27 03:25 by hidao80

:thumbsup:

特に問題ないと思います。
いよいよ開発ですね！

-----

##### 332408595

2017/09/27 04:54 by KEINOS

👌 いよいよですな！

-----

##### 332433556

2017/09/27 07:25 by hidao80

とんでもないことに気が付いてしまった…

```
- items リポジトリ /
　　　　⊢ README.md
　　　　⊢ index.php
　　　　⊢ _samples /
　　　　　　　　　　　　∟ qithub.conf.sample
　　　　∟ _scripts /
```

**`scripts` リポジトリ**ですよね…？

サーバ側も、

```
⊢ www /
∣　　　　⊢ qithub /
∣　　　　∣　　　　⊢ README.md
∣　　　　∣　　　　⊢ index.php
∣　　　　∣　　　　⊢ _samples /
∣　　　　∣　　　　　　　　∟ qithub.conf.sample
∣　　　　∣　　　　∟ _scripts /
∣　　　　∣
∣　　　　⊢ etc /
∣　　　　∣
∣　　　　⋮
∣
⊢ qithub.conf
∣
⋮
```

で、`git clone`一発でデプロイしようとしたら、`scripts`リポジトリの名前を`qithub`にしないとうまくいかないのでは？

私のカン違いならいいのですが… :(

-----

##### 332439093

2017/09/27 07:49 by KEINOS

あ、間違えました！`items`→`scripts`リポジトリです！ご指摘ありがとうございます。

> git clone一発でデプロイしようとしたら、scriptsリポジトリの名前をqithubにしないとうまくいかないのでは？

私も"GitHub Desktop"でローカルに "Clone" する際、「これじゃ"scripts"ディレクトリになっちゃうじゃん」と思ったのですが、ディレクトリ名（リポジトリ名）を指定できたので、その際に「qithub」と指定しました。

ではコマンドで出来るのかと調べてみたところ、下記の方法でCloneできるみたいです。

```
git clone https://github.com/Qithub-BOT/scripts.git qithub
```

ただ、要検証だと思います。・・・`script`→`qithub`に変えちゃいます？

-----

##### 332440575

2017/09/27 07:55 by hidao80

いいえ、`scripts`リポジトリのままでいいと思います。

そうですね、`git clone <URL> qithub` できることをすっかり忘れてました。
**リポジトリ名を頻繁に変えるとドキュメンターが泣く**ので勘弁してください。;-(

-----

##### 332444686

2017/09/27 08:12 by KEINOS

泣いちゃいますよねー！:thumbsup:

-----

##### 333870472

2017/10/03 15:01 by KEINOS

## 1. 確認
BOTのプラグイン仕様（#16）とセキュリティissue（#17）により、「_plugins」ディレクトリと「.htaccess」 ファイルを追加しようと思います。（作業中）

プラグイン類は「_plugins」ディレクトリに移動。「_scripts」ディレクトリは残し、トゥートの実行部分などのスクリプトが残される方向です。

```
─ `scripts`リポジトリ /
　　　　├─ README.md
　　　　├─ index.php （本体スクリプト）
　　　　├─ _samples /
　　　　│　　　　　　　　└─ qithub.conf.sample
　　　　│
　　　　├─ _plugins /
　　　　│　　　　　　　├─ .htaccess
　　　　│　　　　　　　├─ index.html （ブランクファイル）
　　　　│　　　　　　　├─ issueXX /
　　　　│　　　　　　　├─ issueXX /
　　　　│　　　　　　　├─ ⋯
　　　　│　　　　　　　⋮
　　　　│
　　　　└─ _scripts /
　　　　　　　　　　　 ├─ .htaccess
　　　　　　　　　　　 ├─ index.html （ブランクファイル）
　　　　　　　　　　　 └─ ？？？？
　　　　　　　　　　　 └─ ？？？？ /
```

## 2. 相談

上記ディレクトリ構成図で「_scripts」以下の「？？？？」のファイルやディレクトリですが、各々の命名ルールはどうしましょう？

### 自分の案

    プラグインと同様に「issueXX」とし、issue名に「スクリプト：〜」とすることで基本機能や仕様の追跡をしやすくする。

スクリプトファイルの利用はファイル名からの直感性はありませんが、issue番号に追従するという共通ルールでシンプルに運用できると期待します。

_※ ここでの話は、ファイル／ディレクトリ名の話で、スクリプト内の関数やクラス名などは含まれません。_


-----

##### 333871765

2017/10/03 15:05 by KEINOS

取り急ぎ上記で進めておりますが、ここで決定したら準拠するようにいたし:muscle:。

-----

##### 333973680

2017/10/03 20:48 by hidao80

ぬー、いまいちこのフォルダ・ファイル構成が直感的でないと感じています…\
対案として、

1. `_scripts` を `system` または `qithub` に改称する。
1. プラグインには `issueXX` という名前を使わず、テンプレートをドキュメントに指示し、それに沿わせる\
    例）<動詞>-<対象>
1. ファイル名として、エントリポイント（main関数的な、プラグインの起点となるファイル）には `main.<拡張子>` を指定する。\
    例1）main.php\
    例2）main.py

BOT の機能を追加するとき、むやみな拡張を突っ込まれないためにファイル名に issue# を使うことは理解できますが、plugins のファイル名に issue# を使うことは反対です。 

Github の前提として、Organization 外の人からの PR も受け付けるということになると思うのですが、その時、提案の通りだと**issue#がないとファイル名がつけられない規約になるため、issue を立てていないと実装・PR できない**というのはハードルが高すぎます。 #6 を思い出せ！

また、エントリポイントのファイル名を固定することで、呼び出し側スクリプトの定型化を目指します。

というわけで、対案をグラフにすると以下の通り。

```
─ `scripts`リポジトリ /
　　　　├─ README.md
　　　　├─ index.php （本体スクリプト）
　　　　├─ _samples /
　　　　│　　　└─ qithub.conf.sample
　　　　│
　　　　├─ system /
　　　　│　　├─ .htaccess
　　　　│　　├─ index.html （ブランクファイル）
　　　　│　　├─ issueXX /
　　　　│　　├─ issueXX /
　　　　│　　├─ ⋯
　　　　│　　⋮
　　　　│
　　　　└─ plugins /
　　　　　　　├─ .htaccess
　　　　　　　├─ index.html （ブランクファイル）
　　　　　　　├─ count-thumbsup /
　　　　　　　│　　　└─ main.py
　　　　　　　├─ say-hello /
　　　　　　　│　　　└─ main.php
　　　　　　　⋮
```

ちなみに、複数の issue が一つのファイルを対象とする可能性が考えられますが、その時ファイル名はどうしましょ？

-----

##### 334040068

2017/10/04 03:24 by KEINOS

👍 

> issue を立てていないと実装・PR できないというのはハードルが高すぎ

そうでした！
１ファイル１issue だとスレッドが長くなるので、**１トラブル１issueであるべき**ですね。

私も「_scripts」は直感的ではないと思ったのですが、下手なレガシーを引き継ごうメンタルが働いていましたね。「_scripts」→「system」の方が直感的で 👍 です。

「plugins」内のプラグイン名は issue番号 でなく「＜DO＞-＜WHAT＞」は良いですね。 これも 👍 

 > 複数の issue が一つのファイルを対象とする可能性が考えられます

先の１機能もしくはプラグイン、１issue であれば回避はできるのですが、総意でそれは 🙅‍♂️  なくなったので、**「system」も「plugins」と同じルールにする**はどうでしょう？両機能で統一できると思うのですが。

```
─ `scripts`リポジトリ /
　　　　├─ README.md
　　　　├─ index.php （本体スクリプト）
　　　　├─ _samples /
　　　　│　　　└─ qithub.conf.sample
　　　　│
　　　　├─ system /
　　　　│　　├─ .htaccess
　　　　│　　├─ index.html （ブランクファイル）
　　　　│　　├─ post-toot /
　　　　│　　│　　　└─ main.php
　　　　│　　├─ reply-toot /
　　　　│　　│　　　└─ main.py
　　　　│　　├─ get-latest-qiita-articles /
　　　　│　　│　　　└─ main.php
　　　　│　　├─ ⋯
　　　　│　　⋮
　　　　│
　　　　└─ plugins /
　　　　　　　├─ .htaccess
　　　　　　　├─ index.html （ブランクファイル）
　　　　　　　├─ count-thumbsup /
　　　　　　　│　　　└─ main.py
　　　　　　　├─ say-hello /
　　　　　　　│　　　└─ main.php
　　　　　　　⋮
```


-----

##### 334042795

2017/10/04 03:49 by hidao80

👍 (・∀・)イイ！

これで行きましょう！

-----

##### 334086193

2017/10/04 08:32 by hidao80

よくよくこのスレッド見てたら、`_scripts` の名前変えるな！ って言ってるの私でしたね…

マージしましたので、issue を閉じます。

-----

##### 338406915

2017/10/21 14:35 by KEINOS

### 提案

PR #28 より利用を始めた`SideCI`ですが、ディレクトリ構成の中に`test`ディレクトリを作成してはどうでしょう？

### 理由

`SideCI` で PHP の[コード解析ツール `phpmd` ](https://qiita.com/rana_kualu/items/097db09f711fe15eddb7)を使う場合で解析ルールのうち除外や特定のルールを指定したい場合、オプションでコードセットのファイルを設置し、その旨を `sideci.yml` ファイルに記述するようです。

よく他のリポジトリで`test`ディレクトリを見かけるのはこれだったんですね。

まだ、コードルールも決まっていませんが、決めるにしても必要になってきそうですし、なんか格好良さそうなので試してみたいです。

-----

##### 338422307

2017/10/21 18:22 by hidao80

👍

-----

##### 340152640

2017/10/28 07:11 by hidao80

test ディレクトリを含む PR をマージした段階で、本 issue を Close で 👌❓

-----

##### 340153202

2017/10/28 07:15 by KEINOS

## 相談

index.php が長くなってきたので、随時処理（クエリにより個別に適宜呼び出せる処理）を外部ファイルに整理したいのですが「includes」ディレクトリを作成して、そちらに処理を分けたいと考えています。

下記のようなイメージですがいかがでしょうか。

```sample.php
<?php
include_once('./includes/constants.inc');
include_once('./includes/functions.inc');

switch (strtolower($_GET['process'])) {
    case 'your_process1':
        include_once( './includes/your_process1.inc' );
        break;
    case 'your_process2':
        include_once( './includes/your_process2.inc' );
        break;
    default:
        echo 'Error: No process specified';
}
```


-----

##### 340154030

2017/10/28 07:19 by KEINOS

PR の Close 確認、ニアミスでしたね！
次回 PR に test を含めますので、マージした時点で Close で 🙏 🙇 。

-----

##### 340157833

2017/10/28 07:53 by hidao80

👍 LGTM 😊 

…と思いきや、index.php って誰やねん、と思ってしまいました。→ main.php のことで 👌❓

個別プラグイン or system 直下の各機能フォルダ内部の構造に関しては、以下の2案からルールを定めたいと思うのですがどうでしょう。

#### 案

1. index.html (内容はブランク) を必須とし、**その他は制限を設けない = 開発者の自由**
2. **制限を設けない = 開発者の自由**

#### 極端なケース

i. プラグイン or システム各機能フォルダ直下に、すべてのファイル (画像等素材込) を並べる。
ii. 複数の多階層のディレクトリを設置する。

Web サーバとの兼ね合いがあるので甲乙つけ難いですが、基本方針として *KISS* なので、「includes」でも「images」でも**好きなだけ、自由に追加できる方針を決めておく**と今後の開発がスムーズになり、参入者にも優しいのではないかと考えます。

-----

##### 340159374

2017/10/28 08:25 by KEINOS

> index.php って誰やねん

説明が足りなかったです、すみません。`index.php` = 本体スクリプトのことです。 😅 

tokenを必要とする機能は`system`、メッセージ内容の生成は`plugins`で、サーバーが対応している限り、これらのプログラム言語は制限しない API の仕様になっており、それらのディレクトリ内は制限を設けない等は問題ないと思います。

それらを各々呼び出して（組み合わせて）処理するための本体スクリプト（PHP）側の分岐が長くなってきたので、別ファイルにしたいのです。（下記「👈」が新規）

### ディレクトリの構成イメージ
```
─ `scripts`リポジトリ /
　　　　├─ README.md
　　　　├─ index.php （本体スクリプト）
　　　　├─ _samples /
　　　　│　　　└─ qithub.conf.sample
　　　　│
　　　　├─ tests / 👈
　　　　│　　　└─ phpmd.json（SideCI用） 👈
　　　　│
　　　　├─ includes / 👈
　　　　│　　├─ index.html （ブランクファイル） 👈
　　　　│　　├─ constants.inc （index.php用） 👈
　　　　│　　├─ functions.inc （index.php用） 👈
　　　　│　　├─ your_process1.inc （index.php用） 👈
　　　　│　　└─ your_process2.inc（index.php用） 👈
　　　　│
　　　　├─ system /
　　　　│　　├─ .htaccess
　　　　│　　├─ index.html （ブランクファイル）
　　　　│　　├─ post-toot /
　　　　│　　│　　　└─ main.php
　　　　│　　├─ reply-toot /
　　　　│　　│　　　└─ main.py
　　　　│　　├─ get-latest-qiita-articles /
　　　　│　　│　　　└─ main.php
　　　　│　　├─ ⋯
　　　　│　　⋮
　　　　│
　　　　└─ plugins /
　　　　　　　├─ .htaccess
　　　　　　　├─ index.html （ブランクファイル）
　　　　　　　├─ count-thumbsup /
　　　　　　　│　　　└─ main.py
　　　　　　　├─ say-hello /
　　　　　　　│　　　└─ main.php
　　　　　　　⋮
```



-----

##### 340160477

2017/10/28 08:48 by hidao80

>本体スクリプト（PHP）側の分岐が長くなってきたので、別ファイルにしたいのです。

よく見たら一番外に `index.php` が。これはうかつでした。😓

なるほど、`system/`、`plugins/`以下を呼び出す BOT のエントリポイントである`index.php`のファイルがデカすぎるということですね。  
これは想定していませんでした。これならば`includes/`は必要だと思います。

…いま開発ブランチをのぞいたら、`index.php`が1,000行超えてるじゃないですか！  
これは気が付かず申し訳ないことです。😅

`lib/`って言うとまた違いますので、`includes/`で 👌👍🙏

-----

##### 340162879

2017/10/28 09:08 by KEINOS

とんでもないです。そうです、ひっそりと居た index です。 😄 

やってる感があって１ファイルで完結したかったのですが、そうも言ってられないサイズになってきました。（と言ってもテスト機能が大半ですが）

とりあえず、今の 新着Qiita記事 のトゥート部分を外部ファイルに吐き出して整理したら PR しますね。他の部分は別 PR であげます。

-----

##### 340163338

2017/10/28 09:13 by KEINOS

あ、Closeしちゃった。 PR あげて Merge の Close でやんした。失礼！
