##################################
# DEPENDENCIES STAGE
##################################
FROM php:8.4-fpm-alpine3.21 AS dependencies

WORKDIR /var/www/html

RUN --mount=type=cache,target=/var/cache/apk \
    apk add --no-cache \
    $PHPIZE_DEPS \
    curl-dev \
    libxml2-dev \
    oniguruma-dev \
    postgresql-dev \
    icu-dev

RUN docker-php-ext-install -j$(nproc) \
    pdo_pgsql mbstring pcntl bcmath intl

RUN --mount=type=cache,target=/tmp/pear/cache \
    pecl install redis apcu \
    && docker-php-ext-enable redis apcu

##################################
# BUILD STAGE
##################################
FROM php:8.4-fpm-alpine3.21 AS build

WORKDIR /var/www/html

ARG GROUP_ID=1000
ARG GROUP_NAME=www
ARG USER_ID=1000
ARG USER_NAME=www

RUN addgroup -g ${GROUP_ID} -S ${GROUP_NAME} \
    && adduser -u ${USER_ID} -S ${USER_NAME} -G ${GROUP_NAME}

RUN --mount=type=cache,target=/var/cache/apk \
    apk add --no-cache \
    libpq \
    icu \
    su-exec \
    nodejs \
    npm

COPY --from=dependencies /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=dependencies /usr/local/etc/php /usr/local/etc/php
COPY --from=composer:2.9.5 /usr/bin/composer /usr/bin/composer

RUN chmod u+s /sbin/su-exec

COPY docker/php/conf.d/*.ini /usr/local/etc/php/conf.d/

COPY composer.json composer.lock ./

RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --no-interaction --no-scripts --prefer-dist

COPY --chown=${USER_NAME}:${GROUP_NAME} ./ ./

##################################
# DEVELOPMENT
##################################
FROM build AS development

COPY ./docker/php/conf.d/zzz-local.ini /usr/local/etc/php/conf.d/zzz-local.ini
COPY ./docker/php/conf.d/www.conf /usr/local/etc/php-fpm.d/www.conf
