FROM php:7.4.19-fpm-alpine
RUN apk --update --no-cache add git
RUN docker-php-ext-install pdo_mysql
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
EXPOSE ${PHP_FPM_EXPOSED_PORT}