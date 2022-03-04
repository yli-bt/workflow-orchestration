# Workflow Orchestration Microservice

Built on top of Lumen, Nginx, Temporal.io, and PHP Roadrunner.

## Install

After build, you must install grpc and protobuf php extensions

> docker ps

Copy the container ID for "workflow-orchestration_laravel_workflow_app"

> docker exec -it <container id> pecl install grpc-beta protobuf
> docker exec -it <container id> docker-php-ext-enable protobuf
>  docker exec -it <container id> docker-php-ext-enable grpc

Restart nginx

>  docker exec -it <container id> nginx -s reload

Login to restart php-fpm

> docker exec -it <container id> /bin/sh

> ps -ef

Get the PIDs of the php-fpm processes and kill them.  Restart php-fpm

> kill -HUP <pids>
> php-fpm -D
> exit
