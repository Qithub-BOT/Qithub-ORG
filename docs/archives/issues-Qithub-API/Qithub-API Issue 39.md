これは  リポジトリの[ issue をアーカイブ]()したものです。

# roll-dice plugin 元々の意図

- 2017/11/05 13:01 by hidao80
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

元々の意図は、`?process=roll-dice&dice_code=2d6` でした。

ダイスコードが一般的でないのは承知の上です。トゥートをトリガーとした時、

```
#roll-dice 2d6
```

とかで呼び出せるとシンプルかつ TRPG ユーザ受けしそうと思っていました。

## 対象トピック番号

#38 , Qithub-BOT/items#14

## 補足

`&times=n&max=m` のインタフェースも残しています。

## TL;DR（進捗・結論 2017/11/06 現在）

- dice_code パラメータが有効になりました。
- 既存の times と max も有効のままです。
- "dice_role"を"role_dice"に統一しました。（#43）

-----

## コメント

-----

##### 341971911

2017/11/05 13:07 by KEINOS

> 元々の意図は、`?process=dice-roll&dicecode=2d6` でした。

あー、なるほどー！これは失礼しました！

先ほど PR #40 をあげてしまったので、この PR をマージしてから、差分をコミットしまーす。


-----

##### 341972163

2017/11/05 13:12 by hidao80

#40 確認しました。

本 PR とマージするだけでしたらマージ 👌😎🙏。
