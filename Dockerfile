FROM php:8.1-apache

# Установка необходимых расширений и пакетов
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    zip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite

# Копируем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www/html

# Копируем проект
COPY . /var/www/html

# Конфиг Apache, если есть
COPY ./apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Установка зависимостей
RUN composer install
