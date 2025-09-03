#!/bin/bash
# crond 起動（バックグラウンド）
cron -f &
# Apache をフォアグラウンドで起動
exec apache2-foreground
