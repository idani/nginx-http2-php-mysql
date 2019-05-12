FROM php:5-fpm-alpine

# timezone
ARG TZ=Asia/Tokyo

# Composer install
RUN set -eux && \
  apk add --update --no-cache --virtual=.build-dependencies \
    autoconf \
    gcc \
    g++ \
    make \
    tzdata && \
  cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
  echo ${TZ} > /etc/timezone && \
  pecl install xdebug-2.5.5 && \
  apk del .build-dependencies && \
  docker-php-ext-install pdo_mysql mysqli mbstring && \
  docker-php-ext-enable xdebug

# Config
COPY ./xdebug.ini /usr/local/etc/php/conf.d/
