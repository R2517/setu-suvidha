# SETU Suvidha — महा ई-सेवा पोर्टल

SaaS platform for Maharashtra VLE (Village Level Entrepreneur) centers — government forms, wallet, CRM, and admin panel.

## Tech Stack
- **Backend:** Laravel 11, PHP 8.2+, MySQL 8.0
- **Frontend:** Tailwind CSS v3.4, Alpine.js, Lucide Icons
- **Auth:** Laravel Breeze
- **Payments:** Razorpay

## Setup

```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Build frontend assets
npm run build

# Start dev server
php artisan serve --port=8080
```

## Default Admin Login
- **Email:** admin@setusuvidha.com
- **Password:** admin123

## Features
- 11 public pages (Home, About, Services, FAQ, Contact, etc.)
- VLE Dashboard with 18 service cards, theme picker, wallet
- 10 government forms with Marathi UI + A4 print views
- CRM modules (PAN Card, Voter ID, Bandkam Kamgar)
- Admin panel (VLEs, Pricing, Plans, Transactions, Settings)
- Razorpay wallet recharge
- Dark mode support
