# 🏪 DormDash — Campus Dorm Food Delivery Platform

🚀 A dual-portal e-commerce platform connecting campus vendors with dormitory customers. Built with **Laravel 13** (PHP 8.4) on the backend, **Tailwind CSS 4 + Alpine.js** on the frontend, and a 🐍 **Python FastAPI** microservice for ML-powered analytics forecasting.

---

## 🏗️ Architecture Overview
![Architecture](assets/architecture.png)
### 🐳 Docker Services

| Service | Container | Image | Port | Purpose |
|---------|-----------|-------|------|---------|
| `app` | dormdash-app | 🐘 php:8.4-fpm-alpine | 9000 | Laravel backend |
| `nginx` | dormdash-nginx-laravel | 🌐 nginx:alpine | 8080→80 | Web server |
| `db` | dormdash-db | 🗄️ mysql:8.0 | 3307→3306 | Database |
| `node` | dormdash-node | 🟢 node:20-alpine | 5173 | Vite dev server |

### 🔀 Data Flow

```
User Request ──→ 🌐 Nginx ──→ 🐘 PHP-FPM ──→ 🗄️ MySQL
                                      ↕
                              🐍 Python AI API ──→ 🗄️ MySQL (reads for forecasting)
```

### 👥 Role-Based Access

![Role-Based Access](assets/roles.png)

---

## Database Schema (ERD)

### 📊 Core Tables

```
add erd here
```

### 🔗 Key Relationships

- **users**: Base identity table with `role` enum (`customer` | `vendor`)
- **customers/vendors**: One-to-one with `users` via shared primary key
- **items**: Belongs to a `vendor`; can be a single product or a `bundle` (self-referencing via `bundle_items`)
- **orders**: Have a status workflow and line items via `order_items` (pivot with quantity + price-at-purchase)
- **addresses**: Shared table — linked to vendors (via `vendors.address_id`) or customers (via `customers.primary_address_id`)

### 🔄 Order Status Workflow

![Order Status](assets/order-processing.png)

---

## 🚀 Running the Application

### 📋 Prerequisites

- Docker & Docker Compose
- Python 3.10+ (for the AI analytics service)
- PHP 8.3+ (if running locally)

### 1️⃣ Start the Docker Stack

```bash
cd /home/jhan/Codes/source
docker compose up -d
```

This starts four services:

| What | ⏱️ When ready |
|------|------------|
| 🗄️ MySQL on `localhost:3307` | ~15–30s |
| 🐘 Laravel PHP-FPM | ~5s after DB |
| 🌐 Nginx on `localhost:8080` | immediately |
| ⚡ Vite dev server on `localhost:5173` | ~30–60s (npm install + build) |

### 2️⃣ Apply Database Schema & Seed Data

SQL init scripts in `database/init/` run automatically on first `docker compose up` (mounted to `/docker-entrypoint-initdb.d`). They execute in filename order:

1. `schema_db_dash_v3.sql` — Full schema (tables, indexes, constraints)
2. `seed_00_order_data.sql` — 60+ historical orders for AI demo
3. `seed_01_base.sql` — Users, vendors, customers
4. `seed_02_vendor1_snackshack.sql` through `seed_06_vendor5_freshhub.sql` — Products per vendor
5. `seed_07_relations.sql` — Pivot table relations
6. `seed_08_ai_demo.sql` — Additional AI demo data

> **Note:** The Laravel route cache at `bootstrap/cache/routes-v7.php` is permission-locked. New routes added to `routes/web.php` won't be recognized — use existing cached URIs for forms.

### 3️⃣ Run Laravel Migrations (if needed separately)

```bash
docker compose exec app php artisan migrate
```

### 4️⃣ Start the Python AI Analytics Service

```bash
# Activate the virtual environment
source /path/to/env/activate

# Start the FastAPI server
cd app/AI
uvicorn main:app --host 0.0.0.0 --port 5000 --reload
```

The AI API connects to MySQL at `host.docker.internal:3307` (from inside Docker) or `localhost:3307` (from host). Configure via the `AI_DB_URL` environment variable.

### 5️⃣ Access the Application

- **Web App:** http://localhost:100%8080
- **AI API Health:** http://localhost:5000/health
- **Vite Dev Server:** http://localhost:5173 (auto-proxied by Nginx)

### 6️⃣ Seed Test Accounts

| Username | Role | Password |
|----------|------|----------|
| `snack_shack` | Vendor | (bcrypt — check SQL seed) |
| `dorm_bites` | Vendor | (bcrypt — check SQL seed) |
| `campus_pantry` | Vendor | (bcrypt — check SQL seed) |
| `quick_mart` | Vendor | (bcrypt — check SQL seed) |
| `fresh_hub` | Vendor | (bcrypt — check SQL seed) |
| `juan_cruz` | Customer | (bcrypt — check SQL seed) |
| `maria_santos` | Customer | (bcrypt — check SQL seed) |
| `carlo_reyes` | Customer | (bcrypt — check SQL seed) |

🔑 Quick login for testing (any user ID):
```
http://localhost:8080/test-login/1   (vendor: snack_shack 🏪)
http://localhost:8080/test-login/6   (customer: juan_cruz 👤)
```

---

## 📄 Key Pages (Vendor Portal)

| URL | Page | Description |
|-----|------|-------------|
| `/vendor-home` | Dashboard | Overview with order stats, product counts |
| `/vendor-products` | Product Inventory | Manage products, stock, bundles; search & filter |
| `/vendor-products/{id}/edit` | Edit Product | Update price, stock, unit, availability |
| `/vendor-orders` | Active Orders | View & fulfill orders (confirm → ship → deliver) |
| `/vendor-profile` | Profile | View vendor info, addresses, upload photo |
| `/vendor-profile/vendor-profile-edit` | Edit Profile | Update brand name, email, phone, website, password |
| `/vendor-profile/vendor-address-add` | Add Address | Add a business address |
| `/vendor-analytics` | Analytics Dashboard | Revenue forecasting and inventory alerts |

## 🛍️ Key Pages (Customer Portal)

| URL | Page | Description |
|-----|------|-------------|
| `/products` | Browse/Search | Product catalog with filters |
| `/cart` | Shopping Cart | Manage cart items |
| `/checkout` | Checkout | Place order with payment |
| `/orders` | Order History | View past orders, track deliveries |
| `/profile` | Profile | Manage personal info, addresses, payment methods |

---

## 🤖 Python AI Analytics Service

### 🧠 Overview

A lightweight FastAPI microservice at `app/AI/main.py` that reads from the same MySQL database and runs linear regression forecasts. It is completely independent of Laravel — it connects directly to MySQL via SQLAlchemy + PyMySQL.

### ⚙️ How It Works

![Data Flow Diagram](assets/dfd.png)

The `DashboardController@index` method calls the AI API via HTTP. It tries multiple candidate URLs (`host.docker.internal:5000`, `127.0.0.1:5000`) to handle Docker vs. host networking differences.

### 📡 API Endpoints

#### 📈 `GET /forecast/revenue`
Daily revenue forecast using linear regression.

| Param | Default | Description |
|-------|---------|-------------|
| `vendor_id` | — (required) | Vendor to analyze |
| `days` | 90 | Historical days to train on |
| `horizon` | 1 | Days to forecast ahead |

**Response:** `{ predicted: [floats], dates: [strings], historical: [{order_date, revenue}] }`

#### 📊 `GET /forecast/items`
Revenue forecast for the top N items.

| Param | Default | Description |
|-------|---------|-------------|
| `vendor_id` | — (required) | Vendor to analyze |
| `days` | 90 | Historical days |
| `horizon` | 1 | Forecast horizon |
| `top` | 5 | Number of top items |

**Response:** `[{ item_id, predicted: [floats], total_predicted: float, name: string }]`

#### 📉 `GET /forecast/summary`
Compact single-number summary.

| Param | Default | Description |
|-------|---------|-------------|
| `vendor_id` | — (required) | Vendor to analyze |
| `days` | 90 | Historical days |
| `horizon` | 1 | Forecast horizon |

**Response:** `{ predicted_next_day: float, last_day: float, percent_change: float }`

#### ⚠️ `GET /inventory/alerts`
Items with stock below a threshold.

| Param | Default | Description |
|-------|---------|-------------|
| `vendor_id` | — (required) | Vendor to analyze |
| `threshold` | 10 | Stock threshold |

**Response:** `[{ item_id, name, stock }]`

### 🏃 Running the AI Service

```bash
# Terminal 1 — ensure DB is running
docker compose up -d db

# Terminal 2 — start AI server
source .ddvenv/bin/activate
cd app/AI
uvicorn main:app --host 0.0.0.0 --port 5000 --reload

# Test it
curl http://localhost:5000/health
curl "http://localhost:5000/forecast/summary?vendor_id=1"
curl "http://localhost:5000/inventory/alerts?vendor_id=1&threshold=10"
```

### 🔧 Environment Variables for AI

| Variable | Default | Description |
|----------|---------|-------------|
| `AI_DB_URL` | `mysql+pymysql://root:pass@localhost:3307/dormdash_db_v4` | Database connection string |

### 🧮 ML Model

Uses `sklearn.linear_model.LinearRegression`:
- **Revenue forecast:** Regresses daily order total against a day-index feature
- **Item forecast:** Same model per item, trained independently
- **Limitations:** Simple linear trend — does not capture seasonality, day-of-week effects, or promotions

---

## 🎨 Frontend Build

### 🛠️ Development (with HMR)

```bash
# Vite dev server runs automatically in Docker
# Or run locally:
npm install
npm run dev
```

### 📦 Production Build

```bash
npm run build
```

The build outputs to `public/build/` (versioned assets). Tailwind scans `resources/views/**/*.blade.php` and `resources/js/**/*.js` for class usage.

---

## 🎼 Composer Commands

```bash
# Full setup (install + migrate + build)
composer run setup

# Development mode (artisan serve + queue listen + logs + vite)
composer run dev

# Run tests
composer run test
```

---

## 📜 Composer Scripts (from composer.json)

```bash
composer run setup    # Full setup: install, key:generate, migrate, npm install + build
composer run dev      # Dev mode: artisan serve + queue:listen + pail + npm run dev
composer run test     # Run PHPUnit tests
```

---

## ✅ Testing

```bash
# PHPUnit tests
composer run test

# Or directly:
./vendor/bin/phpunit
```

---

## 📝 Development Notes

- **Route cache is locked** — `bootstrap/cache/routes-v7.php` is owned by uid 82 and cannot be deleted or rebuilt. All forms must POST to URIs that exist in the cached route file.
- **Queue** is set to `sync` in `.env` — all jobs run synchronously. The queue tables exist but are unused.
- **No scheduled tasks** are configured — no `Kernel.php` or cron setup.
