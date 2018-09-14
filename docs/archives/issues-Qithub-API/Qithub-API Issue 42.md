これは  リポジトリの[ issue をアーカイブ]()したものです。

# プラグイン：トゥートだけするプラグインの規制緩和

- 2017/11/05 14:09 by hidao80
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 相談内容

roll-dice プラグインを作って思ったのですが、index.php を変更しないとプラグインが実行できないのがつらいです。

### 関連 issue / PR

issue: #4 , #16 
PR: #38, #39, #40

## 自分の案

index.php へのパラメータ指定だけでプラグインが動くようになれば、もっと気軽に KISS な状態でプラグイン開発ができると思います。

つまり、index.php は、パラメータのパースとプラグインの cli 起動のみをさせ、プログラム言語ごとに起動ロジックを一つしか持たない形が理想です。

またしても「プラグインにどこまで権限委譲するか」がカギになりますね。

----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。

----------------

## TL;DR（進捗 2017/11/18 現在）

- issue #61 と一緒に PR #63 で対応中



-----

## コメント

-----

##### 342008523

2017/11/05 21:47 by KEINOS

👍 

現在はプラグインの動作はURLをトリガーにしていますが、**本質的にはトゥートがトリガーになるべき**なので、仕様をボチボチ固めないとなと思ってたので、良い機会だと思います。

PR #39 の[コメント](https://github.com/Qithub-BOT/scripts/pull/39#issue-271274830)も加味すると、以下ような方向かしら。

### クエリからのプラグイン呼び出し
```
https://blog.keinos.com/qithub/?plugin={$name_plugin}
```

### プラグイン実行用トゥートのフォーマット
```
＠qithub #{$name_plugin}[:{$param1}[:{$param2}[: ... ]]]
```

#### 例１
パラメーターが１つの場合のサンプル。
```
＠qithub #roll_dice:1b6
```

#### 例２
パラメーターが２つある場合のサンプル。
```
＠qithub #memo:save:これはメモです
```

#### 例３
プラグインの使い方（ヘルプ）を知りたい場合のサンプル。パラメーターが指定されていない場合は、README.mdの内容を返信トゥートする。（ ∴ README.md は500文字内でないといけない？）
```
＠qithub #roll_dice
```




-----

##### 342008776

2017/11/05 21:51 by KEINOS

むしろ、この issue では規制緩和に話を絞って、プラグイン実行のトゥートの仕様は別 issue の方がいいかもしれません。

規制緩和に 👍 

-----

##### 342014775

2017/11/05 23:25 by hidao80

具体的なコードの提示がいりますね。

ちょっと考えてみます。

-----

##### 342036193

2017/11/06 03:17 by KEINOS

> index.php へのパラメータ指定だけでプラグインが動くようになれば、もっと気軽に KISS な状態でプラグイン開発ができると思います。

セキュリティは一旦置いておき、挙動自体ですが以下のようなイメージでしょうか。

### sample1
```index.php
<?php
// index.php (sample1)
include('functions.php.inc');
$result = call_plugin( $_GET );
echo $result;
```

### sample2
```index.php
<?php
// index.php (sample2)
include('functions.php.inc');
if( isset($_GET['plugin'] && ! Empty($_GET['plugin']){
    $name_plugin = $_GET['plugin'];
    $result = call_plugin( $name_plugin, $_GET );
    echo $result;
}
```

-----

##### 342039766

2017/11/06 03:50 by hidao80

非常にザックリとしたイメージでは sample1 のイメージです。👍

後は対応言語が増えない限り、`call_plugin()`に変更を加えずにプラグインが追加できれば最高です😄

-----

##### 342096402

2017/11/06 09:45 by hidao80

複数 main.* という名前のファイルがある時の動作は不定。

例） 
```
plugins/
    └pluginA/
          ├main.php
          ├main.py
          ：
```

### sample A

```php
<?php
define('PLUGIN_DIR', './plugins/');
define('MAIN_FILE_GLOB', 'main.*');
define('CMD_PHP', 'php -f ');
define('CMD_PYTHON', 'python ');

toot(call_plugin($_GET));
die();

/* ======================
     $params = $_GET
   ======================*/
function call_plugin($params) {
    $plugin_path = PLUGIN_DIR.$params['plugin_name'].'/';
    $main_files = glob($plugin_path.MAIN_FILE_GLOB);
    $main_file = $main_files[0];
    unset($params['plugin_name']);
    switch ($main_file) {
        case 'main.php':
            $cmd = CMD_PHP;
            break;
        case 'main.py':
            $cmd = CMD_PYTHON;
            break;
    }
    $args = implode(' ', $params);
    return shell_exec("${cmd} ${main_file} ${args}"); // $args の順序は URL と同じ？ 要調査
}
```

-----

##### 342104711

2017/11/06 10:17 by KEINOS

なるほど。上記の例だと**同じ機能を別の言語でも実装できる**（複数 main.xxx がある）ので面白いと思います。言語の比較もできて勉強にも良いですね。

その場合、注釈にもあるように sample A だと優先順位が決まってしまうので、のちのちクエリのルール決めが必要ですね。

まずは、この路線で進めてみたいと思います！

次回 PR「issue 最終回 #42 DEVELOPとDEPLOYの違いはセックスマシーンか否か」の巻き

![developper_jamesbrown](https://user-images.githubusercontent.com/11840938/32435992-f402dff2-c325-11e7-9e90-3ebe37db8ab3.gif)



-----

##### 342114118

2017/11/06 10:54 by hidao80

sample A では、**実行される main ファイルは常に一つ（1言語）だけで、プラグインや URL パラメータからは別言語の main ファイルを選択できない**のではないか、と表現したつもりでした。  
なにかひらめかせたのなら、これ幸い。

ちなみに、`$args`に入るのは、`$_GET`配列から`$_GET['plugin_name']`項目を取り除いたものです。  
roll-dice で例えると

```
https://blog.keinos.com/qithub/?plugin_name=roll-dice&dicecode=10d6
```

```
$_GET === [
    'plugin_name' => 'roll-dice',  // process の代わりのパラメータ。process としても良い
    'dicecode' => '10d6'
];

$args === [
    'dicecode' => '10d6'
];
```

のイメージです。

>次回 PR「issue 最終回 #42 DEVELOPとDEPLOYの違いはセックスマシーンか否か」の巻き

完全に打ち切られてる話数じゃないですか！😅

-----

##### 342709658

2017/11/08 04:45 by KEINOS

>> 次回 PR「issue 最終回 #42 DEVELOPとDEPLOYの違いはセックスマシーンか否か」の巻き
>>
> 完全に打ち切られてる話数じゃないですか！😅
>

やはり、キワどい単語を連呼していたのがマズかったようです。セカンド・シーズンに期待しましょう。

-----

##### 345540122

2017/11/19 18:51 by KEINOS

以下のクエリのフォーマットで直接プラグインを実行できるようにしました。（ PR #63 で実装）

### 基本URLリクエスト

'plugins/' ディレクトリ下のプラグイン・ディレクトリ名でプラグインを指定します。

    qithub/?plugin_name=<プラグイン名>

#### 例：
- プラグイン設置先：` /plugins/say-hello-world/`
- リクエストクエリ：`qithub/?plugin_name=say-hello-world`

### プラグインへの引数

URL の GET クエリの**全ての内容がプラグインの標準入力の第１引数に渡されます**。（QithubAPIフォーマット）

#### 例：
- リクエストクエリ：`qithub/?plugin_name=say-hello-world&hoge=hoge&foo=bar`
- 渡されるJSON　 ：`{ "plugin_name": "say-hello-world", "hoge": "hoge", "foo": "bar" }`
- 第１引数の値 　  ：`%7B%0A%20%20%20%20%22plugin_name%22%3A%20%22say-hello-world%22%2C%0A%20%20%20%20%22hoge%22%3A%20%22hoge%22%2C%0A%20%20%20%20%22foo%22%3A%20%22bar%22%0A%7D`（上記のJSONをURLエンコードしたもの=QithubAPIフォーマット）

### 複数のプログラム言語（`main.xxx`）がある場合

実行したい言語の拡張子を指定します。`main.xxx`が１つの場合は指定不要。

    qithub/?plugin_name=<プラグイン名>&pg_lang=<プログラム言語の拡張子>

例：`qithub/?plugin_name=say-hello-world&pg_lang=go`

### 対応する言語

以下のプログラム言語を利用できるようにしました。

- PHP
- Python
- Go
- Ruby
- Perl

#### 各言語の動作サンプル（DEV環境先）

プラグイン`say-hello-world`内に上記の言語のサンプルを設置しました。（複数言語の動作）

ただし、PHP以外は Qithub API の仕様（JSONをURLエンコードしたもの）で返すようにしていないので、下記サンプルの「RAW」内容をご覧ください。ゆくゆくは、それぞれの言語も Qithub API に準拠させたいと思います。

##### PHP
https://blog.keinos.com/qithub_dev/?plugin_name=say-hello-world&pg_lang=php

##### Go
https://blog.keinos.com/qithub_dev/?plugin_name=say-hello-world&pg_lang=go

##### Python
https://blog.keinos.com/qithub_dev/?plugin_name=say-hello-world&pg_lang=py

##### Ruby
https://blog.keinos.com/qithub_dev/?plugin_name=say-hello-world&pg_lang=rb

##### Perl
https://blog.keinos.com/qithub_dev/?plugin_name=say-hello-world&pg_lang=pl

#### その他のプラグインのサンプル

##### `roll-dice`のサンプル

https://blog.keinos.com/qithub_dev/?plugin_name=roll-dice&dice_code=3d6&use_emoji=yes


-----

##### 345548435

2017/11/19 20:49 by hidao80

💪LGTM🤩

すばらしい！ API の実装のみならず、サンプルコードとその挙動のチェックライトまで実装してもらえるとは！

これでバリバリプラグインが作れますね！

大々的にオープンにできる日も近い？😁
