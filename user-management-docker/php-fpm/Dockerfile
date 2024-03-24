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

RUN pecl install xdebug-3.1.5

RUN apk add --no-cache rabbitmq-c-dev && \
    mkdir -p /usr/src/php/ext/amqp && \
    curl -fsSL https://pecl.php.net/get/amqp | tar xvz -C "/usr/src/php/ext/amqp" --strip 1 && \
    docker-php-ext-install amqp; \
    docker-php-ext-enable amqp

RUN docker-php-ext-install pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY supervisord.conf /etc/supervisor.d/supervisord.ini

RUN mkdir /var/log/messenger

WORKDIR /var/www
EXPOSE ${PHP_FPM_EXPOSED_PORT}

# Prepare cache on container startup, extract env vars for console commands, initialize application and start webserver and co
CMD printenv | grep -v "no_proxy" >> /etc/environment \
    && /usr/bin/supervisord --nodaemon --configuration /etc/supervisord.conf