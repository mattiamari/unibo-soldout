FROM soldout:latest
FROM php:7-fpm-alpine

RUN apk add --no-cache tzdata && \
	docker-php-ext-install pdo pdo_mysql
COPY entrypoint.sh /usr/local/bin/

WORKDIR /soldout

COPY --from=0 /soldout www/

ENTRYPOINT [ "entrypoint.sh" ]
CMD [ "php-fpm" ]
