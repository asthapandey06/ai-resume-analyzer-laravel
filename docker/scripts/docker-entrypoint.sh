#!/bin/sh
set -e

echo "Preparing Laravel..."

mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache


# Run migrations if the RUN_MIGRATIONS environment variable is set to true
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Waiting for database..."

    # # Wait until database accepts connections
    until php artisan db:show >/dev/null 2>&1
    do
        sleep 2
    done

    echo "Running migrations..."
    php artisan migrate --force
fi

# Optimize Laravel if the APP_ENV is set to production
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing Laravel..."

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

echo "Starting PHP-FPM..."

exec "$@"