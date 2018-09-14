これは  リポジトリの[ issue をアーカイブ]()したものです。

# roll-dice plugin 作ってみた。（旧 Dice roll）

- 2017/11/05 09:59 by hidao80
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

## 対象トピック番号

Qithub-BOT/items#14

## 補足

鬼軍曹として iPad Pro + cloud9 だけでコーディング & テストしました。現状は、

- BOT のプラグイン実行環境完成待ち。
- plugin「say-hello-world」をコピってきたので、インタフェースはほぼ同じ。
- PR 時、index.php を修正していないので、このプラグインを BOT から実行することができない。

ご意見、コミットお待ちして💪。

たまには目先を変えると息抜きになるかも？

## TL;DR（進捗・結論 2017/11/05 現在）

- BOT本体（index.php）に随時処理として組み込み。以下のURLクエリで実行可能
    - DEV環境： https://blog.keinos.com/qithub_dev/?process=dice-roll
    - デプロイ先： https://blog.keinos.com/qithub/?process=dice-roll 
    - オプションでサイコロの最大出目や振る回数を変更可能。詳しくはプラグインを参照


-----

## コメント

-----

##### 341961734

2017/11/05 10:05 by hidao80

@KEINOS さんにリアルタイムで看破されたのかと思って戦慄した。😅

これが Side CI か…っ。

-----

##### 341966807

2017/11/05 11:41 by KEINOS

自分でもビビりました。「お、おれ、、なに勝手にブッた斬ってんの！」という感じ 😅 

index.php の随時処理に組み込んでコミットしてみまーす。


-----

##### 341967053

2017/11/05 11:45 by KEINOS

動いた！楽しい！
以下、DEV環境の動作テストです。よろしければマージで！
- 標準テスト<br>https://blog.keinos.com/qithub_dev/?process=dice-roll
- 3回振る（3d6）<br>https://blog.keinos.com/qithub_dev/?process=dice-roll&times=3
- 5面サイコロを10回振る（10d5）<br>https://blog.keinos.com/qithub_dev/?process=dice-roll&times=10&max=5
- デバッグモード（10d6）<br>https://blog.keinos.com/qithub_dev/?process=dice-roll&times=10&max=6&mode=debug

-----

##### 341970085

2017/11/05 12:39 by KEINOS

デプロイしました！

- 標準テスト<br>https://blog.keinos.com/qithub/?process=dice-roll
- 3回振る（3d6）<br>https://blog.keinos.com/qithub/?process=dice-roll&times=3
- 5面サイコロを10回振る（10d5）<br>https://blog.keinos.com/qithub/?process=dice-roll&times=10&max=5
- デバッグモード（10d6）<br>https://blog.keinos.com/qithub/?process=dice-roll&times=10&max=6&mode=debug

