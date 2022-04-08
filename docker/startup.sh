#!/bin/sh

sed -i "s,LISTEN_PORT,$PORT,g" /etc/nginx/nginx.conf
sed -i "s,TEMPORAL_HOST,$TEMPORAL_HOST,g" .rr.yaml

php-fpm -D

while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done;

cd /var/www/html && php artisan migrate

nginx &

/var/www/html/rr serve
