これは  リポジトリの[ issue をアーカイブ]()したものです。

# #84 implement help and version 20171222 1652

- 2018/01/17 05:05 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

新サーバー移行前に、変に細かい作り込みを初めてしまったので、シンプルなうちに PR。

## TESTS
下記から `&mode=debug` を外せば Qithub エンコードで取得できます。
- `＠qithub:help` https://blog.keinos.com/qithub_dev/?plugin_name=help&args=&mode=debug

- `＠qithub:help —help` https://blog.keinos.com/qithub_dev/?plugin_name=help&args=--help&mode=debug
- `＠qithub:help —version` https://blog.keinos.com/qithub_dev/?plugin_name=help&args=--version&mode=debug
- `＠qithub:help —extensions` https://blog.keinos.com/qithub_dev/?plugin_name=help&args=--extensions&mode=debug
- `＠qithub:help —plugins` https://blog.keinos.com/qithub_dev/?plugin_name=help&args=--plugins&mode=debug

-----

## コメント

-----

##### 358219534

2018/01/17 07:24 by hidao80

LGTM👍😄

微調整お疲れ様です！

念のため、もう少しブランチの削除を待ってみます。

-----

##### 358220998

2018/01/17 07:33 by KEINOS

デプロイしましたー。問題なければ、ブランチの削除お願いし 💪 

## TESTS @ DEPLOY SRV

下記から `&mode=debug` を外せば Qithub エンコードで取得できます。

- `＠qithub:help` https://blog.keinos.com/qithub/?plugin_name=help&args=&mode=debug

- `＠qithub:help —help` https://blog.keinos.com/qithub/?plugin_name=help&args=--help&mode=debug
- `＠qithub:help —version` https://blog.keinos.com/qithub/?plugin_name=help&args=--version&mode=debug
- `＠qithub:help —extensions` https://blog.keinos.com/qithub/?plugin_name=help&args=--extensions&mode=debug
- `＠qithub:help —plugins` https://blog.keinos.com/qithub/?plugin_name=help&args=--plugins&mode=debug
