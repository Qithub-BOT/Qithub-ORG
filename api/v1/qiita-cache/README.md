# Qiita 記事のキャッシュサーバー（魚拓） API

この API にリクエストがあると、指定された Qiita 記事 ID のキャッシュが JSON 形式で取得できます。

## 用途

Qiita のスパム記事のスパム検知エンジン開発などにご利用ください。

## 禁止事項／禁止事項

- Qiita をより良くする目的以外での利用は禁止いたします。
- 同一 IP からの大量アクセスや異常を [`DenyHosts`](https://www.google.co.jp/search?q=site:qiita.com+DenyHosts%E3%81%A8%E3%81%AF&oq=DenyHosts%E3%81%A8%E3%81%AF) が検知した場合、一定時間ブロックされる可能性があります。

## Issue 

**不具合や要望**は [issue](https://github.com/Qithub-BOT/Qithub-ORG/issues) までお願いいたします。

## GET /api/v1/qiita-cache/

- id
    - 取得したい Qiita 記事の ID です。20 桁の16 進数で表現されています。
    - Example: `"599c4f3b5a25370f8505"`

```
https://qithub.tk/api/v1/qiita-cache/?id=<Qiita記事ID>
```

## 仕様

### 基本動作

- API にアクセスがあると、最後にトゥートした情報を JSON 形式で返します。
- API へのアクセス時、その時間内にトゥートされてない場合は、時報を Qiitadon でトゥートします。

