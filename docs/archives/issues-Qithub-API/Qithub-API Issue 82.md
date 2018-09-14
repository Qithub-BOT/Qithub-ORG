これは  リポジトリの[ issue をアーカイブ]()したものです。

# Qithub のサーバーに Babel を入れたい

- 2017/12/17 18:54 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 提案

> babel：node.js 産トランスパイラ。各種ソースコードを別言語のソースコードに変換するコンパイラ。Qiita API ラッパーを翻訳するのに使える？ （[issue #79より](https://github.com/Qithub-BOT/scripts/issues/79#issuecomment-352215922)）

新サーバーに Babel を入れてプリセットで遊んでみる？

## TL;DR（進捗 2018/05/24 現在）

- [x] 情報のピックアップ
- [x] Node.js のインストール
- [x] Babel のインストール
- [x] PHP プリセットのインストール
- [インストール状況の確認](http://qithub.tk/api/version/?key=npm+package&type=program)

----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。
- 関連 issue #79

-----

## コメント

-----

##### 352276905

2017/12/17 18:55 by KEINOS

### 情報
「[PHP って JavaScript に変換できるの？できるわけないだろ！ babel-preset-php ってのが今日リリースされた？これまさか・・・。ファーーーーーーーーーーーwwwwwwwwwwww](https://qiita.com/kotarella1110/items/064904b3269098938be8)」＠Qiita

-----

##### 352293252

2017/12/17 23:04 by hidao80

babel の行き先はすべて javascript か…

もっと言語間を行き来できるイメージでした。

(´・ω・`)コレジャナイ

-----

##### 352324866

2017/12/18 04:44 by KEINOS

> もっと言語間を行き来できるイメージでした。

確かに、そうなれば凄いんですが。

ただ Electron も Node.js なので Javascript 変換は何かに利用できる気がします。各種プログラム言語のインストールが終わるまで、プライオリティ低めで、しばらくアイデアを練るなり様子をみましょう。


-----

##### 352391373

2017/12/18 10:48 by hidao80

👍

-----

##### 391569565

2018/05/24 02:41 by KEINOS

Babel と PHPプリセットのインストール（いずれもグローバルインストール）が完了しました。
現状問題なければ、おてすきに close お願いし 💪 

## インストール状況の確認
- [イストール済み npm パッケージの一覧](http://qithub.tk/api/version/?key=npm+package&type=program)

## 留意事項

babel のオプションで OS 依存のものが２つ入りませんでした。macOS のローカルで検証する場合は注意が必要かも。

```
$ sudo npm install -g babel-cli
（略）
npm WARN optional SKIPPING OPTIONAL DEPENDENCY: fsevents@^1.0.0 (node_modules/babel-cli/node_modules/chokidar/node_modules/fsevents):
npm WARN notsup SKIPPING OPTIONAL DEPENDENCY: Unsupported platform for fsevents@1.2.4: wanted {"os":"darwin","arch":"any"} (current: {"os":"linux","arch":"x64"})
$
$ sudo npm install -g babel-preset-php
/usr/lib
└─┬ babel-preset-php@1.2.0 
  └── php-parser@2.2.0 
```
