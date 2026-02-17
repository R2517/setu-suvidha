#!/bin/bash
# ══════════════════════════════════════════════════════
# SETU Suvidha — Quick Re-deploy (after git push)
# Hostinger VPS — No sudo required
# Run: bash ~/setu-suvidha/redeploy.sh
# ══════════════════════════════════════════════════════

set -e

APP_DIR="/home/u515436084/setu-suvidha"
PHP_BIN="/opt/alt/php83/usr/bin/php"

# Load nvm
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

echo ">>> Pulling latest changes..."
cd "$APP_DIR"
git pull origin main

echo ">>> Installing dependencies..."
$PHP_BIN $(which composer) install --no-dev --optimize-autoloader --no-interaction

echo ">>> Building frontend..."
npm ci
npm run build

echo ">>> Running migrations..."
$PHP_BIN artisan migrate --force

echo ">>> Clearing caches..."
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

echo ">>> Setting permissions..."
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

echo ""
echo "Re-deploy complete! Changes are now live on https://setusuvidha.com"
