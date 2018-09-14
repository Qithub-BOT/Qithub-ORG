これは  リポジトリの[ issue をアーカイブ]()したものです。

# GitHub の WebHook 受信・内容保存・内容の確認

- 2017/10/10 17:36 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

WebHook をトリガーにするための、第一弾として、GitHub からの WebHook で以下の動作をするように準備。

1. 受け取りログデータの読み込み
2. WebHookの内容受け取り
3. ログデータに追加・保存

### WebHook URL
https://blog.keinos.com/qithub_dev/?process=github

### ログの確認URL
https://blog.keinos.com/qithub_dev/?process=github&method=view

まずは、このプルリクの WebHook を受け取れるかのテストなので、**マージしないで**ください。
マージOKになりましたら、コメントします。


-----

## コメント

-----

##### 335552957

2017/10/10 17:44 by KEINOS

なんか、、、受け取れなかった 😭 

-----

##### 335554179

2017/10/10 17:49 by KEINOS

これが参考になりそう。GitHub から POST されても `$_POST` に入らない？
https://qiita.com/oyas/items/1cbdc3e0ac35d4316885


-----

##### 336045727

2017/10/12 07:33 by KEINOS

機能としては十分ではありませんが、とりあえず GitHub の WebHook は受け取れるのを確認したので、**マージをお願いします**。

以後は Qithub-BOT/items#52 にあわせて、プルリクせずにブランチをUPを試しつつ続行したいと思います。

-----

##### 336060916

2017/10/12 08:38 by hidao80

👍😊

お疲れ様でした！ マージしたブランチは削除しましたが、同名のブランチで  push しても害はない状態になっていると思います。

別名ブランチが作りにくいようでしたら、そのままのブランチを使って push  してみてください。

-----

##### 336069868

2017/10/12 09:13 by KEINOS

👍 レッツトライですな
