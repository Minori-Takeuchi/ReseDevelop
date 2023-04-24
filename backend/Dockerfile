# backend Dockerfile
FROM php:8.1-fpm-alpine

RUN apk update && \
    apk add --no-cache \
    busybox-suid \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev && \
    docker-php-ext-install pdo_mysql \
    zip \
    opcache \
    gd && \
    apk add --no-cache $PHPIZE_DEPS && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    rm -rf /tmp/*

COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock /var/www/laravel/

COPY php.ini /etc/php/8.1/cli/conf.d/99-etc.ini

ADD docker-entrypoint.sh ./
RUN chmod +x ./docker-entrypoint.sh

WORKDIR /var/www/laravel
CMD ["./docker-entrypoint.sh"]

