# backend Dockerfile
FROM php:8.1-fpm-alpine

RUN apk add --no-cache tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    echo "Asia/Tokyo" > /etc/timezone && \
    apk del tzdata

RUN apk update && \
    apk add --no-cache \
    busybox-suid \
    dcron \
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

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY .env /var/www/laravel/
COPY .env.testing /var/www/laravel/
COPY . /var/www/laravel/
RUN chmod 666 /var/www/laravel/composer.lock

RUN touch /var/log/cron.log
COPY mycron /etc/cron.d/mycron
RUN chmod 0644 /etc/cron.d/mycron

CMD ["crond", "-f"]

ADD docker-entrypoint.sh ./
RUN chmod +x ./docker-entrypoint.sh
WORKDIR /var/www/laravel/
RUN mkdir -p vendor && chown -R www-data:www-data vendor
USER www-data

RUN composer install --prefer-dist --no-progress --no-interaction


USER root

COPY php.ini /etc/php/8.1/cli/conf.d/99-etc.ini


COPY --chown=www-data:www-data . /var/www/laravel

RUN chown -R www-data:www-data /var/www/laravel/storage
RUN chown -R www-data:www-data /var/www/laravel/bootstrap/cache
CMD ["./docker-entrypoint.sh"]