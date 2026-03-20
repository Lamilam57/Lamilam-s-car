# Use Apache + PHP 8.2
FROM php:8.2-apache

# -----------------------------
# 1. System dependencies
# -----------------------------
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpq-dev libonig-dev libzip-dev \
    libjpeg62-turbo-dev libpng-dev libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# -----------------------------
# 2. Enable Apache rewrite
# -----------------------------
RUN a2enmod rewrite

# -----------------------------
# 3. PHP extensions
# -----------------------------
RUN docker-php-ext-install pdo pdo_mysql mbstring zip

# Install GD for image processing
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# -----------------------------
# 4. Install Composer
# -----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------
# 5. Set working directory
# -----------------------------
WORKDIR /var/www/html

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
CMD ["/entrypoint.sh"]

# -----------------------------
# 6. Copy project
# -----------------------------
COPY . .

# -----------------------------
# 7. Install PHP dependencies
# -----------------------------
RUN composer install --no-dev --optimize-autoloader

# -----------------------------
# 8. Permissions
# -----------------------------
RUN chown -R www-data:www-data storage bootstrap/cache

# -----------------------------
# 9. Expose port for Render
# -----------------------------
ENV PORT=10000
EXPOSE 10000

# -----------------------------
# 10. Start Apache
# -----------------------------
CMD ["apache2-foreground"]
