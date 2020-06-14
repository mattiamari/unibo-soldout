FROM ubuntu:latest

RUN apt-get update \
	&& DEBIAN_FRONTEND=noninteractive apt-get -y install npm composer php-zip zip \
	&& apt-get -y clean \
	&& npm install -g node-sass

WORKDIR /app
ENTRYPOINT ["/bin/bash"]
