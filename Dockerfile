# ============================================
# Stage 1 — Composer dependencies
# ============================================
FROM composer:2.7 AS composer-deps

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs \
    --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ============================================
# Stage 2 — Final production image
# ============================================
FROM php:8.2-fpm-alpine AS production

RUN apk add --no-cache \
        libpng-dev \
        libzip-dev \
        oniguruma-dev \
        autoconf \
        gcc \
        g++ \
        make \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        zip \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del --no-cache \
        autoconf gcc g++ make \
        libpng-dev libzip-dev oniguruma-dev

WORKDIR /var/www/html

COPY --from=composer-deps /app/vendor ./vendor
COPY --from=composer-deps /app/composer.json ./

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache