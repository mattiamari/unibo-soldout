.PHONY: soldout web web-dev php php-dev devtools

soldout: Dockerfile
	docker build -t soldout:latest .

web: docker/web/Dockerfile
	docker build -t soldout-web:latest docker/web

web-dev: docker/web/dev.Dockerfile
	docker build -t soldout-web-dev:latest -f docker/web/dev.Dockerfile docker/web

php: docker/php/Dockerfile
	docker build -t soldout-php:latest docker/php

php-dev: docker/php/dev.Dockerfile
	docker build -t soldout-php-dev:latest -f docker/php/dev.Dockerfile docker/php

devtools: docker/devtools.Dockerfile
	docker build -t soldout-devtools:latest -f docker/devtools.Dockerfile docker