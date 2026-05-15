#!/bin/sh
set -e

# Clean up stale temp files from www-data
rm -f /tmp/*.php*

chown -R www:www storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan optimize:clear && exec php-fpm
