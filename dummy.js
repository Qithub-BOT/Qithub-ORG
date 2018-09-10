/*
    このスクリプトは Sider CI の ESLint v5 系で Javascript ファイルが１つも
    ないと他の拡張子のファイルもチェックしてしまう挙動を回避するためのもの
    です。
    Node.js などのコードが発生するようになったら削除してください。

    - 参考 Issue #131 https://github.com/Qithub-BOT/Qithub-ORG/pull/131
*/

function hello(name) {
    document.body.textContent = "Hello, " + name + "!"
}

hello("World");