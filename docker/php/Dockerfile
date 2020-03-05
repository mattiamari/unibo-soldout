FROM php:7-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql
COPY entrypoint.sh /usr/local/bin/

ENTRYPOINT [ "entrypoint.sh" ]
CMD [ "php-fpm" ]
