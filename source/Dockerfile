FROM php:8.4-fpm-alpine

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
    re2c \
    && docker-php-ext-configure gd \
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

# Edit Perms
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

USER www-data

# Expose 9000
EXPOSE 9000

CMD ["php-fpm"]
