#!/bin/sh
set -e

cd /var/www/html

# Cache config, route, view untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migration di BACKGROUND supaya server bisa langsung listening
# tanpa nunggu migrate selesai dulu (penting untuk health check platform hosting)
(php artisan migrate --force || true) &

# Start supervisor (nginx + php-fpm) - ini yang harus langsung jalan
exec supervisord -c /etc/supervisord.conf