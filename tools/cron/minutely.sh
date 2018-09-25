#!/bin/bash

# Qithub.tk CRON (１分ごと)
# -----------------------
# Cron で叩きたい URL やスクリプトを記載してください。

# 新着Qiita記事 API
curl -s https://qithub.tk/api/v1/qiita-items/ -o /dev/null
