FROM php:8.4-fpm-alpine AS builder

# Install system dependencies for PHP extensions
RUN apk add --no-cache \
    bash \
    git \
    zip \
    unzip \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    g++ \
    make \
    autoconf \
    pkgconf \
    re2c

RUN docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
    && docker-php-ext-install \
        pdo pdo_mysql mbstring zip exif pcntl intl gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first (Caching)
COPY composer.json composer.lock ./

# Copy everythang
COPY . .

# Install PHP dependencies (Autoloader)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN rm -rf /root/.composer/cache

# Edit Perms
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Install only runtime PHP extensions (skip build tools)
RUN apk add --no-cache \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    oniguruma \
    icu

FROM php:8.4-fpm-alpine AS runtime

# Copy built application & vendor from builder
COPY --from=builder /var/www /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Use non-root user
USER www-data

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
