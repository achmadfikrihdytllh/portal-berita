#!/bin/sh
set -e

cd /var/www/html

# Generate APP_KEY kalau belum ada
php artisan key:generate --force || true

# Cache config, route, view untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migration otomatis (aman, tidak menghapus data)
php artisan migrate --force

# Start supervisor (nginx + php-fpm)
exec supervisord -c /etc/supervisord.conf
