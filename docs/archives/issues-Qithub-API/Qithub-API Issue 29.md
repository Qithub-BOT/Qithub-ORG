これは  リポジトリの[ issue をアーカイブ]()したものです。

# Mastodonユーザーアカウント情報とフォロワーを取得する機能を実装

- 2017/10/17 06:54 by KEINOS
- State: closed
- Archive of https://api.github.com/repos/Qithub-BOT/Qithub-API

## 本文

インスタンスが Qiitadon であった場合は、QiitaアカウントのIDもわかるので、QiitaアカウントIDにGitHubアカウントが紐づいていたら、それらもわかりそう。

∴ QiitadonフォロワーからGitHubのプルリク者をある程度の自動認識はできそう

-----

## コメント

-----

##### 337136109

2017/10/17 06:56 by KEINOS

PHP_CodeSniffer detected 14 issues on 98a406d52f435203a6c2981deb418422e9e7bd62
Visit https://sideci.com/gh/104321470/pull_requests/29 to review the issues.


<!-- Comment by [SideCI](https://sideci.com) -->

-----

##### 337139817

2017/10/17 07:14 by KEINOS

OMG 😨 

`@SuppressWarnings`ってどうやるのーん

----

- PHPMDのルール一覧 @ Qiita<br>https://qiita.com/rana_kualu/items/097db09f711fe15eddb7#developmentcodefragment

-----

##### 337143512

2017/10/17 07:31 by hidao80

👍

SideCI って面白！😆

-----

##### 337147812

2017/10/17 07:50 by hidao80

こう書くと良いかも。

フラッシュアイディーあっ！(ルストハリケーン！のふしで)

```
/**
 * @SuppressWarnings
*/
function dump($var){
    var_dump($var);
}
```

-----

##### 337147835

2017/10/17 07:50 by KEINOS

なんか、スッゲーできる空想の自分に叱られてるみたい。 😆 


-----

##### 338419629

2017/10/21 17:39 by KEINOS

`@SuppressWarnings`は`@SuppressWarnings(PHPMD)`の記述で有効になりました。

```
/**
 * @SuppressWarnings(PHPMD)
 */
```
※ 参考文献：[PHPMD - PHP Mess Detector](https://phpmd.org/documentation/suppress-warnings.html)

とりあえず、 デバッグ用関数（print_r,echo,die）の利用をしている関数に`@SuppressWarnings`を当て、その他のSideCIのコードレビューのエラーも修正しました。

ただ、定数・関数・クラスなどの宣言は別ファイルでしないといけないらしいのですが、ファイル名のルールを固めてから分割したいため、現在は１ファイルにまとめておくため、それらのエラーは強制Closeさせています。（次回のブルリクで修正予定）

取り急ぎ問題なければ マージ & Close お願いします。or  😎 ❓ 
