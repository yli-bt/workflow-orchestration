#!/bin/sh

php-fpm -D

while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done;

cd /var/www/html && php artisan migrate

nginx
