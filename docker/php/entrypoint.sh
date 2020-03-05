#!/bin/sh

chmod 777 /soldout/data
exec docker-php-entrypoint "$@"
