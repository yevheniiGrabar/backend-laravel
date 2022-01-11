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
    && rm -rf /var/cache/apk/*

RUN apk add --update nodejs npm

RUN docker-php-ext-configure gd && docker-php-ext-install \
    pdo_mysql
