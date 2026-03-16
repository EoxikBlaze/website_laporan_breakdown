#!/bin/sh
set -e

echo "=== GS Breakdown App Startup ==="

# ── 1. Create .env from example ──────────────────────────────────────────────
if [ ! -f .env ]; then
    echo "Creating .env..."
    cp .env.example .env
fi

# ── 2. Inject Railway env vars into .env ─────────────────────────────────────
[ -n "$APP_URL" ]   && sed -i "s|APP_URL=.*|APP_URL=$APP_URL|g"   .env
[ -n "$APP_KEY" ]   && sed -i "s|APP_KEY=.*|APP_KEY=$APP_KEY|g"   .env
[ -n "$APP_DEBUG" ] && sed -i "s|APP_DEBUG=.*|APP_DEBUG=$APP_DEBUG|g" .env

# ── 3. Generate APP_KEY if still missing ─────────────────────────────────────
CURRENT_KEY=$(grep "^APP_KEY=" .env | cut -d= -f2)
if [ -z "$CURRENT_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# ── 4. Create required directories & SQLite file ─────────────────────────────
echo "Setting up storage & database..."
mkdir -p storage/framework/sessions storage/framework/views \
         storage/framework/cache/data storage/logs \
         bootstrap/cache
touch database/database.sqlite
chmod -R 775 storage bootstrap/cache

# ── 5. Discover packages (needed when composer ran with --no-scripts) ─────────
php artisan package:discover --ansi || true

# ── 6. Run migrations & seed ─────────────────────────────────────────────────
echo "Running migrations..."
php artisan migrate --force --seed || echo "Migrations failed, continuing..."

# ── 7. Build caches ───────────────────────────────────────────────────────────
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ── 8. Storage link ───────────────────────────────────────────────────────────
php artisan storage:link 2>/dev/null || true

# ── 9. Start server ───────────────────────────────────────────────────────────
echo "=== Starting server on port ${PORT:-8000} ==="
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
