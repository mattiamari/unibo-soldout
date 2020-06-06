#!/bin/sh

chmod 777 /soldout/data
ln -snf /usr/share/zoneinfo/$TZ /etc/localtime
echo $TZ > /etc/timezone

exec docker-php-entrypoint "$@"
