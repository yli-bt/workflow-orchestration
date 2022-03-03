#!/bin/sh

sed -i "s,LISTEN_PORT,$PORT,g" /etc/nginx/nginx.conf

cd /var/www/html
php artisan migrate

/usr/bin/supervisord -c /app/docker/supervisord.conf &

/var/www/html/rr serve
php-fpm -D

while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done;

nginx
