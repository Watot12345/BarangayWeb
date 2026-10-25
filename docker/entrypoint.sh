#!/bin/sh
set -e

echo "==> Starting Barangay San Agustin Application..."

# 1. Fallback for APP_KEY if not configured in host environment
if [ -z "$APP_KEY" ]; then
    echo "==> APP_KEY not provided, setting fallback application key..."
    export APP_KEY="base64:xKa28hCNNeiry9tH7PRrmiK+qtVbxjPkLQ/+VmnWYLo="
fi

# 2. Default SQLite database setup
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"
export DB_DATABASE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
export SESSION_DRIVER="${SESSION_DRIVER:-file}"
export CACHE_STORE="${CACHE_STORE:-file}"

if [ "$DB_CONNECTION" = "sqlite" ]; then
    mkdir -p /var/www/html/database
    if [ ! -f "$DB_DATABASE" ]; then
        touch "$DB_DATABASE"
    fi
fi

# 3. Run database migrations & seeders
echo "==> Running database migrations..."
php artisan migrate --force --no-interaction || true

echo "==> Running database seeders..."
php artisan db:seed --force --no-interaction || true

# 4. Configure PHP upload limits (50M)
echo "upload_max_filesize = 50M" > /usr/local/etc/php/conf.d/uploads.ini || true
echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/uploads.ini || true
echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini || true

# 5. Connect storage symlink and clear caches
php artisan storage:link --force || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# 6. Ensure full read/write permissions for web server (www-data)
echo "==> Setting directory permissions..."
mkdir -p /var/www/html/public/uploads/posts /var/www/html/storage/app/public/posts
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/public/uploads
chmod -R 777 /var/www/html/public/uploads /var/www/html/storage/app/public
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
if [ -f "$DB_DATABASE" ]; then
    chmod 666 "$DB_DATABASE"
fi

# 6. Configure Apache PORT (Render passes $PORT, default 8000)
PORT="${PORT:-8000}"
echo "==> Binding Apache to port ${PORT}..."
sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

echo "==> Barangay San Agustin ready! Launching Apache..."
exec apache2-foreground
