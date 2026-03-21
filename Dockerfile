# -----------------------------
# Stage 1: Build PHP dependencies
# -----------------------------
FROM php:8.2-cli AS build

ENV COMPOSER_MEMORY_LIMIT=-1

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip zip libzip-dev libonig-dev \
    libjpeg62-turbo-dev libpng-dev libfreetype6-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy only composer files first (to leverage Docker cache)
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# -----------------------------
# Stage 2: Final Apache image
# -----------------------------
FROM php:8.2-apache

ENV PORT=10000

# Enable Apache rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy Laravel app files
COPY . .

# Copy vendor from build stage
COPY --from=build /app/vendor ./vendor

# Copy entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Copy Apache config
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose Render port
EXPOSE 10000

# Start container
CMD ["/entrypoint.sh"]
