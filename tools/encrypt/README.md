## 軽量ファイルの暗号化・復号スクリプト

**GitHub 上の公開鍵を使ってファイルを暗号化／復号するスクリプト**です。

サークル間のメンバーでセンシティブなファイルのやりとりにお使いください。


### 暗号化スクリプト（`enc.sh`）

```bash
$ ./enc.sh KEINOS ./himitsu.txt
```

上記コマンドは [`@KEINOS@GitHub` の公開鍵](https://github.com/KEINOS.keys)で `himitsu.txt` を暗号化し `himitsu.txt.enc` ファイルを作成します。

#### 構文

```bash
$ ./enc.sh <github user> <input file> [<output file>]
```

#### ソース

- ソースを見る
- ソースをダウンロード

### 復号スクリプト（`dec.sh`）

```bash
$ ./dec.sh ../.ssh/id_rsa ./himitsu.txt.enc ./himitsu.txt
```

上記コマンドはローカルの秘密鍵（`id_rsa`）で `himitsu.txt` を暗号化し `himitsu.txt.enc` ファイルを作成します。

#### 構文

```bash
$ ./dec.sh <secret key> <input file> <output file>
```

#### ソース

- ソースを見る
- ソースをダウンロード

### 動作テスト・スクリプト（`check.sh`）

```bash
$ ./check.sh KEINOS ../.ssh/id_rsa
```

上記コマンドはカレント・ディレクトリにダミー・ファイルを作成し暗号化・復号と比較のチェックを行います。指定したユーザと

#### 構文

```bash
$ ./check.sh <github user> <secret key>
```

#### ソース

- ソースを見る
- ソースをダウンロード

## 注意

- このスクリプトは [1 ブロックぶんの暗号化](https://qiita.com/kunichiko/items/3c0b1a2915e9dacbd4c1)しか行わないため**軽量のファイル向け**です。パスワードやハッシュ値といった軽量ファイル向けです。
- 各スクリプトはダウンロード後、（0744などの）実行権限が必要です。

## 動作検証済み環境

1. macOS HighSierra
    - `OSX 10.13.5`
    - `$ openssl version` : `LibreSSL 2.2.7`
    - `$ ssh -V` : `OpenSSH_7.6p1, LibreSSL 2.6.2`
    - `$ bash --version` : `GNU bash, version 3.2.57(1)-release (x86_64-apple-darwin17)`

