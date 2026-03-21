FROM php:8.2-apache

# Memory for Composer
ENV COMPOSER_MEMORY_LIMIT=-1

# -----------------------------
# 1. Install system dependencies
# -----------------------------
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpq-dev libonig-dev libzip-dev \
    libjpeg62-turbo-dev libpng-dev libfreetype6-dev \
    libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

# -----------------------------
# 2. Enable Apache rewrite module
# -----------------------------
RUN a2enmod rewrite

# -----------------------------
# 3. Install PHP extensions
# -----------------------------
RUN docker-php-ext-install pdo pdo_mysql mbstring zip gd curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# -----------------------------
# 4. Install Composer
# -----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------
# 5. Set working directory
# -----------------------------
WORKDIR /var/www/html

# -----------------------------
# 6. Copy project files
# -----------------------------
COPY . .

# -----------------------------
# 7. Install PHP dependencies
# -----------------------------
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# -----------------------------
# 8. Copy entrypoint and set executable
# -----------------------------
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# -----------------------------
# 9. Copy custom Apache config
# -----------------------------
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# -----------------------------
# 10. Fix permissions
# -----------------------------
RUN chown -R www-data:www-data storage bootstrap/cache

# -----------------------------
# 11. Expose HTTP port for Render
# -----------------------------
ENV PORT=10000
EXPOSE 10000

# -----------------------------
# 12. Start container via entrypoint
# -----------------------------
CMD ["/entrypoint.sh"]
