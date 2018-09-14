これは  リポジトリの[ issue をアーカイブ]()したものです。

# Qithub のサーバーに TeX を入れたい

- 2017/12/17 18:47 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 提案

> TeX 数式コードを数式画像に変換するツール：幸せになれる人が増えそう。（ [Issue #79より](https://github.com/Qithub-BOT/scripts/issues/79#issuecomment-352215922)）

色々なツールや対応言語のものがありそうなので選定を行ってからインストールしたいと思います。

情報求む (´･ω･`)ノ

## TL;DR（進捗 2018/05/24現在）

- [MathJax-Node を利用することに](#issuecomment-376417871)
- [x] [npm のインストール](#issuecomment-376873339) [[バージョン確認](http://qithub.tk/api/version/?key=npm&type=program)]
- [x] [mathjax-node のインストール](#issuecomment-376873339) [[パッケージの確認](http://qithub.tk/api/version/?key=npm+package&type=program)]
----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。
- 関連： Qithub-BOT/items/issues/14 「機能拡張 なくてもいいけど、あるとよりよい機能は？」


-----

## コメント

-----

##### 352291319

2017/12/17 22:33 by hidao80

これでサクッと行ければ…

[TeXの数式をPNG, GIFなどの画像に変換 その1](http://minus9d.hatenablog.com/entry/20111224/1324724159)

-----

##### 352325707

2017/12/18 04:52 by KEINOS

CentOS7 + PHP でこう言うのも

「[PHPでTeXをPDF化してくれるサービスを実装してみた](https://qiita.com/inductor/items/6be85d61ea7c47680100)」

-----

##### 352391772

2017/12/18 10:50 by hidao80

PDF では「用紙サイズ」という仕様が壁になりはしないでしょうか。🤔

A5 とかでも Qiitadon にはデカすぎるような。もっと小さいサイズの PDF って作れるんでしょうか？ いまいち詳しくないもので。

-----

##### 352419315

2017/12/18 13:01 by KEINOS

> PDF では「用紙サイズ」という仕様が壁になりはしないでしょうか。🤔

そうか。そもそもトゥートに貼り付ける画像が用途のメイン理由なので、PDF → 画像変換も必要ですし無駄っすね。


-----

##### 376417871

2018/03/27 06:57 by KEINOS

Issue #91 の[コメント](https://github.com/Qithub-BOT/scripts/issues/91#issuecomment-375948604)で「[markdown-it-mathjax](https://github.com/classeur/markdown-it-mathjax)」について言及があったのですが、Node.js で使える Mathjax というものがありました。つまりサーバーサイドの MathJax。

## MathJax-Node
- https://github.com/mathjax/MathJax-node
- 関連情報: [How to create math SVG images by Mathjax-node(Node.js)](http://cartman0.hatenablog.com/entry/2017/05/14/172311)(日本語)

というのも、TeX を CentOS7 に導入するのが意外に面倒で、 ISO イメージで入れたり、日本語対応、フォントなど、また LaTeX 互換の配慮といったコストが発生します。

どうやら [Qiita は MathJax の Javascript ライブラリを利用している](https://qiita.com/PlanetMeron/items/63ac58898541cbe81ada)ようなので、できれば同じにしたいと思いました。

Node.js 自体が慣れていないのですが、どげんとCentOSなのでどうでしょう？



-----

##### 376873339

2018/03/28 12:45 by hidao80

関連情報見ました。良さげですね👍

Node.js自体はパッケージマネージャのnpm（pythonのpipやrubyのgemに相当）がイケてるので、nodejsパッケージをCentOSに突っ込めさえすればそこからは簡単にいけそうな雰囲気です。

1. CentOSにNode.jsをインストールする。参考：https://qiita.com/te2u/items/ee8391842397da381e23
1. Node.jsのパッケージマネージャnpmで、OSにmathjax-nodeをインストール。  
```sudo npm install -g mathjax-node```
1. [関連情報ページ](http://cartman0.hatenablog.com/entry/2017/05/14/172311)のSVG出力プログラムをBOTが起動できる位置に保存。
1. 数式をBOTから流し込めるようにプログラムを修正。
1. プログラムから出力されたSVGをBOTが取り込めるようにする。

って感じでしょうか。

## TeXおよびmarkdown-it-mathjaxの処遇
TeX、markdown-it-mathjaxをインストールするのはやめておきましょう😓

-----

##### 391564462

2018/05/24 02:09 by KEINOS

[MathJax-Node](http://qithub.tk/api/version/?key=npm+package&type=program)のインストールが終わりました。

SVG出力プログラムはインストールしていませんが、BOT側での実装なので、問題なければ close お願いし 💪 

