#!/bin/bash
# ══════════════════════════════════════════════════════════════════
# SETU Suvidha — Full Deployment Script for Hostinger VPS
# Domain: setusuvidha.com
# ══════════════════════════════════════════════════════════════════

set -e

DOMAIN="setusuvidha.com"
APP_DIR="/home/u515436084/htdocs/$DOMAIN"
REPO_URL="https://github.com/R2517/setu-suvidha.git"
DB_NAME="setu_suvidha"
DB_USER="setu_user"
DB_PASS="SetuSuvidha@2026#Secure"

echo ""
echo "═══════════════════════════════════════════════════"
echo "  SETU Suvidha — Deployment Starting..."
echo "  Domain: $DOMAIN"
echo "═══════════════════════════════════════════════════"
echo ""

# ─── Step 1: Check environment ───
echo ">>> Step 1: Checking environment..."
php -v | head -1
mysql --version 2>/dev/null || echo "MySQL client not found (will check server)"
composer --version 2>/dev/null | head -1 || echo "Composer not found - installing..."
node -v 2>/dev/null || echo "Node.js not found - installing..."
echo ""

# ─── Step 2: Install missing tools ───
echo ">>> Step 2: Installing required tools..."

# Composer (if not present)
if ! command -v composer &> /dev/null; then
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    echo "Composer installed."
fi

# Node.js (if not present, for Vite build)
if ! command -v node &> /dev/null; then
    echo "Installing Node.js 20..."
    curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
    sudo apt-get install -y nodejs
    echo "Node.js installed: $(node -v)"
fi

# Required PHP extensions
echo "Checking PHP extensions..."
php -m | grep -i "pdo_mysql" || (echo "Installing php-mysql..." && sudo apt-get install -y php-mysql)
php -m | grep -i "mbstring" || (echo "Installing php-mbstring..." && sudo apt-get install -y php-mbstring)
php -m | grep -i "xml" || (echo "Installing php-xml..." && sudo apt-get install -y php-xml)
php -m | grep -i "curl" || (echo "Installing php-curl..." && sudo apt-get install -y php-curl)
php -m | grep -i "gd" || (echo "Installing php-gd..." && sudo apt-get install -y php-gd)
php -m | grep -i "zip" || (echo "Installing php-zip..." && sudo apt-get install -y php-zip)
php -m | grep -i "bcmath" || (echo "Installing php-bcmath..." && sudo apt-get install -y php-bcmath)
echo "PHP extensions OK."
echo ""

# ─── Step 3: Create MySQL Database ───
echo ">>> Step 3: Setting up MySQL database..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null || echo "DB may already exist or need manual setup"
sudo mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';" 2>/dev/null || echo "User may already exist"
sudo mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost'; FLUSH PRIVILEGES;" 2>/dev/null || echo "Grants may need manual setup"
echo "Database setup done."
echo ""

# ─── Step 4: Clone or Pull repo ───
echo ">>> Step 4: Setting up application..."
if [ -d "$APP_DIR/.git" ]; then
    echo "Repo exists, pulling latest..."
    cd "$APP_DIR"
    git pull origin main
else
    echo "Cloning fresh repo..."
    # Backup existing files if any
    if [ -d "$APP_DIR" ] && [ "$(ls -A $APP_DIR 2>/dev/null)" ]; then
        echo "Backing up existing files..."
        sudo mv "$APP_DIR" "${APP_DIR}_backup_$(date +%Y%m%d_%H%M%S)"
    fi
    git clone "$REPO_URL" "$APP_DIR"
    cd "$APP_DIR"
fi
echo "Repo ready at: $APP_DIR"
echo ""

# ─── Step 5: Create .env ───
echo ">>> Step 5: Creating .env for production..."
cat > "$APP_DIR/.env" << 'ENVEOF'
APP_NAME="SETU Suvidha"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Kolkata
APP_URL=https://setusuvidha.com
APP_KEY=

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
ENVEOF

# Append DB credentials (not using heredoc to avoid variable issues)
echo "DB_DATABASE=$DB_NAME" >> "$APP_DIR/.env"
echo "DB_USERNAME=$DB_USER" >> "$APP_DIR/.env"
echo "DB_PASSWORD=$DB_PASS" >> "$APP_DIR/.env"

cat >> "$APP_DIR/.env" << 'ENVEOF2'

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@setusuvidha.com"
MAIL_FROM_NAME="SETU Suvidha"

RAZORPAY_KEY_ID=
RAZORPAY_KEY_SECRET=
RAZORPAY_WEBHOOK_SECRET=

FILESYSTEM_DISK=public
ENVEOF2

echo ".env created."
echo ""

# ─── Step 6: Install dependencies ───
echo ">>> Step 6: Installing PHP dependencies..."
cd "$APP_DIR"
composer install --no-dev --optimize-autoloader --no-interaction
echo ""

echo ">>> Step 6b: Installing Node.js dependencies & building..."
npm ci --production=false
npm run build
echo "Vite build complete."
echo ""

# ─── Step 7: Laravel setup ───
echo ">>> Step 7: Laravel setup..."
php artisan key:generate --force
php artisan storage:link
php artisan migrate --force
php artisan db:seed --force 2>/dev/null || echo "Seeders skipped (may already be seeded)"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
echo "Laravel setup complete."
echo ""

# ─── Step 8: Set permissions ───
echo ">>> Step 8: Setting file permissions..."
sudo chown -R www-data:www-data "$APP_DIR"
sudo chmod -R 755 "$APP_DIR"
sudo chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
echo "Permissions set."
echo ""

# ─── Step 9: Nginx configuration ───
echo ">>> Step 9: Configuring Nginx..."
NGINX_CONF="/etc/nginx/sites-available/$DOMAIN"
sudo tee "$NGINX_CONF" > /dev/null << NGINXEOF
server {
    listen 80;
    listen [::]:80;
    server_name $DOMAIN www.$DOMAIN;
    root ${APP_DIR}/public;

    index index.php index.html;

    charset utf-8;
    client_max_body_size 20M;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php\$ {
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
NGINXEOF

# Enable site
sudo ln -sf "$NGINX_CONF" /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
echo "Nginx configured and reloaded."
echo ""

# ─── Step 10: SSL with Let's Encrypt ───
echo ">>> Step 10: Setting up SSL..."
if command -v certbot &> /dev/null; then
    sudo certbot --nginx -d "$DOMAIN" -d "www.$DOMAIN" --non-interactive --agree-tos --email admin@setusuvidha.com --redirect 2>/dev/null || echo "SSL setup may need manual intervention"
else
    echo "Installing Certbot..."
    sudo apt-get install -y certbot python3-certbot-nginx
    sudo certbot --nginx -d "$DOMAIN" -d "www.$DOMAIN" --non-interactive --agree-tos --email admin@setusuvidha.com --redirect 2>/dev/null || echo "SSL setup may need manual intervention"
fi
echo ""

# ─── Step 11: Setup Cron for scheduler ───
echo ">>> Step 11: Setting up Laravel scheduler cron..."
CRON_CMD="* * * * * cd $APP_DIR && php artisan schedule:run >> /dev/null 2>&1"
(crontab -l 2>/dev/null | grep -v "artisan schedule:run"; echo "$CRON_CMD") | crontab -
echo "Cron job added."
echo ""

# ─── Done! ───
echo ""
echo "═══════════════════════════════════════════════════"
echo "  ✅ DEPLOYMENT COMPLETE!"
echo ""
echo "  🌐 Site: https://$DOMAIN"
echo "  📁 Path: $APP_DIR"
echo "  🗃️  DB:   $DB_NAME"
echo ""
echo "  Next steps:"
echo "  1. Point your domain DNS A record to: $(curl -s ifconfig.me)"
echo "  2. Add Razorpay keys in .env if needed"
echo "  3. Add SMTP credentials in .env for email"
echo "  4. Register admin user at https://$DOMAIN/register"
echo "═══════════════════════════════════════════════════"
echo ""
