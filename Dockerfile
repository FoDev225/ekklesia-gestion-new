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

# Garantit que les dossiers de cache Laravel existent, peu importe ce que Git a préservé
RUN mkdir -p storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN composer install --optimize-autoloader --no-dev

EXPOSE 8080

CMD ["sh", "-c", "echo '=== PORT VALUE IS:' $PORT '==='; php artisan migrate --force; php artisan storage:link || true; php artisan config:cache; php artisan serve --host 0.0.0.0 --port ${PORT:-8080}"]