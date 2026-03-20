#!/bin/sh

echo "Starting Laravel setup..."

# Generate key if not present
php artisan key:generate || true

# Run migrations (force in production)
php artisan migrate --force || true

# Clear and cache config
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache

# Start Apache
apache2-foreground
