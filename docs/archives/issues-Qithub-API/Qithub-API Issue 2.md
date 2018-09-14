これは  リポジトリの[ issue をアーカイブ]()したものです。

# BOT のコラボ記事（原稿）の保管パスを決めましょう

- 2017/09/25 01:43 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

Qiitaに投稿される元Markdownデータのパスですが、以下の２案を提案します。

## 案１

**"items"リポジトリ直下にQiita記事と同じIDのファイル名がいい**と思います。（ディレクトリの作成は禁止）

■理由
QiitaのURLが`items/<ドキュメントID>`となっているため、直感的な気がします。

````
Qiita-BOTch (Organization)
    ⊢ items (Repository)
    ∣    ⊢ xxxxxxxxxx.md (Raw MD text of Qiita Doc ID xxx..xxx )
    ∣    ⊢ yyyyyyyyyyy.md (Raw MD text of Qiita Doc ID yyy...yyy )
    ∣    ∟ zzzzzzzzzzz.md (Raw MD text of Qiita Doc ID zzz...zzzz )
    ∟scripts (Repository)
         ∟ ??（未定）

````

## 案２

`items`リポジトリ直下はアカウント名にして、Qiitaドメイン以下のURLにあわせる。

■理由
コラボレーターがローカルにクローンした際に階層でイメージがつきやすいと思います。

ただ、ディレクトリを作成されてしまうとBOTに不具合が発生する可能性あり。

````
Qiita-BOTch (Organization)
    ⊢ items (Repository)
    ∣    ∟ BOTch/ 
    ∣        ∟ items/
    ∣            ⊢ xxxxxxxxxx.md (Raw MD text of Qiita Doc ID xxx..xxx )
    ∣            ⊢ yyyyyyyyyyy.md (Raw MD text of Qiita Doc ID yyy...yyy )
    ∣            ∟ zzzzzzzzzzz.md (Raw MD text of Qiita Doc ID zzz...zzzz )
    ∟scripts (Repository)
         ∟ ??（未定）

````


-----

## コメント

-----

##### 331770883

2017/09/25 03:51 by hidao80

案1 に賛成です。

ディレクトリ階層を気にするよりも、「存在しない」方がシンプルで直感的な構造だからです。

-----

##### 331826116

2017/09/25 09:24 by KEINOS

なんか、issue を`Scripts`と`Items`で行ったり来たりで、迷い箸してるみたいですね。

