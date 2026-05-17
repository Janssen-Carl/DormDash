# Database Seed Files

SQL seed scripts for populating the `dormdash_v4_migration` database with sample data.

## Prerequisites

- Docker container `dormdash-db` must be running
- Laravel migrations must have been run first (`php artisan migrate`)

```bash
docker-compose up -d db
php artisan migrate
```

## Seed Files (run in order)

| # | File | Contents |
|---|------|----------|
| 01 | `seed_01_base.sql` | 8 users, 8 addresses, 5 vendors, 3 customers, 10 categories |
| 02 | `seed_02_vendor1_snackshack.sql` | Snack Shack – 10 items (snacks, noodles, coffee) |
| 03 | `seed_03_vendor2_dormbites.sql` | Dorm Bites – 10 items (canned goods, milk, bread) |
| 04 | `seed_04_vendor3_campuspantry.sql` | Campus Pantry – 10 items (school supplies) |
| 05 | `seed_05_vendor4_quickmart.sql` | Quick Mart – 10 items (personal care) |
| 06 | `seed_06_vendor5_freshhub.sql` | Fresh Hub – 10 items (dorm essentials) |
| 07 | `seed_07_relations.sql` | 5 pages, 25 page_items, 6 carts, 4 orders, 9 order_items, 4 payments, 4 discounts |

## How to Run

> **Important:** PowerShell does not support the `<` redirect operator. Use `cmd /c` with `type` piping instead.

### Option A – Run all seeds at once (from `database/init/` directory)

```powershell
# Navigate to the seed directory
cd source\database\init

# Truncate existing data first (clean slate)
cmd /c "echo SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE payment_transactions; TRUNCATE TABLE order_items; TRUNCATE TABLE orders; TRUNCATE TABLE carts; TRUNCATE TABLE discounts; TRUNCATE TABLE stock_logs; TRUNCATE TABLE item_images; TRUNCATE TABLE page_items; TRUNCATE TABLE bundle_items; TRUNCATE TABLE category_items; TRUNCATE TABLE items; TRUNCATE TABLE pages; TRUNCATE TABLE categories; TRUNCATE TABLE customers; TRUNCATE TABLE vendors; TRUNCATE TABLE addresses; TRUNCATE TABLE users; SET FOREIGN_KEY_CHECKS=1; | docker exec -i dormdash-db mysql -u root -ppass dormdash_v4_migration"

# Run each seed in order
cmd /c "type seed_01_base.sql | docker exec -i dormdash-db mysql -u root -ppass dormdash_v4_migration"
cmd /c "type seed_02_vendor1_snackshack.sql | docker exec -i dormdash-db mysql -u root -ppass dormdash_v4_migration"
cmd /c "type seed_03_vendor2_dormbites.sql | docker exec -i dormdash-db mysql -u root -ppass dormdash_v4_migration"
cmd /c "type seed_04_vendor3_campuspantry.sql | docker exec -i dormdash-db mysql -u root -ppass dormdash_v4_migration"
cmd /c "type seed_05_vendor4_quickmart.sql | docker exec -i dormdash-db mysql -u root -ppass dormdash_v4_migration"
cmd /c "type seed_06_vendor5_freshhub.sql | docker exec -i dormdash-db mysql -u root -ppass dormdash_v4_migration"
cmd /c "type seed_07_relations.sql | docker exec -i dormdash-db mysql -u root -ppass dormdash_v4_migration"
```

### Option B – Run individually (useful for debugging)

```powershell
cmd /c "type seed_01_base.sql | docker exec -i dormdash-db mysql -u root -ppass dormdash_v4_migration"
```

## Placeholder Images

Run the PowerShell script to set up placeholder images in `public/images/`:

```powershell
powershell -ExecutionPolicy Bypass -File database\init\setup_placeholders.ps1
```

This creates:
- `public/images/vendors/{1-5}/cover.jpg` and `profile.jpg`
- `public/images/items/{1-50}/1.jpg`

Replace these with real images as needed.

## Test Accounts

All seed users use this password hash (plaintext: **`password`**):

| Username | Role | Email |
|----------|------|-------|
| `snack_shack` | vendor | snackshack@example.com |
| `dorm_bites` | vendor | dormbites@example.com |
| `campus_pantry` | vendor | campuspantry@example.com |
| `quick_mart` | vendor | quickmart@example.com |
| `fresh_hub` | vendor | freshhub@example.com |
| `juan_cruz` | customer | juan@example.com |
| `maria_santos` | customer | maria@example.com |
| `carlo_reyes` | customer | carlo@example.com |

## Image Path Template

```
Vendor cover:   /images/vendors/{vendor_id}/cover.jpg
Vendor profile: /images/vendors/{vendor_id}/profile.jpg
Item image:     /images/items/{item_id}/1.jpg
```

## Notes

- Top-level categories use self-referencing `parent_id` because the migration column is NOT NULL.
- The `seed_all.sql` master file uses `SOURCE` which only works inside the MySQL CLI directly, not via piped input. Use Option A above instead.
- DB connection: `127.0.0.1:3307`, user `root`, password `pass`.
