# 軽量ファイルの暗号化・復号スクリプト

**GitHub 上の公開鍵を使ってファイルを暗号化／ローカルの秘密鍵で復号するシェル・スクリプト**です。

サークル間のメンバーでセンシティブなファイルのやりとりにお使いください。

---

## TL;DR

### 暗号化（Encrypt）

GitHub ユーザに送る**ファイルの暗号化**の仕方。（以下は KEINOS 氏に送る場合）

```
$ ./enc KEINOS himitsu.txt
```

### 復号（Decrypt）

自身の秘密鍵で、届いた**暗号ファイルの復号**の仕方。（GitHub の公開鍵とペアの秘密鍵に限る）

```
$ ./dec ~/.ssh/id_rsa himitsu.txt.enc himitsu.txt
```

### 動作確認（Check）

GitHub 上の公開鍵とローカルの秘密鍵で**暗号化・復号の動作テスト**の仕方。（以下は KEINOS 氏がテストを行う場合）

```
$ ./check KEINOS ~/.ssh/id_rsa
```

### 電子署名（Sign）

自身の秘密鍵で**ファイルの署名**の仕方。（GitHub の公開鍵とペアの秘密鍵に限る）

```
$ ./sign KEINOS ~/.ssh/id_rsa himitsu.txt
```

### 署名の確認（Verify）

署名者の GitHub 公開鍵で署名を検証する。

```
$ ./verify himitsu.txt KEINOS himitsu.txt.sig
```

---

## TS;DR

- いずれのスクリプトも引数がない場合はヘルプが表示されます。（例：`$ ./enc`でヘルプ表示）
- 実行前に、ダウンロードしたスクリプト・ファイルのハッシュ値（`SHA512`）と、チェックサムが同じであることを確認してください。
- 実行権限を与えるのを忘れないでください。（例：`chmod ./enc 0744`）
- ダウンロードしたスクリプト・ファイルを別名保存した場合は、各構文内のスクリプト名も置き換えてください。（`enc.sh`としてダウンロードした場合 `$ ./enc` -> `$ ./enc.sh`）
- 動作確認済み OS と環境はページ下部に記載しています。

---

### 暗号化スクリプト（`enc.sh`）

このシェル・スクリプトは GitHub 上の公開鍵一覧(`https://github.com/<gihub user>.keys`)から一番最初の公開鍵を使い暗号化ファイルを作成します。

#### 構文

```bash
$ ./enc <github user> <input file> [<output file>]
```

##### 引数

- `<github user>`：相手の GitHub アカウント名。（`@KEINOS@GitHub` の場合は `KEINOS`）
- `<input file>` ：暗号化したいファイルのパス。

##### オプション

- `<output file>`：暗号化されたファイルの保存先のパス。指定されていない場合は、同階層に `<input file>.enc` と `.enc` 拡張子を追加して暗号化済みファイルが作成されます。

#### ソース

- [`enc.sh` のソースを見る](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/enc)
- [`enc.sh` のダウンロード](https://qithub.gq/tools/crypt/?type=enc)
- [チェックサム (SHA512)](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/enc.sha512)

---

### 復号スクリプト（`dec.sh`）

このシェル・スクリプトはローカルの秘密鍵を使い暗号ファイルを復号します。

#### 構文

```bash
$ ./dec <private key> <input file> <output file>
```

##### 引数

- `<private key>`：復号に使われる秘密鍵のパス。GitHub 上の公開鍵とペアである必要があります。（例：`~/.ssh/id_rsa`）
- `<input file>`：暗号化されたファイルのパス。
- `<output file>`：復号された／平文化されたファイルの出力先のパス。

#### ソース

- [`dec.sh` のソースを見る](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/dec)
- [`dec.sh` のダウンロード](https://qithub.gq/tools/crypt/?type=dec)
- [チェックサム (SHA512)](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/dec.sha512)

---

### 動作テスト・スクリプト（`check.sh`）

このシェル・スクリプトはカレント・ディレクトリにダミー・ファイルを作成し「暗号化」、「復号」および「比較」のチェックを行います。

#### 構文

```bash
$ ./check <github user> <private key>
```

- `<github user>`：自分の GitHub アカウント名。（`@KEINOS@GitHub` の場合は `KEINOS`）
- `<private key>`：復号に使われる秘密鍵のパス。GitHub 上の公開鍵とペアである必要があります。
- [注意]：暗号化で使われる公開鍵は指定ユーザの公開鍵一覧（`https://github.com/<github user>.keys`）で表示される一番上の公開鍵が使われます。

#### ソース

- [`check.sh` のソースを見る](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/check)
- [`check.sh` のダウンロード](https://qithub.gq/tools/crypt/?type=check)
- [チェックサム (SHA512)](https://github.com/Qithub-BOT/Qithub-ORG/blob/master/tools/crypt/check.sha512)

---

### 署名スクリプト

このシェル・スクリプトは、自分の秘密鍵を使ってファイルの署名を作成します。

#### 構文

```bash
$ ./sign <private key> <input file> [<output file>]
```

- `<private key>`：秘密鍵のパス。署名に使われます。GitHub の公開鍵とペアの秘密鍵である必要があります。
- `<input file>`：署名したいファイルのパス。

##### オプション

- `<output file>`：署名されたファイルの保存先パス。指定されていない場合は、同階層に `<input file>.sig` （`.sig` 拡張子を追加した署名ファイル）が作成されます。

##### 参考文献

- http://blog.livedoor.jp/k_urushima/archives/979220.html

---

### 署名の検証スクリプト

このシェル・スクリプトは、ファイルが正しく署名されたものか検証します。

#### 構文

```bash
$ ./verify <verify file> <github user> [<sign file>]
```

- `<verify file>`：署名を確認したいファイルのパス
- `<github user>`：署名者の GitHub アカウント名

##### オプション

- `<sign file>`：署名されたファイルのパス。指定されていない場合は、同階層にある `<verify file>.sig` （`.sig` 拡張子を追加したファイル）が使用されます。

---

## 注意

- これらのスクリプトは [1 ブロックぶんの暗号化](https://qiita.com/kunichiko/items/3c0b1a2915e9dacbd4c1)しか行わないため**軽量のファイル向け**です。パスワードやハッシュ値といった軽量ファイル向けです。
- 各スクリプトはダウンロード後、（0744などの）実行権限が必要です。

## 動作検証済み環境

1. macOS HighSierra
    - `OSX 10.13.5`（2018/07/13）
    - `$ openssl version` : `LibreSSL 2.2.7`
    - `$ ssh -V` : `OpenSSH_7.6p1, LibreSSL 2.6.2`
    - `$ bash --version` : `GNU bash, version 3.2.57(1)-release (x86_64-apple-darwin17)`

