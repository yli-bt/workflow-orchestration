FROM docker.io/yichenglibt/php:latest

WORKDIR /var/www/html

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

COPY . .

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /var/www/html && /usr/local/bin/composer install
RUN cd /var/www/html && /usr/local/bin/composer dump-autoload && /usr/local/bin/composer update

RUN chown -R www-data:www-data /var/www/html

RUN cd /var/www/html && \
    php artisan cache:clear

ENTRYPOINT []

