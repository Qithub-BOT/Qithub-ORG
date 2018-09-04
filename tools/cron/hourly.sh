#!/bin/bash

# Qithub.tk CRON (１時間ごと)
# -----------------------
# Cron で叩きたい URL やスクリプトを記載してください。

# QiiTime API
curl -s https://qithub.tk/api/v1/qiitime/ -o /dev/null
