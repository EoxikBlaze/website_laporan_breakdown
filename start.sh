#!/bin/sh
set -e

echo "=== GS Breakdown App Startup ==="

# Create .env from example if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# Write Railway environment variables into .env
# This ensures Laravel can read them even without native env detection
if [ -n "$APP_KEY" ]; then
    sed -i "s|APP_KEY=.*|APP_KEY=$APP_KEY|" .env
fi
if [ -n "$APP_URL" ]; then
    sed -i "s|APP_URL=.*|APP_URL=$APP_URL|" .env
fi

# Force production settings
sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|" .env

# Ensure SQLite database file exists
echo "Ensuring database file exists..."
touch database/database.sqlite

# Run migrations
echo "Running migrations..."
php artisan migrate --force --seed || echo "Migration failed, continuing..."

# Clear all caches to avoid stale cached routes/views
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
php artisan storage:link || true

echo "=== Starting server on port $PORT ==="
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
