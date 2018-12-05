# Qiita 魚拓 API（`Qiita-cache API`）

**Qiita 記事のキャッシュサーバー（魚拓） API** です。

この API にリクエストがあると、指定された Qiita 記事 ID のキャッシュが JSON 形式で取得できます。

## 用途＆利用対象者

Qiita のスパム記事のスパム検知エンジン開発などにご利用ください。

この API は Qiita をより良くしたいユーザーのためのサービスです。心無い利用が多いと判断した場合は、Qiita の OAuth を必須とするなど検討いたします。

## スパム・フラグ

出力される JSON データの "is_spam" 要素（値：true/false）はキャッシュ・サーバーが付加したものです。

**これは簡易的なスパム検知**で、キャッシュ時に、ユーザーおよび該当記事が削除されていた場合、スパム記事と見なされフラグが立てられます。

そのため、ユーザー本人が退会および記事の削除を行った可能性もあるためスパムでない可能性もあります。

## エンドポイントとメソッド

### GET `/api/v1/qiita-cache/?id=`

- id
    - 取得したい Qiita 記事の ID です。20 桁の16 進数で表現されています。
    - Example: `"599c4f3b5a25370f8505"`

    ```
    https://qithub.tk/api/v1/qiita-cache/?id=<Qiita記事ID>
    ```

オプションで `&update=true` をリクエスト・クエリに加えるとキャッシュの内容を更新します。（削除済みを除く）

### GET `/api/v1/qiita-cache/?tag=`

- tag
    - URL エンコードされたタグ名です。キャッシュされた情報から最も出現率の多いタグの表記で返されます。スペース（`%20`）区切りで複数タグも指定できます。
    - Example: `"javascript%20TomCAT"`

    ```
    https://qithub.tk/api/v1/qiita-cache/?tag=<URLエンコードのタグ>
    ```

## 禁止事項／禁止事項

- **Qiita をより良くする目的以外での利用は禁止**いたします。
- 同一 IP からの大量アクセスや異常を [`DenyHosts`](https://www.google.co.jp/search?q=site:qiita.com+DenyHosts%E3%81%A8%E3%81%AF&oq=DenyHosts%E3%81%A8%E3%81%AF) が検知した場合、一定時間ブロックされる可能性があります。

## Issue 

**不具合や要望**は下記リンクからお願いいたします。既存の Issue 確認は [issue ページ](https://github.com/Qithub-BOT/Qithub-ORG/issues?utf8=%E2%9C%93&q=is%3Aissue+Qiita-cache) をご覧ください。

[[提案する](https://github.com/Qithub-BOT/Qithub-ORG/issues/new?title=%E3%80%90%E6%8F%90%E6%A1%88%E3%80%91Qiita-cache%20API%20%E3%81%A7%E2%97%8F%E2%97%8F%E2%97%8F%E3%81%97%E3%81%A6%E6%AC%B2%E3%81%97%E3%81%84)] [[相談する](https://github.com/Qithub-BOT/Qithub-ORG/issues/new?title=%E3%80%90%E7%9B%B8%E8%AB%87%E3%80%91Qiita-cache%20API%20%E3%81%A7%E2%97%8F%E2%97%8F%E2%97%8F%E3%81%97%E3%81%9F%E3%81%84)] [[質問する](https://github.com/Qithub-BOT/Qithub-ORG/issues/new?title=%E3%80%90%E8%B3%AA%E5%95%8F%E3%80%91Qiita-cache%20API%20%E3%81%A7%E2%97%8F%E2%97%8F%E2%97%8F%E3%81%99%E3%82%8B%E3%81%AB%E3%81%AF)] [[報告する](https://github.com/Qithub-BOT/Qithub-ORG/issues/new?title=%E3%80%90%E5%A0%B1%E5%91%8A%E3%80%91Qiita-cache%20API%20%E3%81%A7%E2%97%8F%E2%97%8F%E2%97%8F%E3%81%97%E3%81%A6%E3%81%84%E3%81%BE%E3%81%99)]

