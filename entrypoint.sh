#!/bin/bash

# -----------------------------
# 1. Set Apache port dynamically (Render)
# -----------------------------
sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf

# -----------------------------
# 2. Run migrations & clear caches
# -----------------------------
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# -----------------------------
# 3. Start Apache in foreground
# -----------------------------
apachectl -D FOREGROUND
