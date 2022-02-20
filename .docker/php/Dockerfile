# ----------------------
# The FPM base container
# ----------------------
FROM php:7.4-fpm-alpine AS dev

RUN apk add \
    libpng-dev

# Install php extensions
RUN docker-php-ext-install \
    gd

# Cleanup apk cache and temp files
RUN rm -rf /var/cache/apk/* /tmp/*

# ----------------------
# Composer install step
# ----------------------

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ----------------------
# The FPM production container
# ----------------------
FROM dev
