#!/bin/sh

echo "Starting Laravel setup..."

# If .env does not exist, copy example
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate application key if missing
php artisan key:generate || true

# Run migrations (force in production)
php artisan migrate --force || true

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache

# Start Apache
apache2-foreground
