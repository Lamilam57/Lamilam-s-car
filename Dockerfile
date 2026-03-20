FROM php:8.2-fpm

# -----------------------------
# 1. Install system dependencies
# -----------------------------
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# -----------------------------
# 2. Install PHP extensions
# -----------------------------
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip

# Install GD properly (image processing)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# -----------------------------
# 3. Install Composer
# -----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------
# 4. Set working directory
# -----------------------------
WORKDIR /var/www

# -----------------------------
# 5. Copy composer files first (cache optimization)
# -----------------------------
COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-scripts

# -----------------------------
# 6. Copy application files
# -----------------------------
COPY . .

# -----------------------------
# 7. Laravel setup
# -----------------------------

# Generate app key (only if not already set)
RUN php artisan key:generate || true

# Set correct permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Optimize Laravel for production
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# -----------------------------
# 8. Expose port (for php-fpm)
# -----------------------------
EXPOSE 9000

# -----------------------------
# 9. Start PHP-FPM
# -----------------------------
CMD ["php-fpm"]
