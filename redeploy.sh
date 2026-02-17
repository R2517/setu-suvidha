#!/bin/bash
# ══════════════════════════════════════════════════════
# SETU Suvidha — Quick Re-deploy (after git push)
# Hostinger VPS — No sudo required
# Run: bash ~/setu-suvidha/redeploy.sh
# ══════════════════════════════════════════════════════

set -e

APP_DIR="/home/u515436084/setu-suvidha"

# Load nvm
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

echo ">>> Pulling latest changes..."
cd "$APP_DIR"
git pull origin main

echo ">>> Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo ">>> Building frontend..."
npm ci
npm run build

echo ">>> Running migrations..."
php artisan migrate --force

echo ">>> Clearing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ">>> Setting permissions..."
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

echo ""
echo "Re-deploy complete! Changes are now live on https://setusuvidha.com"
