FROM php:7-fpm-alpine

RUN apk add --no-cache tzdata && \
	docker-php-ext-install pdo pdo_mysql
COPY entrypoint.sh /usr/local/bin/

ENTRYPOINT [ "entrypoint.sh" ]
CMD [ "php-fpm" ]
