# syntax=docker/dockerfile:1.7
ARG PHP_VERSION=8.3

# ──────────────────────────────────────────────────────────────────────────────
# Stage 1: Build front-end assets with Vite
# ──────────────────────────────────────────────────────────────────────────────
FROM node:20-alpine AS assets

WORKDIR /app

COPY package*.json vite.config.js ./
# Use `npm install` rather than `npm ci`: Vite 8 pulls Rollup, which uses
# platform-specific optional native deps (@rollup/rollup-*). A lockfile
# generated on Windows/macOS lacks the linux-musl variant Alpine needs,
# and `npm ci` refuses to reconcile that. `npm install` resolves it.
RUN npm install --no-audit --no-fund --loglevel=error

COPY resources ./resources
COPY public ./public
RUN npm run build

# ──────────────────────────────────────────────────────────────────────────────
# Stage 2: Install Composer dependencies
# ──────────────────────────────────────────────────────────────────────────────
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
# ext-intl / ext-exif / ext-gd live in the runtime image, not in composer:2.
# Ignore them here so `composer install` resolves the lock file successfully;
# the final runtime stage provides the real extensions.
RUN composer install \
        --no-dev \
        --no-interaction \
        --no-scripts \
        --prefer-dist \
        --optimize-autoloader \
        --ignore-platform-req=ext-intl \
        --ignore-platform-req=ext-exif \
        --ignore-platform-req=ext-gd \
        --ignore-platform-req=ext-pcntl

# ──────────────────────────────────────────────────────────────────────────────
# Stage 3: Runtime image (PHP-FPM + nginx via supervisord)
# ──────────────────────────────────────────────────────────────────────────────
FROM php:${PHP_VERSION}-fpm-alpine AS runtime

ARG WWW_USER_ID=1000
ARG WWW_GROUP_ID=1000

# System packages + PHP extensions Laravel/Filament need
RUN apk add --no-cache \
        nginx \
        supervisor \
        bash \
        git \
        curl \
        icu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        oniguruma-dev \
        libwebp-dev \
        mysql-client \
        tzdata \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps \
    && rm -rf /tmp/* /var/cache/apk/*

# Composer binary for artisan / maintenance tasks inside the container
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ensure www-data uid/gid match the host for bind-mount friendliness
RUN set -eux; \
    if [ "$(id -u www-data)" != "${WWW_USER_ID}" ]; then \
        deluser www-data || true; \
        addgroup -g ${WWW_GROUP_ID} -S www-data || true; \
        adduser  -u ${WWW_USER_ID} -S -G www-data www-data; \
    fi

WORKDIR /var/www/html

# Application source (vendor + built assets injected below)
COPY --chown=www-data:www-data . /var/www/html
COPY --from=vendor --chown=www-data:www-data /app/vendor /var/www/html/vendor
COPY --from=assets --chown=www-data:www-data /app/public/build /var/www/html/public/build

# Service configuration
COPY docker/php/php.ini        /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php/www.conf       /usr/local/etc/php-fpm.d/zz-www.conf
COPY docker/nginx/nginx.conf   /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh      /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p storage/framework/{cache,sessions,testing,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
