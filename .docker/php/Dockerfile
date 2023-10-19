FROM php:8.1.1-fpm-alpine3.15

RUN apk update && apk upgrade && \
	apk add git

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /srv/
