## QiiTime API について

この API は [Qiitadon](https://qiitadon.com/) で時報 BOT「[@​QiiTime​](https://qiitadon.com/@QiiTime)」に時報トゥートを行わせる、もしくは時報トゥートに関する情報を取得するためのものです。

下記「リクエスト URL」にアクセスすると、最後の時報トゥートに関する JSON データを取得できます。

- **不具合や要望**は [issue](https://github.com/Qithub-BOT/Qithub-ORG/issues) までお願いいたします。


## リクエスト URL

以下が QiiTime API へのリクエスト用エンドポイント URL です。

```
https://qithub.tk/api/v1/qiitime/
```

### リクエスト例

```shell
$ curl --version
curl 7.54.0 (x86_64-apple-darwin17.0) libcurl/7.54.0 LibreSSL/2.0.20 zlib/1.2.11 nghttp2/1.24.0
$ 
$ curl https://qithub.tk/api/v1/qiitime/
{
    "threshold": "2018090511",
    "is_cache": true,
    "id": "100670688621666501",
    "uri": "https:\/\/qiitadon.com\/users\/QiiTime\/statuses\/100670688621666501",
    "url": "https:\/\/qiitadon.com\/@QiiTime\/100670688621666501",
    "created_at": "2018-09-05T02:00:02.593Z",
    "requested_at": "2018-09-05T11:14:05.32400Z"
}
```

```shell
$ php --version
PHP 7.2.6 (cli) (built: May 25 2018 06:18:43) ( NTS )
$ 
$ php -r "print_r(file_get_contents('https://qithub.tk/api/v1/qiitime/'));"
{
    "threshold": "2018090511",
    "is_cache": true,
    "id": "100670688621666501",
    "uri": "https:\/\/qiitadon.com\/users\/QiiTime\/statuses\/100670688621666501",
    "url": "https:\/\/qiitadon.com\/@QiiTime\/100670688621666501",
    "created_at": "2018-09-05T02:00:02.593Z",
    "requested_at": "2018-09-05T11:24:51.32400Z"
}
```

## 用途／目的

### TL;DR

ローカルや連合などの**タイムラインには流したくないがタグ検索にヒットさせたいトゥートがある**場合にご利用ください。

なお、[Qithub サークル](https://github.com/Qithub-BOT/Qithub-ORG/)では、「[@​Qithub​(bot)​](https://qiitadon.com/@qithub)」による [Qiita](https://qiita.com/) の新着記事のお知らせなどに利用しています。

### TS;DR

- この**時報トゥートに対して返信トゥートする**ことで、LTL（ローカル・タイム・ライン）には流れないが、タグ検索でヒットするトゥートをすることができます。
- 現在の返信先のトゥート ID（`Status ID`）を知りたい場合は、下記「リクエスト URL」の API を叩くと、JSON 形式で取得できます。
- 時報トゥートには `#2018_08_31_02` といった日時のタグが付きます。
- 詳しい仕様は下記をご覧ください。


## 仕様

### 基本動作

- API にアクセスがあると１時間ごとに時報を Qiitadon でトゥートします。
- API へのアクセスが同時刻内（トゥート済みの１時間以内）の場合は何もしません。
- 上記いずれの場合でも、最後にトゥートした情報を返します。


### `threshold` 値について

- 最後にトゥートした日時を「`YmdH`」フォーマットにした整数を `threshold` と呼んでいます。（例：`2018/09/04 14時`のトゥートの場合、`threshold` は`2018090414`）


### トゥート

- 現時刻が `threshold` の値を超えていない場合は、最後の時報トゥートの情報を返します。
- 現時刻が `threshold` の値が現時刻より大きい場合は、時報をトゥートしてから、最後の時報トゥートの情報を返します。
- 返される時報トゥートのデータは下記「レスポンスについて」をご覧ください。


### レスポンスについて

- レスポンスは JSON データです。
- JSON データは URL などのスラッシュ、またはユニコードなどをエスケープしています。
- レスポンスヘッダの `ETag` は下記 `threshold` を MD5 ハッシュしたものです。


### レスポンス内容

下記 JSON の１次元データが返されます。

|Attribute    |Description                               |Type     |
|:------------- |:-------------------------------------- |:-------:|
| `threshold`   | 'YmdH'形式のタイムスタンプ             | integer |
| `is_cache`    | 時報済みか否か                         | boolean |
| `id`          | 時報トゥート（`status`）の ID          | integer |
| `uri`         | 連合内のユニークなリソース ID          | string  |
| `url`         | 時報トゥートのインスタンス上の URL     | string  |
| `created_at`  | 時報トゥートが作成された時刻           | string  |
| `requested_at`| 時報トゥートが作成された時刻           | string  |


#### レスポンスのサンプル

`2018/09/04 14時`の時報の場合のレスポンス内容の例。

```json
{
    "threshold": "2018090414",
    "is_cache": false,
    "id": "100665815858109827",
    "uri": "https:\/\/qiitadon.com\/users\/QiiTime\/statuses\/100665815858109827",
    "url": "https:\/\/qiitadon.com\/@QiiTime\/100665815858109827",
    "created_at": "2018-09-04T05:20:50.095Z",
    "requested_at": "2018-09-04T14:20:48.32400Z"
}
```
