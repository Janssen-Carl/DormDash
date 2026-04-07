# =========================
# Builder Stage
# =========================
FROM php:8.4-fpm-alpine AS builder

# Set working directory
WORKDIR /var/www

# Install system dependencies & PHP build tools
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
    shadow

# Install PHP extensions needed by Laravel
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        bcmath \
        exif \
        pcntl \
        intl \
        gd \
        zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy the entire application first (includes artisan)
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs

# Fix permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# =========================
# Runtime Stage
# =========================
FROM php:8.4-fpm-alpine AS runtime

WORKDIR /var/www

# Install runtime PHP extensions
RUN apk add --no-cache \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    oniguruma \
    icu

# Copy application & vendor from builder
COPY --from=builder /var/www /var/www

# Copy Composer too (optional, useful for dev containers)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install

# Copy custom PHP-FPM config
COPY docker/fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf

# Fix permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Fix permissions
RUN chown -R www-data:www-data /var/www /usr/local/etc/php-fpm.d

# Fix permissions
RUN mkdir -p /run/php /var/log/php-fpm \
    && chown -R www-data:www-data /run/php /var/log/php-fpm

#Use non-root user
USER www-data

# Expose PHP-FPM
EXPOSE 9000

CMD ["php-fpm", "-F"]
