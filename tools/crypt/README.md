## 軽量ファイルの暗号化・復号スクリプト

**GitHub 上の公開鍵を使ってファイルを暗号化／復号するスクリプト**です。

サークル間のメンバーでセンシティブなファイルのやりとりにお使いください。


### 暗号化スクリプト（`enc.sh`）

```bash
$ ./enc KEINOS ./himitsu.txt
```

上記コマンドは [`@KEINOS@GitHub` の公開鍵一覧](https://github.com/KEINOS.keys)から一番最初の公開鍵を使い `himitsu.txt` を暗号化し `himitsu.txt.enc` ファイルを作成します。

#### 構文

```bash
$ ./enc <github user> <input file> [<output file>]
```

#### ソース

- [`enc.sh` のソースを見る](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/enc.sh)
- [`enc.sh` のダウンロード](https://qithub.tk/tools/crypt/?type=enc)
- [Checksum (SHA512)](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/enc.sh.sig)

### 復号スクリプト（`dec.sh`）

```bash
$ ./dec ../.ssh/id_rsa ./himitsu.txt.enc ./himitsu.txt
```

上記コマンドはローカルの秘密鍵（`id_rsa`）で `himitsu.txt` を暗号化し `himitsu.txt.enc` ファイルを作成します。

#### 構文

```bash
$ ./dec <secret key> <input file> <output file>
```

#### ソース

- [`dec.sh` のソースを見る](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/dec.sh)
- [`dec.sh` のダウンロード](https://qithub.tk/tools/crypt/?type=dec)
- [Checksum (SHA512)](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/dec.sh.sig)

### 動作テスト・スクリプト（`check.sh`）

```bash
$ ./check KEINOS ../.ssh/id_rsa
```

上記コマンドはカレント・ディレクトリにダミー・ファイルを作成し暗号化・復号と比較のチェックを行います。

第１引数のユーザ名は自分の GitHub アカウント、第２引数は公開鍵とペアの秘密鍵のパスを指定してください。

暗号化で使われる公開鍵は指定ユーザの公開鍵一覧（`https://github.com/<github user>.keys`）で表示される一番上の公開鍵が使われます。

#### 構文

```bash
$ ./check <github user> <secret key>
```

#### ソース

- [`check.sh` のソースを見る](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/check.sh)
- [`check.sh` のダウンロード](https://qithub.tk/tools/crypt/?type=check)
- [Checksum (SHA512)](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/check.sh.sig)

## 注意

- このスクリプトは [1 ブロックぶんの暗号化](https://qiita.com/kunichiko/items/3c0b1a2915e9dacbd4c1)しか行わないため**軽量のファイル向け**です。パスワードやハッシュ値といった軽量ファイル向けです。
- 各スクリプトはダウンロード後、（0744などの）実行権限が必要です。

## 動作検証済み環境

1. macOS HighSierra
    - `OSX 10.13.5`
    - `$ openssl version` : `LibreSSL 2.2.7`
    - `$ ssh -V` : `OpenSSH_7.6p1, LibreSSL 2.6.2`
    - `$ bash --version` : `GNU bash, version 3.2.57(1)-release (x86_64-apple-darwin17)`

