#!/bin/bash
# ══════════════════════════════════════════════════════
# SETU Suvidha — Quick Re-deploy (after git push)
# Run this on VPS after pushing changes from local
# ══════════════════════════════════════════════════════

set -e

APP_DIR="/home/u515436084/htdocs/setusuvidha.com"

echo ">>> Pulling latest changes..."
cd "$APP_DIR"
git pull origin main

echo ">>> Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo ">>> Building frontend..."
npm ci --production=false
npm run build

echo ">>> Running migrations..."
php artisan migrate --force

echo ">>> Clearing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo ">>> Setting permissions..."
sudo chown -R www-data:www-data "$APP_DIR"
sudo chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

echo ""
echo "✅ Re-deploy complete! Changes are now live."
