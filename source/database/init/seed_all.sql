-- =============================================
-- MASTER SEED RUNNER
-- Run this file to execute all seeds in FK order
-- Usage: mysql -u root -p dormdash_v4_migration < seed_all.sql
-- =============================================

SET FOREIGN_KEY_CHECKS = 0;

-- Truncate all tables (clean slate)
TRUNCATE TABLE payment_transactions;
TRUNCATE TABLE order_items;
TRUNCATE TABLE orders;
TRUNCATE TABLE carts;
TRUNCATE TABLE discounts;
TRUNCATE TABLE stock_logs;
TRUNCATE TABLE item_images;
TRUNCATE TABLE page_items;
TRUNCATE TABLE bundle_items;
TRUNCATE TABLE category_items;
TRUNCATE TABLE items;
TRUNCATE TABLE pages;
TRUNCATE TABLE categories;
TRUNCATE TABLE customers;
TRUNCATE TABLE vendors;
TRUNCATE TABLE addresses;
TRUNCATE TABLE users;

SET FOREIGN_KEY_CHECKS = 1;

SOURCE seed_01_base.sql;
SOURCE seed_02_vendor1_snackshack.sql;
SOURCE seed_03_vendor2_dormbites.sql;
SOURCE seed_04_vendor3_campuspantry.sql;
SOURCE seed_05_vendor4_quickmart.sql;
SOURCE seed_06_vendor5_freshhub.sql;
SOURCE seed_07_relations.sql;
SOURCE seed_08_ai_demo.sql;
