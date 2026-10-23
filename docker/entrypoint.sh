#!/bin/sh
set -e

# Ensure SQLite database file exists if using sqlite
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
fi

# Ensure storage directory permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Cache config & routes for production if APP_KEY is present
if [ -n "$APP_KEY" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Run migrations & seeders gracefully
php artisan migrate --force || true
php artisan db:seed --force || true

# Bind Apache to PORT provided by host (e.g. Render / Koyeb uses $PORT, default 8000)
PORT="${PORT:-8000}"
sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

exec apache2-foreground
