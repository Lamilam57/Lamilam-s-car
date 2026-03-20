FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    libjpeg-dev libpng-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Install GD for image processing
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first (cache optimization)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copy full project
COPY . .

# Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Laravel optimization
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

CMD ["php-fpm"]
