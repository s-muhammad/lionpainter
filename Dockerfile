# syntax=docker/dockerfile:1

# ---------- Stage 1: PHP dependencies ----------
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev --no-interaction --no-progress --prefer-dist \
    --no-scripts --no-autoloader --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts --ignore-platform-reqs

# ---------- Stage 2: Frontend build (Vite) ----------
FROM node:20-alpine AS assets
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
# Some packages (Livewire, Flux, ...) import CSS/JS from vendor/
COPY --from=vendor /app/vendor ./vendor
RUN rm -f public/hot && npm run build

# ---------- Stage 3: Final image ----------
FROM serversideup/php:8.2-fpm-nginx AS final

# Optional PHP extensions (uncomment what your project needs):
# USER root
# RUN install-php-extensions intl
# USER www-data

ENV PHP_OPCACHE_ENABLE=1 \
    AUTORUN_ENABLED=true \
    SSL_MODE=off

# App code + vendor, then the built frontend assets
COPY --chown=www-data:www-data --from=vendor /app /var/www/html
COPY --chown=www-data:www-data --from=assets /app/public/build /var/www/html/public/build

# NOTE: this image listens on port 8080 (set "Ports Exposes" = 8080 in Coolify)
EXPOSE 8080
