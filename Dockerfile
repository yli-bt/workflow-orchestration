FROM php:8.0.15-fpm-alpine3.15

RUN apk update && apk upgrade
RUN apk add --no-cache nginx supervisor wget mysql-dev mysql
RUN apk add --no-cache php7-dev
RUN apk add --no-cache musl-dev
RUN apk add --no-cache linux-headers
RUN apk add --no-cache $PHPIZE_DEPS
RUN apk add --no-cache gcc
RUN apk add --no-cache autoconf
RUN apk add --no-cache libc-dev
RUN apk add --no-cache libffi-dev

RUN docker-php-ext-install pdo_mysql grpc mbstring zip gd
RUN CFLAGS="$CFLAGS -D_GNU_SOURCE" docker-php-ext-install sockets
RUN pecl install grpc-beta

WORKDIR /var/www/html

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

COPY . .

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /var/www/html && /usr/local/bin/composer install
RUN composer update

RUN chown -R www-data:www-data /var/www/html

RUN cd /var/www/html && php artisan cache:clear
#RUN cd /var/www/html && php artisan migrate

CMD sh /var/www/html/docker/startup.sh
