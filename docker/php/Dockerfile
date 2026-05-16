##################################
# BASE DEPENDENCIES
##################################
FROM php:8.4-fpm-alpine3.21 AS base

WORKDIR /var/www/html

RUN apk add --no-cache \
    $PHPIZE_DEPS \
    curl-dev \
    libxml2-dev \
    oniguruma-dev \
    postgresql-dev \
    icu-dev \
    libpq \
    icu

RUN docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    mbstring \
    pcntl \
    bcmath \
    intl

RUN pecl install redis apcu \
    && docker-php-ext-enable redis apcu

##################################
# COMPOSER DEPENDENCIES
##################################
FROM composer:2.9.5 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

COPY . .

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

##################################
# APP BUILD
##################################
FROM base AS app

WORKDIR /var/www/html

COPY --from=vendor /app/vendor ./vendor

COPY . .


##################################
# DEVELOPMENT
##################################
FROM php:8.4-fpm-alpine3.21 AS development

WORKDIR /var/www/html

RUN apk add --no-cache \
    libpq \
    icu \
    su-exec \
    nodejs \
    npm

ARG GROUP_ID=1000
ARG GROUP_NAME=www
ARG USER_ID=1000
ARG USER_NAME=www

RUN addgroup -g ${GROUP_ID} -S ${GROUP_NAME} && \
    adduser -u ${USER_ID} -D -S -G ${GROUP_NAME} ${USER_NAME}

COPY --from=app /var/www/html /var/www/html
COPY --from=base /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=base /usr/local/etc/php /usr/local/etc/php

COPY --chown="${GROUP_NAME}:${USER_NAME}" ./ ./
