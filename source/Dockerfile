FROM php:8.4-fpm-alpine

WORKDIR /var/www

# System dependencies
RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    g++ \
    make \
    autoconf \
    pkgconf

# PHP extensions (IMPORTANT: includes pdo_mysql)
RUN docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
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

# Copy project files
COPY . .

# Prepare storage and cache directories before installing dependencies
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Ensure temp dir works
RUN chmod 1777 /tmp

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-progress --no-suggest

# Final permissions
RUN chown -R www-data:www-data /var/www

EXPOSE 9000

# CMD ["php-fpm", "-F"]
