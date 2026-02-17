#!/bin/bash
# ══════════════════════════════════════════════════════════════════
# SETU Suvidha — Hostinger VPS Deployment (No Sudo)
# CloudLinux 8 / Hostinger Managed Hosting
# Domain: setusuvidha.com
# ══════════════════════════════════════════════════════════════════

set -e

DOMAIN="setusuvidha.com"
HOME_DIR="/home/u515436084"
DOMAIN_DIR="$HOME_DIR/domains/$DOMAIN"
HTDOCS_DIR="$DOMAIN_DIR/public_html"
APP_DIR="$HOME_DIR/setu-suvidha"
REPO_URL="https://github.com/R2517/setu-suvidha.git"
DB_NAME="u515436084_setu_suvidha"
DB_USER="u515436084_setu_suvidha"
DB_PASS='Rajat@19941996'
PHP_BIN="/opt/alt/php83/usr/bin/php"

echo ""
echo "═══════════════════════════════════════════════════"
echo "  SETU Suvidha — Hostinger Deployment"
echo "  Domain: $DOMAIN"
echo "═══════════════════════════════════════════════════"
echo ""

# ─── Step 1: Check environment ───
echo ">>> Step 1: Checking environment..."
$PHP_BIN -v | head -1
mysql --version 2>/dev/null || echo "MySQL client check skipped"
$PHP_BIN $(which composer) --version 2>/dev/null | head -1
echo ""

# ─── Step 2: Install Node.js via nvm (no sudo) ───
echo ">>> Step 2: Installing Node.js via nvm..."
export NVM_DIR="$HOME_DIR/.nvm"

if [ ! -d "$NVM_DIR" ]; then
    echo "Installing nvm..."
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash
fi

# Load nvm
export NVM_DIR="$HOME_DIR/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

if ! command -v node &> /dev/null; then
    echo "Installing Node.js 20 via nvm..."
    nvm install 20
    nvm use 20
    nvm alias default 20
fi
echo "Node.js: $(node -v)"
echo "npm: $(npm -v)"
echo ""

# ─── Step 3: Clone or Pull repo ───
echo ">>> Step 3: Setting up application code..."
if [ -d "$APP_DIR/.git" ]; then
    echo "Repo exists, pulling latest..."
    cd "$APP_DIR"
    git pull origin main
else
    echo "Cloning fresh repo..."
    if [ -d "$APP_DIR" ]; then
        mv "$APP_DIR" "${APP_DIR}_backup_$(date +%Y%m%d_%H%M%S)" 2>/dev/null || true
    fi
    git clone "$REPO_URL" "$APP_DIR"
    cd "$APP_DIR"
fi
echo "Repo ready at: $APP_DIR"
echo ""

# ─── Step 4: Symlink public folder to web root ───
echo ">>> Step 4: Setting up web root symlink..."

# Ensure domain directory exists
mkdir -p "$DOMAIN_DIR"

# Backup existing public_html if it's a real directory
if [ -d "$HTDOCS_DIR" ] && [ ! -L "$HTDOCS_DIR" ]; then
    echo "Backing up existing public_html..."
    mv "$HTDOCS_DIR" "${DOMAIN_DIR}/public_html_backup_$(date +%Y%m%d_%H%M%S)" 2>/dev/null || true
fi

# Remove existing symlink if any
rm -f "$HTDOCS_DIR" 2>/dev/null || true

# Create symlink: domains/setusuvidha.com/public_html -> setu-suvidha/public
ln -sf "$APP_DIR/public" "$HTDOCS_DIR"
echo "Symlink created: $HTDOCS_DIR -> $APP_DIR/public"
echo ""

# ─── Step 5: Create .env ───
echo ">>> Step 5: Creating .env for production..."
cat > "$APP_DIR/.env" << ENVEOF
APP_NAME="SETU Suvidha"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Kolkata
APP_URL=https://$DOMAIN
APP_KEY=

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASS

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
ENVEOF
echo ".env created."
echo ""

# ─── Step 6: Install PHP dependencies ───
echo ">>> Step 6: Installing PHP dependencies..."
cd "$APP_DIR"
$PHP_BIN $(which composer) install --no-dev --optimize-autoloader --no-interaction
echo ""

# ─── Step 7: Install Node.js dependencies & build ───
echo ">>> Step 7: Building frontend assets (Vite + Tailwind)..."
# Make sure nvm is loaded
export NVM_DIR="$HOME_DIR/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

npm ci
npm run build
echo "Vite build complete."
echo ""

# ─── Step 8: Laravel setup ───
echo ">>> Step 8: Laravel setup..."
$PHP_BIN artisan key:generate --force
$PHP_BIN artisan storage:link 2>/dev/null || echo "Storage link may already exist"
$PHP_BIN artisan migrate --force
$PHP_BIN artisan db:seed --force 2>/dev/null || echo "Seeders run (or already seeded)"
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
echo "Laravel setup complete."
echo ""

# ─── Step 9: Set permissions ───
echo ">>> Step 9: Setting file permissions..."
chmod -R 755 "$APP_DIR"
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
echo "Permissions set."
echo ""

# ─── Step 10: Add .htaccess for PHP routing ───
echo ">>> Step 10: Ensuring .htaccess is correct..."
# The public/.htaccess from Laravel should handle routing
# But ensure it exists
if [ ! -f "$APP_DIR/public/.htaccess" ]; then
    cat > "$APP_DIR/public/.htaccess" << 'HTEOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
HTEOF
    echo ".htaccess created."
else
    echo ".htaccess already exists."
fi
echo ""

# ─── Step 11: Setup Cron for scheduler ───
echo ">>> Step 11: Setting up Laravel scheduler cron..."
CRON_CMD="* * * * * cd $APP_DIR && $PHP_BIN artisan schedule:run >> /dev/null 2>&1"
(crontab -l 2>/dev/null | grep -v "artisan schedule:run"; echo "$CRON_CMD") | crontab -
echo "Cron job added."
echo ""

# ─── Step 12: Add nvm to bashrc for future sessions ───
echo ">>> Step 12: Ensuring nvm loads on login..."
if ! grep -q "NVM_DIR" "$HOME_DIR/.bashrc" 2>/dev/null; then
    echo '' >> "$HOME_DIR/.bashrc"
    echo '# NVM (Node Version Manager)' >> "$HOME_DIR/.bashrc"
    echo 'export NVM_DIR="$HOME/.nvm"' >> "$HOME_DIR/.bashrc"
    echo '[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"' >> "$HOME_DIR/.bashrc"
    echo "Added nvm to .bashrc"
else
    echo "nvm already in .bashrc"
fi
echo ""

# ─── Done! ───
echo ""
echo "═══════════════════════════════════════════════════"
echo "  DEPLOYMENT COMPLETE!"
echo ""
echo "  Site: https://$DOMAIN"
echo "  App:  $APP_DIR"
echo "  Web:  $HTDOCS_DIR -> $APP_DIR/public"
echo "  DB:   $DB_NAME"
echo ""
echo "  Next steps:"
echo "  1. Enable SSL in hPanel (SSL section)"
echo "  2. Add Razorpay keys: nano $APP_DIR/.env"
echo "  3. Register at https://$DOMAIN/register"
echo "  4. Future updates: bash $APP_DIR/redeploy.sh"
echo "═══════════════════════════════════════════════════"
echo ""
