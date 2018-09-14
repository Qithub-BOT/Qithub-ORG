これは  リポジトリの[ issue をアーカイブ]()したものです。

# #64 トゥートでプログラム実行の実装

- 2018/01/19 19:12 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## トゥートで実行プラグイン「run」

`run` プラグイン呼び出し時に、トゥート（`args`引数）がプログラム言語名から始まった場合で、以降にコードブロックがあった場合、プログラムを実行して返すプラグインを作成しました。
（プラグイン名は `run` でいいのかという問題もありますが）

````
@qithub:run php
おはようございマストドン #今日も元気

```sample.php
<?php
    echo "おはどん";
```

````

実行は Qithub のサーバでなく paiza.IO のような Wandbox というサービスの API を使っています。
（別途 issue 立てますが paiza.IOは引数を自由に渡せないなど制限が多いため）

逆にこのプラグインを利用すれば、 paiza.IO で作ったスクリプトも、中身を Wandbox に投げて動作確認できるかもしれません。

なお、テストデータが作りづらい（エスケープが多い）のでテスト用のクエリのジェネレーターを作成しました。

https://paiza.io/projects/Pu11KFhGpocwvDUeLG5LAA

- [PHPの動作テスト](https://blog.keinos.com/qithub_dev/?plugin_name=run&args=php+--arg1+hoge+--arg2%0AMessages+here+will+be+ignored.%0AOnly+the+first+arg+of+plugin%2C+such+as+%27run%27+here%2C+and+the+code+block+will+be%0Aparsed+to+Wandbox.%0A%0A%60%60%60%0A%3C%3Fphp%0A%0Aecho+%22hello+world%21+from+Qithub%21%22%3B%0A%0A%60%60%60&mode=debug)
- [Goの動作テスト](https://blog.keinos.com/qithub_dev/?plugin_name=run&args=go+--arg1+hoge+--arg2%0AMessages+here+will+be+ignored.%0AOnly+the+first+arg+of+plugin%2C+such+as+%27run%27+here%2C+and+the+code+block+will+be%0Aparsed+to+Wandbox.%0A%0A%60%60%60%0Apackage+main%0A%0Aimport+%28%0A++++%22fmt%22%0A%29%0A%0Afunc+main%28%29+%7B%0A++++fmt.Println%28%22Hello+world+from+Go%21%22%29%0A%7D%0A%0A%60%60%60&mode=debug)

-----

## コメント

-----

##### 359066234

2018/01/19 19:31 by KEINOS

### P.S.
#### ブランチについて
現時点で `Qithub-BOT/scripts` にやはりブランチができていないので、 https://github.com/Qithub-BOT/scripts/pull/89#issuecomment-358773854 のコメントの件の通りのようですね。


-----

##### 359164689

2018/01/20 11:29 by hidao80

おおむね LGTM 👍

### 疑問

複数コードブロックがあった場合、**全てをパースして別々のファイル名 (キー？) で配列に保持しているが、実行しているのは先頭のコードブロックのみ**という実装と理解してもいいでしょうか？

P.S.
コード自体には問題なさそうなので、返事を頂いてからマージし💪。コードレビューしたった感 is ある。

-----

##### 359165807

2018/01/20 11:49 by KEINOS

> 複数コードブロックがあった場合、全てをパースして別々のファイル名 (キー？) で配列に保持しているが、実行しているのは先頭のコードブロックのみという実装と理解してもいいでしょうか？

おぉ、さすが。実はそうなんです！

というのも小生、複数ファイルの送り方がまだよく把握できていないため、現在は先頭のコードブロックのみを Wandbox に送っているでござる。

内部的に保持しているのは paiza.IO のように複数ファイル（タブ）で `include` しても使えるようにするためです。

````
@qithub:php 

```main.php
<?php
include_once('constants.php.inc');
echo HOGE;

```

```constants.php.inc
<?php
const HOGE = 'fuga';
```
````

コメントや PHPDoc などは追い追い。(*´∀`*)ﾃﾍ♪
よろしければ、マージお願いし 💪 

-----

##### 359189032

2018/01/20 17:47 by hidao80

> 現在は先頭のコードブロックのみを Wandbox に送っているでござる。

了解しました。機能の充実は issue を立てるなり、新たな PR なりで対応しましょう！

マージし💪。


-----

##### 359214756

2018/01/21 00:59 by KEINOS

> マージし💪。

ありがとうござい 💪 。昨日の充実は今日の糧なり。
