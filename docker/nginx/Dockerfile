FROM nginx:1.25

# envsubstを利用するためにgettext-baseをインストール
RUN apt-get update && apt-get install -y gettext-base && rm -rf /var/lib/apt/lists/*

# テンプレートとなるdefault.conf.templateをコピーする
COPY docker/nginx/default.conf.template /etc/nginx/conf.d/default.conf.template

# entrypoint.sh をコピーして実行権限を付与する
COPY docker/nginx/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# コンテナ起動時に entrypoint.sh を実行する
ENTRYPOINT ["/entrypoint.sh"]
