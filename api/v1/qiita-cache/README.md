# Qiita 魚拓 API（`Qiita-cache API`）

**Qiita 記事のキャッシュサーバー（魚拓） API** です。

```
https://qithub.gq/api/v1/qiita-cache/
```

この API にリクエストがあると、指定された Qiita 記事 ID のキャッシュ情報が JSON 形式で取得できます。

## 用途＆利用対象者

Qiita のスパム記事のスパム検知エンジン開発などにご利用ください。

この API は Qiita をより良くしたいユーザーのためのサービスです。心無い利用が多いと判断した場合は、Qiita の OAuth を必須とするなど検討いたします。

## エンドポイントとメソッド

### 記事取得

#### GET `/api/v1/qiita-cache/?id=`

キャッシュされた Qiita 記事の情報が取得できます。

- クエリ引数： `id` （必須）
    - 取得したい Qiita 記事の ID を指定します。20 桁の16 進数で表現されています。
        ```
        https://qithub.gq/api/v1/qiita-cache/?id=<Qiita記事ID>
        ```

    - Example: `"599c4f3b5a25370f8505"`
        ```
        https://qithub.gq/api/v1/qiita-cache/?id=599c4f3b5a25370f8505
        ```
 - クエリ引数： `update` （オプション）
    - 値が指定されていると最新の記事情報が取得できます。（キャッシュの内容も更新されます）
    - 値が空白、もしくは記事がすでに削除されている場合は更新しません。

    - Example:
        ```
        https://qithub.gq/api/v1/qiita-cache/?id=599c4f3b5a25370f8505&update=foo
        ```

#### JSON のフォーマット

返される JSON は、本家 Qiita の API の JSON 仕様に、スパム・フラグの要素を加えたものです。

- [Qiita API v2 投稿](https://qiita.com/api/v2/docs#%E6%8A%95%E7%A8%BF) @ Qiita
- スパム・フラグ
    - 要素名："`is_spam`"、型：`string`、値：`true`/`false`
    - **これは簡易的なスパム検知**です。キャッシュ時に、ユーザーおよび該当記事が削除されていた場合、スパム記事と見なされフラグが立てられます。そのため、ユーザー本人が退会および記事の削除を行った可能性もあるためスパムでない可能性もあります。

### タグ取得

#### GET `/api/v1/qiita-cache/?tag=`

キャッシュされた Qiita 記事で**最も使われているタグの表記方法を取得**できます。

キャッシュ記事や Qiita API にも検索タグが存在しない／使われたことがない場合は、デフォルトで検索タグが返されます。

- クエリ引数： `tag` （必須）
    - 値
        - URL エンコードされたタグ名です。スペース（`%20`）区切りで複数タグも指定できます。

        ```
        https://qithub.gq/api/v1/qiita-cache/?tag=<URLエンコードのタグ>
        ```

    - Example: `"javascript%20TomCAT"`

        ```
        https://qithub.gq/api/v1/qiita-cache/?tag=javascript%20TomCAT%20JerryMOUSE
        ```
        ```json
        [
            "javascript": "JavaScript",
            "TomCAT": "Tomcat",
            "JerryMOUSE": "JerryMOUSE"
        ]
        ```

- クエリ引数： `return_value` （オプション）
    - 値
        - `self_if_not_used` （デフォルト）
            - タグが存在しない場合、検索タグ自身を返します。
        - `only_used`
            - タグが存在しない場合、空の値を返します。
        - 値が指定されていない場合は、デフォルト値が使われます。
    - Example:
        ```
        https://qithub.gq/api/v1/qiita-cache/?tag=javascript%20TomCAT%20JerryMOUSE&return_value=only_used
        ```
        ```json
        [
            "javascript": "JavaScript",
            "TomCAT": "",
            "JerryMOUSE": ""
        ]
        ```


## 禁止事項／禁止事項

- **Qiita をより良くする目的以外での利用は禁止**いたします。
- 同一 IP からの大量アクセスや異常を [`DenyHosts`](https://www.google.co.jp/search?q=site:qiita.com+DenyHosts%E3%81%A8%E3%81%AF&oq=DenyHosts%E3%81%A8%E3%81%AF) が検知した場合、一定時間ブロックされる可能性があります。

## Issue 

**不具合や要望**は下記リンクからお願いいたします。既存の Issue 確認は [issue ページ](https://github.com/Qithub-BOT/Qithub-ORG/issues?utf8=%E2%9C%93&q=is%3Aissue+Qiita-cache) をご覧ください。

[[提案する](https://github.com/Qithub-BOT/Qithub-ORG/issues/new?title=%E3%80%90%E6%8F%90%E6%A1%88%E3%80%91Qiita-cache%20API%20%E3%81%A7%E2%97%8F%E2%97%8F%E2%97%8F%E3%81%97%E3%81%A6%E6%AC%B2%E3%81%97%E3%81%84)] [[相談する](https://github.com/Qithub-BOT/Qithub-ORG/issues/new?title=%E3%80%90%E7%9B%B8%E8%AB%87%E3%80%91Qiita-cache%20API%20%E3%81%A7%E2%97%8F%E2%97%8F%E2%97%8F%E3%81%97%E3%81%9F%E3%81%84)] [[質問する](https://github.com/Qithub-BOT/Qithub-ORG/issues/new?title=%E3%80%90%E8%B3%AA%E5%95%8F%E3%80%91Qiita-cache%20API%20%E3%81%A7%E2%97%8F%E2%97%8F%E2%97%8F%E3%81%99%E3%82%8B%E3%81%AB%E3%81%AF)] [[報告する](https://github.com/Qithub-BOT/Qithub-ORG/issues/new?title=%E3%80%90%E5%A0%B1%E5%91%8A%E3%80%91Qiita-cache%20API%20%E3%81%A7%E2%97%8F%E2%97%8F%E2%97%8F%E3%81%97%E3%81%A6%E3%81%84%E3%81%BE%E3%81%99)]

