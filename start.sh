#!/bin/bash
set -e

echo "=== TMS Application Startup ==="

# 1. Create required directories
echo "Creating required directories..."
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p database

# 2. Set permissions (best effort)
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chmod 775 database 2>/dev/null || true

# 3. Set safe environment defaults
echo "Setting environment defaults..."
export APP_ENV="${APP_ENV:-production}"
export APP_DEBUG="${APP_DEBUG:-false}"
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"
export DB_DATABASE="${DB_DATABASE:-/app/database/database.sqlite}"

# 4. Generate APP_KEY if missing
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    echo "APP_KEY generated"
fi

# 5. Create SQLite database file if it doesn't exist
echo "Ensuring database exists..."
touch "$DB_DATABASE"
chmod 664 "$DB_DATABASE" 2>/dev/null || true

# 6. Clear all caches
echo "Clearing caches..."
php artisan optimize:clear 2>/dev/null || echo "Cache clear skipped"

# 7. Run migrations
echo "Running migrations..."
php artisan migrate --force

# 8. Seed database (always for MVP - handles duplicates gracefully)
echo "Seeding database..."
php artisan db:seed --force 2>/dev/null || echo "Seeding skipped"

# 9. Start the server
echo "Starting server on 0.0.0.0:${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
