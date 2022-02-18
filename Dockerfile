FROM php:7.4-fpm-alpine

RUN apk add --no-cache nginx supervisor wget mysql-dev mysql

RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www/html

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

COPY . .

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /var/www/html && /usr/local/bin/composer install 

RUN chown -R www-data: /var/www/html

RUN cd /var/www/html && php artisan cache:clear
#RUN cd /var/www/html && php artisan migrate

CMD sh /var/www/html/docker/startup.sh
