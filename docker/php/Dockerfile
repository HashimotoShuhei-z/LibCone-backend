FROM php:8.3-fpm

# パッケージの更新と必要なツールのインストール
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
     zlib1g-dev \
     libzip-dev \
     libpq-dev \
     vim \
     mariadb-client \
  && docker-php-ext-install zip pdo_mysql pdo_pgsql \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*


#Composer install
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

ENV COMPOSER_HOME /composer

ENV PATH $PATH:/composer/vendor/bin

COPY ./ /var/www/

RUN chmod -R 777 /var/www/

WORKDIR /var/www


RUN composer global require "laravel/installer"