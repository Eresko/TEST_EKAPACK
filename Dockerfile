FROM php:8.4-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip \
    && docker-php-ext-install pdo_pgsql pgsql

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Установка переменной TMPDIR
ENV TMPDIR=/tmp

WORKDIR /var/www/html

# Копирование проекта
COPY . .

# Установка зависимостей PHP
RUN composer install --no-dev --optimize-autoloader

# Создание нужных директорий и права
RUN mkdir -p storage/framework/{views,cache,sessions} \
    && mkdir -p /tmp \
    && chown -R www-data:www-data storage bootstrap/cache /tmp \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 1777 /tmp