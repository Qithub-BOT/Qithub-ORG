これは  リポジトリの[ issue をアーカイブ]()したものです。

# プラグイン："roll-dice" で emoji を返せないか（オプション）

- 2017/11/05 13:33 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 提案

PR #38（関連 #39 #40 ）の「Dice Roll」プラグインですが、トゥート用にオプションで絵文字で返せるようにしてはどうでしょう？

## 自分の案

#### API リクエストパラメーター
```
[
    'is_mode_debug" => 0,
    'dicecode' => '3d11',
    'use_emoji' => 'yes',
]
```
#### API レスポンス
```
[
    'result' => 'OK',
    'value' => [
        'role' => [ ':zero:', ':four:', ':one::one:' ],
        'sum' => 15,
    ],
]
````

## TL;DR（進捗 2017/11/11 現在）

プラグインの直接呼び出し（プラグインの規制緩和 #42 ）がまだ実装されていないため、まずはプラグインの emoji 対応を先に実装。

### クエリのパラメーター
- `&process=roll-dice`：必須：プラグイン'roll-dice'を呼び出すプロセスの指定
- `&use_emoji=yes`：オプション：結果を emoji で取得
- `&dice_code=ndm`：オプション：`ndm`値をデフォルトでなく指定値を利用する
- `&mode=debug`：オプション（共通）：詳細な出力

※動作サンプルは下記[コメントを参照](#issuecomment-343502713)

----------------

- [x] 過去の Issues も検索しましたが該当するものはありませんでした。


-----

## コメント

-----

##### 341974559

2017/11/05 13:50 by hidao80

👍いいですね！😁

実装もそれほど複雑ではない気がします（直感で、ですが）。

-----

##### 343364992

2017/11/10 03:29 by hidao80

#47 マージしましたので、クローズします。

-----

##### 343502713

2017/11/10 15:26 by KEINOS

PR #51 で完全動作するようにし、あわせてデプロイもしました。

クエリによるリクエストのパラメーターは TL;DR に記載。以下動作サンプルです。
2017/11/10 の時点でプラグインの規制緩和に対応していません。

## クエリのリクエスト例

### 通常リクエスト
https://blog.keinos.com/qithub/?process=roll-dice&dice_code=2d3

### emojiリクエスト（ダイスコード：デフォルト）
https://blog.keinos.com/qithub/?process=roll-dice&use_emoji=yes

### emojiリクエスト(デバッグ）
https://blog.keinos.com/qithub/?process=roll-dice&use_emoji=yes&dice_code=2d3&mode=debug

-----

##### 343521271

2017/11/10 16:33 by hidao80

👍👏😄
