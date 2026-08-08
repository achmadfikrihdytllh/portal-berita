#!/bin/sh
set -e

cd /var/www/html

LISTEN_PORT="${PORT:-8000}"
sed -i "s/listen 8000;/listen ${LISTEN_PORT};/" /etc/nginx/http.d/default.conf

# Cache config, route, view untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migration di background supaya server bisa langsung listening
(php artisan migrate --force || true) &

# Start supervisor (nginx + php-fpm)
exec supervisord -c /etc/supervisord.conf