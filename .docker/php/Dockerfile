# ----------------------
# The FPM base container
# ----------------------
# Security: CVE-2026-109618 / CVE-2026-109619 (php2shell) are fixed in 8.2.35+.
# Do NOT downgrade below this version.
FROM php:8.2.35-fpm-alpine AS dev

RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    freetype-dev \
    libpng-dev \
    postgresql-dev

# Cleanup apk cache and temp files
RUN rm -rf /var/cache/apk/* /tmp/*

# Configure php extensions
RUN docker-php-ext-configure gd --with-freetype

# Install php extensions
RUN docker-php-ext-install \
    gd \
    pdo \
    pdo_pgsql

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
