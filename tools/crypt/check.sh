#!/usr/bin/env bash

# 軽量ファイルの暗号化・復号確認スクリプト (UTF-8)
# ================================================
#
# ファイルの暗号化と復号が正常にできるか確認します。相手が同じスクリプトを使った
# 場合、正常に復号できるかの確認に利用します。
#
# - 基本動作
# GitHub 上で公開されている（https://github.com/<user name>.keys で取得できる）
# あなたの RSA 公開鍵を使って暗号化されたファイルを、ロカールの秘密鍵で復号/平文
# 化を行い、元データと復号データの比較を行います。
#
# - 使い方の例：
#       $ ./check.sh KEINOS ../.ssh/id_rsa
#
# - 注意：利用前にスクリプトに実行権限を与えるのを忘れないでください。
#

# ヘルプ表示
# ----------
if [[ $# < 2 ]]; then
  echo
  echo "使い方: $0 <github user> <id_rsa>"
  echo
  echo "- <github user> : あなたの GitHub アカウント名"
  echo "- <id_rsa>      : GitHub で公開している公開鍵のペアとなる秘密鍵のパス"
  echo
  exit 1
fi

cd `dirname $0`
clear

# コマンド引数取得
# ----------------
USERNAME=$1
SECRETKEY=$2


# trap の設定
# -----------
# スクリプト終了後サンプルファイルを削除します。
# - 参考URL ： https://qiita.com/m-yamashita/items/889c116b92dc0bf4ea7d
trap "rm -rf ./sample.*" 0


# サンプル・テキストの作成
# ------------------------
SAMPLETEXT=`md5 -q -s $RANDOM`
SAMPLETEXT='hoge'

# サンプル・ファイル名設定
# ------------------------
FILENAME=`md5 -q -s $RANDOM`
PATHFILE="./sample.${FILENAME}.txt"


# サンプル・ファイルの作成
# ------------------------
echo -n "サンプル・ファイルを作成しています ... "
echo $SAMPLETEXT > $PATHFILE

if [[ $? != 0 ]]; then
  echo "NG：サンプル・ファイルの作成に失敗しました。"
  echo "サンプル・ファイル名： ${PATHFILE}"
  exit 1
fi
echo "OK"


# サンプル・ファイルの暗号化
# --------------------------
echo -n "サンプル・ファイルを暗号化しています ... "
RESULT=`./enc.sh ${USERNAME} ${PATHFILE} 2>$1`

if [[ $? != 0 ]]; then
  echo "NG：サンプル・ファイルの暗号化に失敗しました。"
  echo "スクリプトの実行権限、ディレクトリの書き込み権限などを確認ください。"
  exit 1
fi
echo "OK"


# サンプル・ファイルの復号
# ------------------------
echo -n "暗号ファイルを復号しています ... "
RESULT=`./dec.sh ${SECRETKEY} ${PATHFILE}.enc ${PATHFILE}.dec 2>$1`

if [[ $? != 0 ]]; then
  echo "NG：暗号ファイルの復号中にエラーが発生しました。"
  echo "スクリプトの実行権限、ディレクトリの書き込み権限などを確認ください。"
  exit 1
fi
echo "OK"


# サンプル・ファイルの比較
# ------------------------
echo -n "オリジナルと復号ファイルを比較しています ... "

diff $PATHFILE $PATHFILE.dec

if [[ $? != 0 ]]; then
  echo "NG：オリジナルと復号されたファイルが異なります。"
  echo "オリジナル："
  cat $PATHFILE; echo
  echo "復号："
  cat $PATHFILE.dec; echo
  exit 1
fi
echo "OK"


# サンプル・ファイルの削除
# ----------------------
echo -n "サンプル・ファイルの削除中 ... "
rm $PATHFILE
rm $PATHFILE.enc

if [[ $? != 0 ]]; then
  echo "NG：一時ファイルの削除に失敗しました。 手動で削除してください。"
  echo "ファイル名： ${PATHFILE}"
  exit 1
fi
echo "OK"


# 終了表示
# --------
echo
echo "暗号化・復号のテストを終了しました。"
echo "✅ 問題なさそうです。"
echo

