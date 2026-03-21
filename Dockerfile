# Use PHP 8.2 with Apache
FROM php:8.2-apache

# -----------------------------
# 1. Set environment variables
# -----------------------------
ENV COMPOSER_MEMORY_LIMIT=-1
ENV PORT=10000

# -----------------------------
# 2. Install system dependencies
# -----------------------------
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpq-dev libonig-dev libzip-dev \
    libjpeg62-turbo-dev libpng-dev libfreetype6-dev \
    libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

# -----------------------------
# 3. Enable Apache rewrite module
# -----------------------------
RUN a2enmod rewrite

# -----------------------------
# 4. Install PHP extensions
# -----------------------------
RUN docker-php-ext-install pdo pdo_mysql mbstring zip curl

# Install GD for image processing
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# -----------------------------
# 5. Install Composer
# -----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------
# 6. Set working directory
# -----------------------------
WORKDIR /var/www/html

# -----------------------------
# 7. Copy project files
# -----------------------------
COPY . .

# -----------------------------
# 8. Install PHP dependencies
# -----------------------------
# This now works correctly thanks to proper extensions and memory limit
RUN composer install --no-dev --optimize-autoloader

# -----------------------------
# 9. Copy entrypoint and set executable
# -----------------------------
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# -----------------------------
# 10. Copy custom Apache config
# -----------------------------
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# -----------------------------
# 11. Fix permissions
# -----------------------------
RUN chown -R www-data:www-data storage bootstrap/cache

# -----------------------------
# 12. Expose HTTP port
# -----------------------------
EXPOSE 10000

# -----------------------------
# 13. Start container via entrypoint
# -----------------------------
CMD ["/entrypoint.sh"]
