# ベースステージ
FROM php:8.3-fpm AS base

# 必要パッケージのインストール
RUN apt-get update && apt-get install -y --no-install-recommends \
    zlib1g-dev \
    libzip-dev \
    libpq-dev \
    vim \
    mariadb-client \
  && docker-php-ext-install zip pdo_mysql pdo_pgsql \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

# Composer本体をインストール（composerイメージからコピー）
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer
ENV PATH=$PATH:/composer/vendor/bin

WORKDIR /var/www

# =====================
#  開発用ステージ
# =====================
FROM base AS development

# composer.json / composer.lock を src/ からコピー
COPY src/composer.json src/composer.lock ./

# 開発用パッケージ含むインストール
RUN composer install

# アプリケーションコードを src/ からコピー
COPY src/ /var/www

# ディレクトリ権限の調整
RUN chown -R www-data:www-data /var/www \
 && find /var/www -type f -exec chmod 644 {} \; \
 && find /var/www -type d -exec chmod 755 {} \;

# 開発用に php-fpm を起動する
CMD ["php-fpm"]

# =====================
#  本番用ステージ
# =====================
FROM base AS production

# composer.json / composer.lock を src/ からコピー
COPY src/composer.json src/composer.lock ./

# 本番用（--no-dev）で依存関係をインストール
RUN composer install --no-dev --optimize-autoloader --no-scripts

# アプリケーションコードを src/ からコピー
COPY src/ /var/www

# ディレクトリ権限の調整
RUN chown -R www-data:www-data /var/www \
 && find /var/www -type f -exec chmod 644 {} \; \
 && find /var/www -type d -exec chmod 755 {} \;

# 本番用イメージのエントリーポイント
CMD ["php-fpm"]
