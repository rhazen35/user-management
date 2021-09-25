FROM php:7.4.19-fpm-alpine

# PDO_MYSQL
RUN apk --update --no-cache add git

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		zlib-dev \
		libxslt-dev \
	; \
	\
    docker-php-ext-install pdo_mysql

RUN apk add --no-cache rabbitmq-c-dev && \
    mkdir -p /usr/src/php/ext/amqp && \
    curl -fsSL https://pecl.php.net/get/amqp | tar xvz -C "/usr/src/php/ext/amqp" --strip 1 && \
    docker-php-ext-install amqp; \
    docker-php-ext-enable amqp

COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
EXPOSE ${PHP_FPM_EXPOSED_PORT}