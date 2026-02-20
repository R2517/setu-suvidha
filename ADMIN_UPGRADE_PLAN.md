# SETU Suvidha — Admin & VLE Dashboard Upgrade Plan

> Created: 20 Feb 2026 | Status: PLANNING

---

## Current State (What Exists)

### DB Tables
| Table | Key Columns |
|-------|-------------|
| `profiles` | full_name, email, shop_name, shop_type(setu/csc/other), mobile, address, district, taluka, wallet_balance, is_active, dashboard_config(json) |
| `form_pricing` | form_type, form_name, price, is_active — **VLE only, no public pricing** |
| `subscription_plans` | name, price, duration_days, features(json), is_active |
| `vle_subscriptions` | user_id, plan_id, start_date, end_date, status(active/expired/cancelled), razorpay_payment_id |
| `wallet_transactions` | user_id, amount, type(credit/debit), balance_after, description, reference_id |
| `form_submissions` | user_id, form_type, data(json), print_count |
| `billing_customers` | user_id, name, phone, email, aadhaar, address |
| `billing_services` | user_id, name, name_mr, category, purchase_price, sale_price |
| `billing_sales` | user_id, customer_id, items(json), subtotal, discount, total, payment_method, notes |

### Admin Views (basic, need upgrade)
- `admin/dashboard.blade.php` — 4 stat cards only (VLEs, active VLEs, revenue, forms)
- `admin/vles.blade.php` — Simple table (name, email, shop, wallet, toggle status)
- `admin/pricing.blade.php` — Simple table (form_type, name, price, toggle)
- `admin/plans.blade.php` — Plan cards with features, delete only
- `admin/transactions.blade.php` — Basic table (all wallet txns)
- `admin/settings.blade.php` — Placeholder (Razorpay key, versions only)
- `admin/farmer-card-orders.blade.php` — Public service orders

### VLE Dashboard Views
- `dashboard/index.blade.php` — 24 themes, service cards, wallet badge
- `dashboard/profile.blade.php` — Basic profile form
- `dashboard/wallet.blade.php` — Wallet page
- `dashboard/billing.blade.php` — Billing dashboard

---

## PHASE 1: DB Migrations (Foundation — Do First)
> New migration: `2026_02_20_200001_admin_vle_upgrade.php`

### 1A. `profiles` table — Add columns:
- `full_name_mr` VARCHAR(255) — Marathi name
- `shop_pic` VARCHAR(255) — Shop photo path
- `profile_pic` VARCHAR(255) — Profile photo path  
- `business_categories` JSON — Multi-select: csc, maha_e_seva, grahak_seva, cyber_cafe, xerox_center, aadhaar_center
- `csc_id` VARCHAR(50) — CSC ID number
- `setu_id` VARCHAR(50) — SETU ID number
- `whatsapp_same` BOOLEAN DEFAULT true — Is mobile = WhatsApp?
- `whatsapp_number` VARCHAR(15) — Separate WhatsApp if different
- `bank_name` VARCHAR(100)
- `account_number` VARCHAR(30)
- `ifsc_code` VARCHAR(20)
- `upi_id` VARCHAR(100)
- `qr_code_pic` VARCHAR(255) — Payment QR image
- `about_center` TEXT — SEO description
- `google_map_link` VARCHAR(500)
- `latitude` DECIMAL(10,8)
- `longitude` DECIMAL(11,8)
- `is_public_approved` BOOLEAN DEFAULT false — Admin approval for public listing

### 1B. `form_pricing` table — Add column:
- `audience` ENUM('vle','public') DEFAULT 'vle' — Separate VLE vs Public pricing

### 1C. `subscription_plans` table — Add columns:
- `plan_type` ENUM('monthly','quarterly','half_yearly','yearly')
- `discount_percent` DECIMAL(5,2) DEFAULT 0
- `trial_days` INT DEFAULT 15
- `razorpay_plan_id` VARCHAR(100) — For auto-mandate

### 1D. `vle_subscriptions` table — Add columns:
- `razorpay_subscription_id` VARCHAR(100)
- `trial_ends_at` TIMESTAMP
- `auto_renew` BOOLEAN DEFAULT true

---

## PHASE 2: Admin Panel Upgrades (6 pages)

### 2A. /admin/dashboard — Enhanced Stats
- **Row 1 Cards:** Total VLEs, Active VLEs, Total Revenue (wallet+plans), Total Forms Created
- **Row 2 Cards:** Active Plans (subscriptions), Pending Approvals, Today's Transactions, Low Balance VLEs (<₹30)
- **Recent Activity:** Last 10 transactions
- **Quick Stats:** Plans breakdown chart (monthly/quarterly/half/yearly)

### 2B. /admin/vles — Full VLE Management
- **Search & Filter:** By name, email, district, taluka, status, plan type
- **Table Columns:** #, Name, Shop, District, Wallet, Plan, Status, Actions
- **Actions:** View Profile (slide-out or new page), Add/Reduce Balance, Toggle Active
- **VLE Profile Page** (`/admin/vles/{id}`):
  - Full profile details (all new fields)
  - Sales history (billing_sales count + total)
  - Forms created count
  - Wallet transaction history
  - Subscription status
  - Balance adjustment form (+/- with reason)
  - Public profile approval toggle

### 2C. /admin/pricing — VLE vs Public Pricing
- **Tab/Switch:** VLE Section ↔ Public Section
- **VLE Section:** All form_pricing where audience='vle' — edit price, toggle active
- **Public Section:** All form_pricing where audience='public' — edit price, toggle active
- **Add New Pricing** form at bottom
- Move Farmer Card Orders link to Public section

### 2D. /admin/plans — Subscription Plan Management
- **4 Default Plans:**
  - Monthly ₹199 / 30 days
  - Quarterly ₹399 / 90 days
  - Half-Yearly ₹699 / 180 days
  - Yearly ₹1099 / 365 days
- **Each Plan Card:** Name, price, type, features list, discount, trial days, Razorpay plan ID
- **Edit inline:** Price, features, discount, Razorpay keys
- **CRUD:** Create, Update, Delete plans

### 2E. /admin/transactions — Wallet + Plan Transactions
- **Filter:** By type (wallet top-up, plan purchase, form debit), date range, VLE name
- **Show only:** Plan purchases + wallet add — NOT service form deductions
- **Columns:** #, VLE Name, Type (Plan/Wallet), Description, Amount, Balance After, Date

### 2F. /admin/settings — Full Settings Page
- **Section 1: Razorpay Config** — Key ID (masked), update button
- **Section 2: Author Management** — Show/Edit all `/author` page data (name, bio, contacts)
- **Section 3: System Info** — Laravel version, PHP version, App version
- **Section 4: Error Logs** — Last 20 Laravel log entries (from storage/logs)
- **Section 5: Site Health** — Uptime, cache status, queue status

---

## PHASE 3: VLE Dashboard Fixes

### 3A. Theme Switcher Fix
- Debug and fix the theme selection in `dashboard/index.blade.php`
- Ensure themes persist and apply properly

### 3B. Wallet Widget Upgrade
- Show ₹ amount with + button beside it
- Click + → redirect to wallet page or open add-amount popup
- Min maintenance balance: ₹30 (show warning if below)
- Min wallet add: ₹50
- Bold, hover effects, clear CTA styling

### 3C. Dashboard Customize Dropdown Fix
- Fix 'डॅशबोर्ड कस्टमाइज करा' dropdown animation
- Smooth open/close with Alpine.js transition

### 3D. Floating Billing Button (ALL VLE Pages)
- Floating amber button (bottom-right), draggable
- On click → popup with 2 options: "+New Sale" and "Kiosk"
- Clicking either opens the full billing form in a modal
- Support PDF download + thermal/A4 print
- Add to `layouts/app.blade.php` (only for auth VLE users)

---

## PHASE 4: Billing Module Upgrades

### 4A. Popup Modals (No Redirect)
- Add Sale, Add Expense, Kiosk → open as modals on /billing page itself
- Use Alpine.js for modal management

### 4B. Payment Methods
- Remove UPI button, keep: Cash, Online, Split
- Split = partial cash + partial online

### 4C. Searchable Services
- Add search/filter input above services list in New Sale modal
- Use Alpine.js x-model for instant filtering

### 4D. Invoice Details
- Add shop details: name, address, mobile, email
- Add payment QR code (from profile)
- Profile QR and Payment QR are separate

### 4E. Kiosk Book Redesign
- Modern attractive design for kiosk-book page

### 4F. CSV Service Upload
- Upload CSV to bulk-add/update services
- Format: English Name, Marathi Name, Category, Purchase Price, Sale Price
- Sheet name = Category
- Provide pre-filled CSC sample CSV for download

### 4G. Customer Management
- Due payment tracking per customer
- Link by phone number or Aadhaar
- Manage payments and services per customer

---

## PHASE 5: Profile Upgrade + Public VLE Directory

### 5A. Profile — New Fields
- Shop pic upload (mandatory) + profile pic
- Full name English + Marathi
- Business category multi-select
- CSC ID, SETU ID
- Mobile + WhatsApp toggle
- Bank & UPI details (for invoice)
- About Your Center textarea
- Google Maps link / lat-lng

### 5B. Public VLE Directory Page
- `/setu-suvidha-kendra` — List all approved VLEs
- Filter by district + taluka
- Verified badge for approved VLEs
- SEO optimized

### 5C. Individual VLE Public Profile
- `/setu-suvidha-kendra/{slug}` — Each VLE's public page
- Services offered, address, contact, map
- SEO friendly with schema markup

### 5D. Admin Approval Workflow
- VLE profile changes need admin approval
- Show pending requests in /admin/vles
- Approve/reject with one click

---

## Execution Order (Recommended)

| Step | Task | Depends On | Est. Effort |
|------|------|-----------|-------------|
| 1 | Phase 1: Migrations | — | Small |
| 2 | Phase 2A: Admin Dashboard | Phase 1 | Medium |
| 3 | Phase 2D: Plans page | Phase 1 | Medium |
| 4 | Phase 2C: Pricing page | Phase 1 | Medium |
| 5 | Phase 2B: VLE management | Phase 1 | Large |
| 6 | Phase 2E: Transactions | Phase 1 | Small |
| 7 | Phase 2F: Settings | Phase 1 | Medium |
| 8 | Phase 3A-C: Dashboard fixes | — | Medium |
| 9 | Phase 3D: Floating button | — | Medium |
| 10 | Phase 4A-C: Billing modals | — | Large |
| 11 | Phase 4D-G: Billing extras | Phase 4A | Medium |
| 12 | Phase 5A: Profile upgrade | Phase 1 | Medium |
| 13 | Phase 5B-D: Public directory | Phase 5A | Large |

**Total: ~25-30 files changed/created, 1 migration, 13 execution steps**
