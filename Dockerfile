FROM composer:2 AS composer

FROM php:8.1-apache

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable pdo pdo_mysql
RUN composer install --no-dev

COPY ./src /var/www/html
COPY --from=composer /usr/bin/composer /usr/bin/composer
