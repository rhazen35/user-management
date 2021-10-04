FROM php:7.4.19-fpm-alpine

RUN apk add --no-cache \
        acl \
        fcgi \
        file \
        gettext \
        git \
        make \
        bash \
        nano \
        zip \
        openssh-client \
        supervisor \
        nginx \
        freetype \
        libpng \
        libjpeg-turbo \
        freetype-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        $PHPIZE_DEPS \
    ;

RUN pecl install xdebug

RUN apk add --no-cache rabbitmq-c-dev && \
    mkdir -p /usr/src/php/ext/amqp && \
    curl -fsSL https://pecl.php.net/get/amqp | tar xvz -C "/usr/src/php/ext/amqp" --strip 1 && \
    docker-php-ext-install amqp; \
    docker-php-ext-enable amqp

RUN docker-php-ext-install pdo_mysql
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
EXPOSE ${PHP_FPM_EXPOSED_PORT}