FROM php:8.2-fpm

# Dépendances système nécessaires pour gd et autres extensions PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql zip opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --optimize-autoloader --no-dev

EXPOSE 8080

CMD php artisan migrate --force && php artisan storage:link && php artisan config:cache && php artisan serve --host 0.0.0.0 --port $PORT