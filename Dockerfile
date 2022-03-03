FROM wyga/php-ext-mysqli:7.4
FROM wyga/php-ext-pdo_mysql:7.4
FROM wyga/php-ext-gd:7.4
FROM wyga/php-ext-zip:7.4
FROM wyga/php-ext-sockets:7.4

FROM wyga/merge:5 AS merge
FROM wyga/php-merge:7.4-fpm

FROM php:7.4-fpm-alpine

RUN apk update && apk upgrade
RUN apk add --no-cache nginx supervisor wget mysql-dev mysql
RUN apk add --no-cache php7-dev php7-pear
RUN apk add --no-cache musl-dev
RUN apk add --no-cache linux-headers
RUN apk add --no-cache $PHPIZE_DEPS
RUN apk add --no-cache gcc autoconf libc-dev libffi-dev make

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && install-php-extensions sockets
RUN pecl install grpc-beta && docker-php-ext-enable grpc
RUN pecl install protobuf && docker-php-ext-enable protobuf

WORKDIR /var/www/html

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /app/docker/supervisord.conf

COPY . .

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /var/www/html && /usr/local/bin/composer install
RUN cd /var/www/html &&/usr/local/bin/composer update

RUN chown -R www-data:www-data /var/www/html

RUN cd /var/www/html && php artisan cache:clear

ENTRYPOINT []

