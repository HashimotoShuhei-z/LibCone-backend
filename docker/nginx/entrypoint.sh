#!/bin/sh
# テンプレートから環境変数を置換して default.conf を生成
envsubst '$FASTCGI_PASS' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

# Nginxをフォアグラウンドで起動する
exec nginx -g 'daemon off;'
