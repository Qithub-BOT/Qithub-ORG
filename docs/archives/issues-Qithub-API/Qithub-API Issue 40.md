これは  リポジトリの[ issue をアーカイブ]()したものです。

# PR #38 “roll-dice” プラグインの出力を拡張（文字列→配列渡し）

- 2017/11/05 13:02 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

PR #38 の “roll-dice”（旧dice-role）プラグインの処理結果を、処理しやすいように結果のみ配列で返し、”Result: x Sum: y”の表示は受け手（index.php）側で処理するようにした。

フォーマットは以下の通り。（’roll’キーが振った結果,’sum’キーが出目の合計）

```
[
    ‘result’ => ‘OK’,
    ‘value’ => [
        ‘roll’ => [x,y,z,,,n],
        ‘sum’ => xx,
    ],
]
```


-----

## コメント

-----

##### 341972501

2017/11/05 13:17 by KEINOS

PR #39 の適用を反映（masterからpull）し、下記 URL で 'dicecode' パラメーターの動作確認しました。

https://blog.keinos.com/qithub_dev/?process=dice-roll&dicecode=3d3


-----

##### 341972516

2017/11/05 13:17 by hidao80

👍

-----

##### 341972813

2017/11/05 13:22 by KEINOS

デプロイしましたー

https://blog.keinos.com/qithub/?process=dice-roll&dicecode=10d6


-----

##### 341972954

2017/11/05 13:25 by hidao80

なにげに README.md の整備もしていただいてますね。😳

ありがとうござい💪❗️😁
