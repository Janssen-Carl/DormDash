-- =============================================
-- SEED 07: Pages, Carts, Orders, Payments, Discounts
-- =============================================

-- ----- PAGES (1 per vendor) -----
INSERT INTO pages (vendor_id, title, description, is_public, created_at, updated_at) VALUES
(1, 'Snack Shack Store',   'Your go-to dorm snack shop',       1, NOW(), NOW()),
(2, 'Dorm Bites Store',    'Canned goods and pantry staples',   1, NOW(), NOW()),
(3, 'Campus Pantry Store', 'School supplies for students',      1, NOW(), NOW()),
(4, 'Quick Mart Store',    'Personal care and hygiene products', 1, NOW(), NOW()),
(5, 'Fresh Hub Store',     'Dorm room essentials and tools',    1, NOW(), NOW());

-- ----- PAGE_ITEMS (link first 5 items of each vendor to their page) -----
SET @v1s = (SELECT MIN(item_id) FROM items WHERE vendor_id = 1);
SET @v2s = (SELECT MIN(item_id) FROM items WHERE vendor_id = 2);
SET @v3s = (SELECT MIN(item_id) FROM items WHERE vendor_id = 3);
SET @v4s = (SELECT MIN(item_id) FROM items WHERE vendor_id = 4);
SET @v5s = (SELECT MIN(item_id) FROM items WHERE vendor_id = 5);

SET @p1 = (SELECT page_id FROM pages WHERE vendor_id = 1 LIMIT 1);
SET @p2 = (SELECT page_id FROM pages WHERE vendor_id = 2 LIMIT 1);
SET @p3 = (SELECT page_id FROM pages WHERE vendor_id = 3 LIMIT 1);
SET @p4 = (SELECT page_id FROM pages WHERE vendor_id = 4 LIMIT 1);
SET @p5 = (SELECT page_id FROM pages WHERE vendor_id = 5 LIMIT 1);

INSERT INTO page_items (item_id, page_id, display_order) VALUES
(@v1s+0, @p1, 1), (@v1s+1, @p1, 2), (@v1s+2, @p1, 3), (@v1s+3, @p1, 4), (@v1s+4, @p1, 5),
(@v2s+0, @p2, 1), (@v2s+1, @p2, 2), (@v2s+2, @p2, 3), (@v2s+3, @p2, 4), (@v2s+4, @p2, 5),
(@v3s+0, @p3, 1), (@v3s+1, @p3, 2), (@v3s+2, @p3, 3), (@v3s+3, @p3, 4), (@v3s+4, @p3, 5),
(@v4s+0, @p4, 1), (@v4s+1, @p4, 2), (@v4s+2, @p4, 3), (@v4s+3, @p4, 4), (@v4s+4, @p4, 5),
(@v5s+0, @p5, 1), (@v5s+1, @p5, 2), (@v5s+2, @p5, 3), (@v5s+3, @p5, 4), (@v5s+4, @p5, 5);

-- ----- CARTS (each customer has 2 items in cart) -----
INSERT INTO carts (customer_id, item_id, quantity) VALUES
(6, @v1s+0, 3),
(6, @v2s+1, 2),
(7, @v3s+2, 1),
(7, @v5s+7, 1),
(8, @v4s+0, 2),
(8, @v1s+9, 5);

-- ----- ORDERS -----
-- Customer 6: 2 orders, Customer 7: 1 order, Customer 8: 1 order
-- address_id 6=Juan, 7=Maria, 8=Carlo
INSERT INTO orders (customer_id, address_id, shipping_method, order_total, send_date, receive_date, order_status, tracking_number, created_at, updated_at) VALUES
(6, 6, 'standard',  87.00, '2026-05-10', '2026-05-12', 'delivered',  'TRK-20260510-001', '2026-05-10 09:00:00', NOW()),
(6, 6, 'express',   55.00, '2026-05-15', NULL,         'shipped',    'TRK-20260515-002', '2026-05-15 14:00:00', NOW()),
(7, 7, 'standard', 135.00, NULL,         NULL,         'pending',    NULL,                '2026-05-17 10:30:00', NOW()),
(8, 8, 'standard',  72.00, '2026-05-16', '2026-05-18', 'delivered',  'TRK-20260516-004', '2026-05-16 08:00:00', NOW());

-- ----- ORDER_ITEMS -----
SET @o1 = (SELECT MIN(order_id) FROM orders);

INSERT INTO order_items (order_id, item_id, quantity, price) VALUES
(@o1+0, @v1s+0, 3, 12.00),
(@o1+0, @v1s+3, 1, 25.00),
(@o1+0, @v1s+6, 2,  8.00),
(@o1+1, @v2s+0, 1, 45.00),
(@o1+1, @v2s+7, 1, 10.00),
(@o1+2, @v3s+0, 1, 85.00),
(@o1+2, @v3s+2, 1, 45.00),
(@o1+3, @v4s+0, 1, 42.00),
(@o1+3, @v4s+7, 1, 30.00);

-- ----- PAYMENT_TRANSACTIONS -----
INSERT INTO payment_transactions (order_id, amount, created_at, status, reference_no, token, acc_last4_no) VALUES
(@o1+0,  87.00, '2026-05-10 09:05:00', 'completed', 'REF-2026-0001', NULL, '1234'),
(@o1+1,  55.00, '2026-05-15 14:05:00', 'completed', 'REF-2026-0002', NULL, '1234'),
(@o1+2, 135.00, '2026-05-17 10:35:00', 'pending',   'REF-2026-0003', NULL, '5678'),
(@o1+3,  72.00, '2026-05-16 08:05:00', 'completed', 'REF-2026-0004', NULL, '9012');

-- ----- DISCOUNTS (a few active promos) -----
INSERT INTO discounts (item_id, date_start, date_end, type, value, use_limit, description, name, is_active, created_at, updated_at) VALUES
(@v1s+0, '2026-05-01 00:00:00', '2026-06-01 00:00:00', 'percentage', 10.00, 100, 'May promo on pancit canton',   '10% Off Canton',     1, NOW(), NOW()),
(@v2s+3, '2026-05-01 00:00:00', '2026-05-31 00:00:00', 'fixed',       5.00,  50, 'P5 off Delimondo',             'Delimondo Deal',     1, NOW(), NOW()),
(@v5s+7, '2026-05-15 00:00:00', '2026-06-15 00:00:00', 'percentage', 15.00,  30, 'Summer tumbler sale',           '15% Off Tumbler',    1, NOW(), NOW()),
(@v3s+9, '2026-05-01 00:00:00', '2026-07-01 00:00:00', 'percentage', 20.00, NULL,'Back to school calculator deal','Calculator Blowout', 1, NOW(), NOW());
