#!/usr/bin/env sh
# Container entrypoint: prepare runtime dirs, wait on dependencies,
# run migrations, warm caches, then exec the main process.

set -e

cd /var/www/html

# Ensure writable runtime directories exist on first boot (named volume may be empty)
mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# Generate APP_KEY on first boot if not provided
if [ -z "${APP_KEY:-}" ] && [ -f artisan ]; then
    echo "[entrypoint] APP_KEY not set — generating one (set APP_KEY in your env to make it persistent)"
    php artisan key:generate --show --no-ansi > /tmp/app_key || true
    export APP_KEY="base64:$(cat /tmp/app_key | tr -d '\r\n')"
fi

# Wait for MySQL (compose healthcheck usually handles this, but belt + suspenders)
if [ -n "${DB_HOST:-}" ]; then
    echo "[entrypoint] Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
    for i in $(seq 1 30); do
        if php -r "exit(@fsockopen(getenv('DB_HOST'), (int)(getenv('DB_PORT') ?: 3306)) ? 0 : 1);"; then
            echo "[entrypoint] Database reachable."
            break
        fi
        sleep 2
    done
fi

# Link public storage if not already linked (idempotent)
if [ ! -L public/storage ] && [ ! -e public/storage ]; then
    php artisan storage:link --no-interaction || true
fi

# Run pending migrations (safe to run on every boot)
php artisan migrate --force --no-interaction || true

# Warm framework caches
php artisan config:cache  --no-interaction || true
php artisan route:cache   --no-interaction || true
php artisan view:cache    --no-interaction || true
php artisan event:cache   --no-interaction || true

exec "$@"
