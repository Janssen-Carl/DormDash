-- ============================================================
-- SEEDERS: multivendor_db (matched to actual schema)
-- ============================================================
-- Run in order to respect foreign key constraints.
-- ============================================================

USE `multivendor_db`;

-- ------------------------------------------------------------
-- 1. USERS
-- (password_hash, contact_number columns per actual schema)
-- ------------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `contact_number`) VALUES
('u-0001', 'Alice Reyes',   'alice@example.com',   '$2y$10$hashedpassword1', 'customer', '09171234561'),
('u-0002', 'Bob Santos',    'bob@example.com',     '$2y$10$hashedpassword2', 'customer', '09171234562'),
('u-0003', 'Carlos Mendez', 'carlos@example.com',  '$2y$10$hashedpassword3', 'vendor',   '09171234563'),
('u-0004', 'Diana Cruz',    'diana@example.com',   '$2y$10$hashedpassword4', 'vendor',   '09171234564'),
('u-0005', 'Eve Lopez',     'eve@example.com',     '$2y$10$hashedpassword5', 'admin',    '09171234565');


-- ------------------------------------------------------------
-- 2. USER ADDRESSES
-- (label, address_line, is_default columns per actual schema)
-- ------------------------------------------------------------
INSERT INTO `user_addresses` (`id`, `user_id`, `label`, `address_line`, `is_default`) VALUES
('ua-0001', 'u-0001', 'Home',    '456 Mabini Ave, Quezon City',      1),
('ua-0002', 'u-0001', 'Current', '123 Rizal St, Manila',             0),
('ua-0003', 'u-0002', 'Home',    '321 Luna St, Pasig',               1),
('ua-0004', 'u-0002', 'Work',    '789 Bonifacio Blvd, Makati',       0),
('ua-0005', 'u-0003', 'Home',    '55 Aguinaldo Rd, Cavite',          1),
('ua-0006', 'u-0004', 'Home',    '10 Roxas Blvd, Manila',            1);


-- ------------------------------------------------------------
-- 3. VENDORS
-- ------------------------------------------------------------
INSERT INTO `vendors` (`id`, `user_id`, `store_name`, `description`) VALUES
('v-0001', 'u-0003', 'Carlos Tech Shop', 'Gadgets and accessories at great prices.'),
('v-0002', 'u-0004', 'Diana''s Apparel', 'Trendy clothing for all occasions.');


-- ------------------------------------------------------------
-- 4. CATEGORIES
-- ------------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES
('cat-0001', 'Electronics',   NULL),
('cat-0002', 'Mobile Phones', 'cat-0001'),
('cat-0003', 'Accessories',   'cat-0001'),
('cat-0004', 'Clothing',      NULL),
('cat-0005', 'Tops',          'cat-0004'),
('cat-0006', 'Bottoms',       'cat-0004');


-- ------------------------------------------------------------
-- 5. PRODUCTS
-- ------------------------------------------------------------
INSERT INTO `products` (`id`, `vendor_id`, `name`, `description`, `price`, `stock_qty`, `is_active`, `category_id`) VALUES
('p-0001', 'v-0001', 'Smartphone X1',      'Latest Android smartphone.',          12999.00, 50,  1, 'cat-0002'),
('p-0002', 'v-0001', 'Wireless Earbuds',   'Noise-cancelling Bluetooth earbuds.',  1999.00, 100, 1, 'cat-0003'),
('p-0003', 'v-0001', 'Phone Case X1',      'Durable silicone case for X1.',         299.00, 200, 1, 'cat-0003'),
('p-0004', 'v-0002', 'Classic White Tee',  '100% cotton plain white t-shirt.',      499.00, 150, 1, 'cat-0005'),
('p-0005', 'v-0002', 'Slim Fit Jeans',     'Comfortable slim fit denim jeans.',    1299.00, 80,  1, 'cat-0006'),
('p-0006', 'v-0001', 'USB-C Charging Cable','Fast charging 2m USB-C cable.',        199.00, 300, 1, 'cat-0003');


-- ------------------------------------------------------------
-- 6. PRODUCT VARIANTS
-- (no DEFAULT uuid() in schema so IDs are explicit)
-- ------------------------------------------------------------
INSERT INTO `product_variants` (`id`, `product_id`, `name`, `value`, `price_modifier`, `stock_qty`, `is_active`) VALUES
-- Smartphone X1: Storage
('pv-0001', 'p-0001', 'Storage', '128GB',    0.00,   20, 1),
('pv-0002', 'p-0001', 'Storage', '256GB', 1000.00,   20, 1),
('pv-0003', 'p-0001', 'Storage', '512GB', 2500.00,   10, 1),
-- Classic White Tee: Size
('pv-0004', 'p-0004', 'Size', 'S',  0.00, 40, 1),
('pv-0005', 'p-0004', 'Size', 'M',  0.00, 50, 1),
('pv-0006', 'p-0004', 'Size', 'L',  0.00, 40, 1),
('pv-0007', 'p-0004', 'Size', 'XL', 0.00, 20, 1),
-- Slim Fit Jeans: Size
('pv-0008', 'p-0005', 'Size', '28', 0.00, 15, 1),
('pv-0009', 'p-0005', 'Size', '30', 0.00, 25, 1),
('pv-0010', 'p-0005', 'Size', '32', 0.00, 25, 1),
('pv-0011', 'p-0005', 'Size', '34', 0.00, 15, 1),
-- Wireless Earbuds: Color
('pv-0012', 'p-0002', 'Color', 'Black',   0.00, 40, 1),
('pv-0013', 'p-0002', 'Color', 'White',   0.00, 35, 1),
('pv-0014', 'p-0002', 'Color', 'Blue',  100.00, 25, 1);


-- ------------------------------------------------------------
-- 7. BUNDLES
-- ------------------------------------------------------------
INSERT INTO `bundles` (`id`, `vendor_id`, `name`, `description`, `bundle_price`, `is_active`) VALUES
('b-0001', 'v-0001', 'Phone Starter Kit', 'Smartphone X1 + Earbuds + Case bundle.', 14799.00, 1),
('b-0002', 'v-0002', 'Casual Outfit Set',  'Classic White Tee + Slim Fit Jeans.',    1699.00, 1);


-- ------------------------------------------------------------
-- 8. BUNDLE ITEMS
-- ------------------------------------------------------------
INSERT INTO `bundle_items` (`id`, `bundle_id`, `product_id`, `quantity_required`) VALUES
('bi-0001', 'b-0001', 'p-0001', 1),
('bi-0002', 'b-0001', 'p-0002', 1),
('bi-0003', 'b-0001', 'p-0003', 1),
('bi-0004', 'b-0002', 'p-0004', 1),
('bi-0005', 'b-0002', 'p-0005', 1);


-- ------------------------------------------------------------
-- 9. DISCOUNTS
-- (type ENUM is 'percentage'|'fixed_amount' per actual schema)
-- ------------------------------------------------------------
INSERT INTO `discounts` (`id`, `vendor_id`, `code`, `type`, `value`, `target_type`, `target_id`, `expires_at`, `is_active`) VALUES
('d-0001', 'v-0001', 'TECH10',    'percentage',  10.00, 'product', 'p-0001', '2026-12-31 23:59:59', 1),
('d-0002', 'v-0001', 'BUNDLE50',  'fixed_amount', 50.00, 'bundle', 'b-0001', '2026-06-30 23:59:59', 1),
('d-0003', 'v-0002', 'FASHION15', 'percentage',  15.00, 'product', 'p-0004', '2026-09-30 23:59:59', 1);


-- ------------------------------------------------------------
-- 10. CART
-- (no DEFAULT uuid() in schema so IDs are explicit)
-- ------------------------------------------------------------
INSERT INTO `cart` (`id`, `user_id`) VALUES
('c-0001', 'u-0001'),
('c-0002', 'u-0002');


-- ------------------------------------------------------------
-- 11. CART ITEMS
-- ------------------------------------------------------------
INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `variant_id`, `bundle_id`, `quantity`) VALUES
-- Alice: Smartphone X1 (256GB) + Casual Outfit bundle
('ci-0001', 'c-0001', 'p-0001', 'pv-0002', NULL,     1),
('ci-0002', 'c-0001',  NULL,     NULL,     'b-0002',  1),
-- Bob: Earbuds (Blue) + Jeans (Size 32)
('ci-0003', 'c-0002', 'p-0002', 'pv-0014', NULL,     1),
('ci-0004', 'c-0002', 'p-0005', 'pv-0010', NULL,     2);


-- ------------------------------------------------------------
-- 12. ORDERS
-- (status ENUM is 'pending'|'paid'|'completed'|'cancelled')
-- (address_id references user_addresses)
-- ------------------------------------------------------------
INSERT INTO `orders` (`id`, `user_id`, `discount_id`, `subtotal`, `discount_amount`, `total`, `status`, `address_id`, `delivery_type`) VALUES
('o-0001', 'u-0001', 'd-0001', 13999.00, 1399.90, 12599.10, 'completed',  'ua-0001', 'delivery'),
('o-0002', 'u-0001',  NULL,    14799.00,    0.00, 14799.00, 'completed',  'ua-0001', 'delivery'),
('o-0003', 'u-0002', 'd-0003',  1798.00,  269.70,  1528.30, 'paid',       'ua-0003', 'pickup');


-- ------------------------------------------------------------
-- 13. ORDER ITEMS
-- (variant_id added per migration)
-- ------------------------------------------------------------
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variant_id`, `bundle_id`, `quantity`, `unit_price`, `discount_amount`, `total`) VALUES
-- Order 1: Smartphone X1 (256GB) with 10% discount
('oi-0001', 'o-0001', 'p-0001', 'pv-0002', NULL,     1, 13999.00, 1399.90, 12599.10),
-- Order 2: Phone Starter Kit bundle
('oi-0002', 'o-0002',  NULL,     NULL,     'b-0001', 1, 14799.00,    0.00, 14799.00),
-- Order 3: White Tee (M) + Slim Fit Jeans (30) with 15% discount
('oi-0003', 'o-0003', 'p-0004', 'pv-0005', NULL,     1,   499.00,  74.85,   424.15),
('oi-0004', 'o-0003', 'p-0005', 'pv-0009', NULL,     1,  1299.00, 194.85,  1104.15);


-- ------------------------------------------------------------
-- 14. PAYMENTS
-- (status ENUM is 'pending'|'paid'|'failed'|'refunded')
-- (UNIQUE on order_id — one payment per order)
-- ------------------------------------------------------------
INSERT INTO `payments` (`id`, `order_id`, `method`, `status`, `amount`, `reference_no`, `paid_at`) VALUES
('pay-0001', 'o-0001', 'gcash',       'paid',    12599.10, 'GC-20260101-001',  NOW()),
('pay-0002', 'o-0002', 'credit_card', 'paid',    14799.00, 'CC-20260102-002',  NOW()),
('pay-0003', 'o-0003', 'cod',         'pending',  1528.30, 'COD-20260103-003', NULL);


-- ------------------------------------------------------------
-- 15. REVIEWS
-- (no DEFAULT uuid() in schema so IDs are explicit)
-- ------------------------------------------------------------
INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `order_id`, `rating`, `comment`) VALUES
('r-0001', 'u-0001', 'p-0001', 'o-0001', 5, 'Amazing phone! Super fast and great camera.'),
('r-0002', 'u-0001', 'p-0002', 'o-0002', 4, 'Great earbuds, noise cancellation works well.'),
('r-0003', 'u-0001', 'p-0003', 'o-0002', 5, 'Perfect fit for the X1, feels very sturdy.');
