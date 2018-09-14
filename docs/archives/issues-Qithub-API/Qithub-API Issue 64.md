これは  リポジトリの[ issue をアーカイブ]()したものです。

# プラグイン：トゥートからの呼び出しと返信トゥート

- 2017/11/20 11:11 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 提案

いささか勇み足かもしれませんが、 Qiitadon で Qithub にメンションするとプラグインの実行＆返信トゥートができるといいなと思います。

## 自分の案

Itemsの「[機能拡張 なくてもいいけど、あるとよりよい機能は？](Qithub-BOT/items/issues/14)」にもいくつか挙がっていますが、やはり Qiitan の Slack 連携が参考になると思いました。

<kbd><img width="100%" alt="2017-11-20 19 23 51" src="https://user-images.githubusercontent.com/11840938/33014247-091bebe0-ce2a-11e7-9196-ed265d137252.png"></kbd>
<kbd><img width="100%" alt="2017-11-20 19 23 41" src="https://user-images.githubusercontent.com/11840938/33014261-123119b2-ce2a-11e7-9841-2937439bcb25.png"></kbd>
画像出典元：https://qiita.com/organizations/increments

### Qiitan の基本文法確認と Qithub の文法の相談

Qiitan はメンション直後の「:」から行末（改行コード）までのスペース区切りのようです。

    ＠Qithub:＜プラグイン名＞␣引数1␣引数2␣引数n

しかし、Qithub のプラグインは引数にキーと値がセットになっています。以下のようなコロン区切りが通常なのかも知れませんが煩雑な気がして。シンプルに渡す方法はないでしょうか。

    ＠Qithub:＜プラグイン名＞:引数1キー=値1:引数2=値2:引数n:=値n

## 補足

メンションを検知してプラグインを実行するトリガーですが、できれば `cron` による定期チェック＆実行でなく 'streaming API' でセッションを貼っておいて即答できるといいなと思うのですが、うまく行きません。

----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。

----------------

## TL;DR（進捗・結論 2018/05/24 現在）

- プラグイン → Qithubコマンドに名称変更
- 専用のリポジトリ[Qithub-CMD](https://github.com/Qithub-BOT/Qithub-CMD) を設置
- 決定した仕様は[Wiki](https://github.com/Qithub-BOT/Qithub-CMD/wiki) で確認
- 新規 issue はメイン・リポジトリ [Qithub-ORG の issues](https://github.com/Qithub-BOT/Qithub-ORG/issues]で行うことに

### 決定事項

- トゥート・リクエストはメンション直後のコロン「:」より後から改行までの「**スペース区切り**」の文字列で決まり、"args"の値としてプラグインに渡される。
    - 文法：  `@qithub:{$plugin_name}␣{$args}\n`
- URLリクエストの場合は、同値を"args="パラメーターにURLエスケープして渡す。
    - 文法： `https://sample.com/qithub/?plugin_name={$plugin_name}&args={$args}`

### サンプル

    /* URLリクエスト */
    https://sample.com/qithub/?plugin_name=roll-dice&args=3d6%20yes&mode=debug

    //プラグインに渡されるデータ
    [
        "plugin_name" => "roll-dice",
        "args"        => "3d6␣yes",
        "mode"        => "debug",
    ]
     
    /* トゥート・リクエスト */
    ＠qithub:roll-dice␣3d6␣yes\n

    //プラグインに渡されるデータ
    [
        "plugin_name" => "roll-dice",
        "args"        => "3d6␣yes",
    ]

### CloseまでのToDo

- [ ] プラグインの I/F を新仕様に対応
- [ ] トゥートのメンション取得
- [ ] メンション・トゥートへの返信
- [ ] メンション検知からプラグインの呼び出し

#### Close後のToDo
- cron トリガーから Streaming API からのトリガーへの切り替え

-----

## コメント

-----

##### 345669840

2017/11/20 11:32 by hidao80

やはり、

> ＠Qithub:＜プラグイン名＞␣引数1␣引数2␣引数n

が理想ですね。ですが基本は

> ＠Qithub:＜プラグイン名＞:引数1キー=値1:引数2=値2:引数n:=値n 

としましょう。

## 提案

### 案1

で、**無名変数を指定すると、自動で変数名を割り当てる**と言うのはどうでしょう？

例）

```
@qithub:test-arg 1 a

以下と同値とする

@qithib:test-arg:arg1=1:arg2=a
```

### 案2

変数名を指定する方法は、**URL パラメータを直接利用するのが前提**でしたが、トゥートからオプションを指定するなら思い切って**変数名と言う考え方をやめる**というのも一つの手かと。

最終的に BOT と名乗るからには URL パラメータとの決別があると考えています。（トゥートからのインプットへのシフト）  
ならばいっそ、Slack 式の方が Qiitadon 住民にとって直感的ではないかとも考えます。

-----

##### 345677419

2017/11/20 12:07 by KEINOS

なるほど。では、案1 と 案2 をミックスしたイメージで以下の対案はどうでしょう？

## 対案3

URL パラメーターの１項目をトゥートのインプットと同様にする。

### Roll-diceでのサンプル

#### 従来のリクエスト・クエリ

    https://sample.com/qithub/?plugin_name=roll-dice&dice_code=3d6&use_emoji=yes&mode=debug

    //プラグインに渡されるデータ
    [
        "plugin_name" => "roll-dice",
        "dice_code"   => "3d6",
        "use_emoji"   => "yes",
        "mode"        => "debug"
    ]

#### 新リクエスト・クエリとトゥート・リクエスト

トゥートのインプットを '@qithub:{$plugin_name}:{$args}\n' として $args と同じ内容をクエリの 'args=' パラメーターで受け取る。

    /* URLリクエスト */
    https://sample.com/qithub/?plugin_name=roll-dice&args=3d6%20yes&mode=debug

    //プラグインに渡されるデータ
    [
        "plugin_name" => "roll-dice",
        "args"        => "3d6 yes",
        "mode"        => "debug",
    ]
     
    /* トゥート・リクエスト */
    ＠qithub:roll-dice:3d6 yes\n

    //プラグインに渡されるデータ
    [
        "plugin_name" => "roll-dice",
        "args"        => "3d6 yes",
    ]


-----

##### 345679535

2017/11/20 12:17 by hidao80

👍LGTM

対案3 に賛成です。

オプションすべてが一つの文字列なら、各処理系で呼び出すときのコマンドがよりシンプルに記述できそうですね。

-----

##### 345680326

2017/11/20 12:21 by hidao80

と、思ったけど、**ブラグイン名の後ろのコロンが不要だ**と思い始めたマ〜イレボリュ〜ション。

-----

##### 345680852

2017/11/20 12:23 by KEINOS

そういえばそうですね！ **プラグイン名の後ろのコロンは不要**に 👍 

プラグイン側はテスト動作のクエリ作成が面倒なのと`explode`する手間が増えますが、インターフェースはシンプルで自由度高めになりそうですね！

では、この路線で！

### Close までの ToDo

👉  See TL;DR


-----

##### 345684551

2017/11/20 12:41 by hidao80

👍😄

-----

##### 348081119

2017/11/30 04:43 by KEINOS

### 相談

この仕様の実装や Qiita 記事のデプロイ機能を作成していて感じたのですが、以下のように少し仕様を変更したいです。

> `detail` を追加してコマンド行以外の情報もプラグインに渡せるようにする

```diff
    /* URLリクエスト */
    https://sample.com/qithub/
            ?plugin_name=roll-dice
            &args=3d6%20yes
            &mode=debug
+           &detail=Sample1.%0ASample2.

    //プラグインに渡されるデータ
    [
        "plugin_name" => "roll-dice",
        "args"        => "3d6 yes",
+       "detail"      => "Sample1.\nSample2.",
        "mode"        => "debug",
    ]
     
    /* トゥート・リクエスト */
    ```
    ＠qithub:roll-dice 3d6 yes\n
+   Sample1.\n
+   Sample2.\n
    ```

    //プラグインに渡されるデータ
    [
        "plugin_name" => "roll-dice",
        "args"        => "3d6 yes",
+       "detail"      => "Sample1.\nSampl2.",
    ]
```

#### 理由

> 1. Qiitaコラボ記事の新規記事リクエストに必要
> 1. [他のお遊びプラグイン](https://github.com/Qithub-BOT/items/issues/14)を実装するのに必要

Qithub-BOT/items/issues/13 の新規コラボ記事の**概要を取得するのに必要**なのと、＠Qithub 以外の**メンション先もプラグインで拾える**ようになるため。


-----

##### 348112302

2017/11/30 08:06 by hidao80

大筋で合意ですが、疑問があります。

1. detail の内容とする範囲は？ トゥートの2行目から終端という理解でよい？
2. プラグインへ渡す方法は `$ php roll-dice/main.php args detail` のイメージでよい？
3. args の対象範囲を**最初の改行まで**ではなく、**トゥートの終端まで**にして **detail を使わない**ほうが素直かも。

-----

##### 348295452

2017/11/30 19:31 by KEINOS

> 1. detail の内容とする範囲は？ トゥートの2行目から終端という理解でよい？

そうですね。厳密には`＠qithub`のメンション行の次行から終端です。

> 2. プラグインへ渡す方法は `$ php roll-dice/main.php args detail` のイメージでよい？

プラグインへ渡す仕様自体は変わらず１引数でいいと思いますので、どちらかというと `args` と `detail` を配列に入れた**JSONを渡す `$php roll-dice/main.php json` のようなイメージ**です。

具体的には以下のような感じです。

```sample.php
// プラグインに渡す内容（トゥートから受け取ったデータ）
$array = [
    "plugin_name" => "roll-dice",
    "args"        => "3d6 yes",
    "detail"      => "Sample1.\nSampl2.",
];

// Qithub API フォーマットに変換
$json_raw = json_encode($array);
$json_enc = urlencode($json_raw);

//プラグインへ渡す
$result = `php scripts/roll-dice/main.php $json_enc`;
```

ただし、呼び出し側の `index.php` では、プラグイン関数  `call_plugin()` を用意しているので、実際には以下のようなプラグイン呼び出しがされます。

```index.php
// プラグインに渡すデータ（現在はトゥート受け取りがないため $array = $_GET ）
$array = [
    "plugin_name" => "roll-dice",
    "args"        => "3d6 yes",
    "detail"      => "Sample1.\nSampl2.",
];

// 処理結果を受け取る（フォアグラウンド実行）
$run_background = false;

// プラグインの実行結果
$result = call_plugin( $array, $run_background );
print_r( $result );
```

> 3. args の対象範囲を最初の改行までではなく、トゥートの終端までにして detail を使わないほうが素直かも。

Σ(･ω･ノ)ノ!

```sample.php
$array = [
    "plugin_name" => "roll-dice",
    "args"        => "3d6 yes\nSample1.\nSampl2.",
];
```

３に KISS 👍 ！


-----

##### 348300477

2017/11/30 19:49 by hidao80

引数の使い方が若干特殊ですが（ブラグイン側でパースが必要）、3. のアイデアでいきましょう！😄

-----

##### 391571916

2018/05/24 02:57 by KEINOS

本 issue ですが、Qithub-CMD に引っ越したので close してもいいと思いますが、いかがでしょう？
TL;DR 更新。

問題なければ close お願いし 💪 
