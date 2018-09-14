これは  リポジトリの[ issue をアーカイブ]()したものです。

# Qithub のサーバーにインストールするプログラム一覧

- 2018/01/20 05:28 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 報告

Qithub の新サーバーにインストールする／した各種アプリケーションの一覧を TL;DR に目次と該当コメント（詳細）や issue へのリンクを記載していきます。（リンク先の詳細は編集で適宜更新してください。その際のTL;DR 追加時タイムスタンプを忘れないでね）

## 自分の案

インストールしたことがないアプリケーションもあり、動作しなかったり、ゴチャゴチャした末に動いても、OSのクリーンインストール（再インストール）時に困るので記録に残して行きたいです。

## TL;DR（進捗 2018/05/24 現在）

- インストール済みプログラム言語およびアプリケーションをリアルタイムに確認できるようにした
http://qithub.tk/api/version/

### インストール済みプログラム一覧

|済み|プログラム名|バージョン|詳細へのリンク|
|:--:  |:---               |:--:            |:---                   |
| ✅| Babel | 6.26.0 | https://github.com/Qithub-BOT/scripts/issues/82 |
| ✅ | MeCab | v0.996 | https://github.com/Qithub-BOT/scripts/issues/91#issuecomment-359147635 |
| ✅ | mecab-python | v0.996 | --- |
| ✅ | pip | v9.0.2 | --- |
| ✅| RipGrep | v0.8.1 | https://github.com/Qithub-BOT/scripts/issues/91#issuecomment-359149770 |
| ✅ | ~~TeX~~ MathJax-Node | 2.1.0 | https://github.com/Qithub-BOT/scripts/issues/81 |
|ペンディング | word2vec | ??? | https://github.com/Qithub-BOT/scripts/issues/91#issuecomment-359149133|

----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。
- 関連 issue #79 「Qithub のサーバー移行とドメイン」
- 関連 issue #80 「Qithub のサーバーにインストールするプログラム言語」

-----

## コメント

-----

##### 359147635

2018/01/20 05:41 by KEINOS

## MeCab
オープンソースの形態素解析エンジン。[[Wikipedia](https://ja.wikipedia.org/wiki/MeCab)]

### 目的
トゥート、Qiita記事、ブログのRSSなどの文書解析で ワカチコ ワカチコ したい。

### 関連インストール
- mecab-ipadic / mecab-ipadic-neologd
- [php−mecab](https://qiita.com/renoinn/items/31cb65190a6fbb2fd4a1#php%E3%81%A7mecab)


-----

##### 359149133

2018/01/20 06:17 by KEINOS

## word2vec

単語の意味を類推させる AI アプリ。

### 何ができるか

文章を投げると単語と単語の結合度（ベクトル）を学習させることができ、「`Ubuntu` と `Linux` の関係を `FreeBSD` に当てはめると `UNIX` になる」といった判定ができるようになる。

```
$ ./distance vector_data.bin
Ubuntu
Linux
FreeBSD
-> UNIX
```

### 目的

Qithub BOT の人口無脳なトゥートに使えるのではないかと。あと、ちょっと温めている Qithub のサービスとしてのアイデアの実現可能性の検証に使いたい。

-----

##### 359149770

2018/01/20 06:30 by KEINOS

## RipGrep（rg）

爆速テキスト grep 検索ツール。

### 目的

BOTのフォロワーが「いいね」（「お気に入り」や「ブースト」）したトゥートをテキスト保存して検索できるようにするため。DBを使うよりシンプルに実装できる。（と思う）

### 関連リンク
- [爆速grep「ripgrep」をラズパイにインストールする【実測値あり】](https://qiita.com/KEINOS/items/9267e6f4e3806f508db0)

-----

##### 375948604

2018/03/25 06:25 by hidao80

## markdown-it-mathjax

Markdown中にLatex記法で数式を埋め込めるjavascriptライブラリ。
<https://github.com/classeur/markdown-it-mathjax>

これがあったらTeXは要らない。

-----

##### 375951880

2018/03/25 07:44 by KEINOS

> markdown-it-mathjax

おぉ、Javascript とな。
トゥートの返信に使うにはどうすればいいのでしょう？Node.js にするのかしら。

Pandoc とかも入れてみます？

-----

##### 375955848

2018/03/25 09:08 by hidao80

おぅ…そうでした、**BOTはトゥート文字列を返す**のであって、レイアウト等を調節したいわばHTML組版まではできない（やったところでQiitadonにサニタイズされる）のでした。😓

markdown-it-mathjax は不要です。どっちかっていうと、Qiitadonに実装してもらいたい😅

-----

##### 375956049

2018/03/25 09:11 by KEINOS

> Qiitadonに実装してもらいたい😅

ほんとに。まぁ、私はTeXはほとんど使わないんですけども 😅


-----

##### 391571045

2018/05/24 02:51 by KEINOS

当リポジトリ Qithub-API の issue 整理でクロール中。

http://qithub.tk/api/version/ で各種インストール済み言語やプログラムがリアルタイムで確認できるようになったので、問題なければ鼻をほじりながら close お願いし 💪 


-----

##### 391575360

2018/05/24 03:20 by hidao80

当座、アクセス制限なしでもいいと思います。

ロボットクローキングだけは拒否しておくといいかもですね。metaタグだけでお行儀の良いクローラは拒否できたはず。

-----

##### 391583142

2018/05/24 04:18 by KEINOS

> 当座、アクセス制限なしでもいいと思います。

ガッテン承知の助 👍 

> ロボットクローキングだけは拒否しておくといいかもですね。metaタグだけでお行儀の良いクローラは拒否できたはず。

設定しました。 → [commit f8272de](https://github.com/Qithub-BOT/Qithub-ORG/commit/f8272de6d2a56e6d3befa652481e6062679ca3bb#diff-08646307e79f36047c0d65e7eb013e27R2)
