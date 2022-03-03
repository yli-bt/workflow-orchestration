FROM php:7.4-fpm-alpine

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

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && install-php-extensions pdo_mysql grpc mbstring zip gd sockets

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

RUN cd /var/www/html && /var/www/html/vendor/bin/rr get

ENTRYPOINT []

#CMD cd /var/www/html && /var/www/html/rr serve

#RUN /bin/sh -c /var/www/html/docker/startup.sh

#CMD cd /var/www/html && php artisan migrate
