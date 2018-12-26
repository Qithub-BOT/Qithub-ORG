#!/bin/bash

# Qithub.tk CRON (１時間ごと)
# -----------------------
# Cron で叩きたい URL やスクリプトを記載してください。

# QiiTime API
curl -s https://qithub.gq/api/v1/qiitime/?update=1 -o /dev/null
