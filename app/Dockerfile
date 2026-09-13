# syntax=docker/dockerfile:1

# ---- Stage 1: PHP dependencies ----
FROM composer:2.8 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-progress \
    --prefer-dist \
    --ignore-platform-reqs

# ---- Stage 2: frontend assets ----
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci
COPY . .
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# ---- Stage 3: production runtime ----
FROM php:8.4-fpm-alpine AS app

RUN apk add --no-cache \
        libpq-dev \
        icu-dev \
        icu-libs \
        oniguruma-dev \
        libzip-dev \
        libpng-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        supervisor \
        bash \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        intl \
        mbstring \
        zip \
        opcache \
        bcmath \
        gd \
        pcntl

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/
# `sockets` is needed by pest-plugin-browser; install-php-extensions pulls
# in the kernel headers Alpine needs to build it.
RUN install-php-extensions redis sockets

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/zz-opcache.ini
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini

WORKDIR /var/www/html

COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
COPY . .

# A second, stable copy of the whole public directory outside /var/www/html.
# nginx serves from `public/` and needs index.php there, not just the compiled
# assets — without it, a request for "/" finds a directory with no index file
# and nginx answers 403 instead of handing off to PHP.
#
# The `app-public` named volume (docker-compose.yml, shared read-only with
# nginx) only auto-populates from the image the first time it is created, so
# every later deploy would otherwise keep serving the original copy. The
# entrypoint re-syncs from this path on every container start instead.
RUN mkdir -p /opt/app-public && cp -a public/. /opt/app-public/

RUN addgroup -g 1000 laravel \
    && adduser -G laravel -u 1000 -D laravel \
    && chown -R laravel:laravel /var/www/html /opt/app-public \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker/php/entrypoint.sh docker/php/dev-entrypoint.sh docker/php/sync-public.sh

USER laravel

EXPOSE 9000
HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
    CMD php -r "exit(0);" || exit 1

ENTRYPOINT ["docker/php/entrypoint.sh"]
CMD ["php-fpm"]
