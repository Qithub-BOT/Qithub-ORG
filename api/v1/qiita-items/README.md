# 新着 Qiita 記事取得 API

[新着 Qiita 記事](https://qiita.com/items)を [Qiitadon](https://qiitadon.com/) で[トゥートする BOT](https://qiitadon.com/@qithub/) が、時間内（10 時にリクエストしたら 10 時の間）に投稿した Qiita 記事を JSON 形式の配列で返すだけの API です。

また、リクエスト時に未トゥートの新着記事があった場合は、新着トゥートを行い、それらを含めた JSON 配列が返されます。

## リクエスト URL

`GET` リクエストで下記 URL を叩くと JSON 配列で返されます。

```
https://qithub.gq/api/v1/qiita-items/
```

## API の基本動作

リクエストを受けると以下の動作を行います。


1. 新着確認までの待ち時間[^1]内であった場合、新着は取得せず、その時間にトゥート済みの記事一覧を JSON 形式の配列で返します。
2. 新着確認までの待ち時間[^1]を超えた場合、新着を確認し、トゥート後、トゥート済みの記事一覧を JSON 形式の配列で返します。
3. リセット時間[^2]を超えていた場合は、トゥート済みの一覧はリセットされされます。


[^1]:「**新着確認までの待ち時間**」：現在は 1 分に設定されており、最終トゥートから 1 分以内のリクエストの場合は新着の取得を行いません。
[^2]:「**リセット時間**」：1 時間ごと（毎 n 時 00 分）にリセットされます。

## 返される JSON データ

各項目の詳細については [Qiita API の「投稿」項目](https://qiita.com/api/v2/docs#%E6%8A%95%E7%A8%BF)をご覧ください。

```json
    "<Qiita記事のID>": {
        "coediting": "この投稿が共同更新状態かどうか (Qiita:Teamでのみ有効)",
        "comments_count": "この投稿へのコメントの数",
        "created_at": "データが作成された日時",
        "group": "Qiita:Teamのグループを表します。",
        "id": "投稿の一意なID（Qiita記事のID）",
        "likes_count": "この投稿への「いいね！」の数（Qiitaでのみ有効）",
        "private": "限定共有状態かどうかを表すフラグ (Qiita:Teamでは無効)",
        "reactions_count": "絵文字リアクションの数（Qiita:Teamでのみ有効）",
        "tags": [
            {
                "name": "投稿に付いたタグ名",
                "versions": []
            },
            {
                //以下同文
            }
        ],
        "title": "投稿のタイトル",
        "updated_at": "データが最後に更新された日時",
        "url": "投稿のURL",
        "user": {
            "description": "自己紹介文",
            "facebook_id": "Facebook ID",
            "followees_count": "このユーザがフォローしているユーザの数",
            "followers_count": "このユーザをフォローしているユーザの数",
            "github_login_name": "GitHub ID",
            "id": "ユーザID",
            "items_count": "このユーザが qiita.com 上で公開している投稿の数 (Qiita:Teamでの投稿数は含まれません)",
            "linkedin_id": "LinkedIn ID",
            "location": "居住地",
            "name": "設定している名前",
            "organization": "設定している名前",
            "permanent_id": "ユーザごとに割り当てられる整数のID",
            "profile_image_url": "設定しているプロフィール画像のURL",
            "twitter_screen_name": "Twitterのスクリーンネーム",
            "website_url": "設定しているWebサイトのURL"
        },
        "page_views_count": "閲覧数"
    },
    "<Qiita記事のID>": {
        //以下同文
    }
}
```
