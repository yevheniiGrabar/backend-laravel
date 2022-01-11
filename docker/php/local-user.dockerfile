FROM php:8.0-fpm-alpine

WORKDIR /var/www

RUN apk add --no-cache \
    bash \
    composer \
    gzip \
    zlib-dev \
    libpng-dev \
    libpng \
    libjpeg \
    libcurl \
    openssh-client \
    && rm -rf /var/cache/apk/* \
    && docker-php-ext-configure gd && docker-php-ext-install pdo_mysql

RUN apk add --update nodejs npm

RUN addgroup -g 1000 admin && adduser -S admin -u 1000 -G admin

USER admin
