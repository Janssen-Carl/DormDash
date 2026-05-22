-- ===========================================
-- FULL DATABASE SEED SCRIPT FOR ORDERS
-- Primary keys start from 20 to avoid conflicts
-- Using base users (customers 6, 7, 8 and their addresses 6, 7, 8)
-- Using base items (items 1, 2)
-- ===========================================

-- -------------------
-- ORDERS (Sample orders)
-- -------------------
INSERT INTO orders (order_id, customer_id, address_id, shipping_method, order_total, order_status, created_at, updated_at)
VALUES
    (20,6,6,'standard',50.00,'completed',NOW(),NOW()),
    (21,7,7,'express',75.00,'completed',NOW(),NOW()),
    (22,8,8,'standard',60.00,'completed',NOW(),NOW()),
    (23,6,6,'express',120.00,'completed',NOW(),NOW()),
    (24,7,7,'standard',30.00,'completed',NOW(),NOW()),
    (25,8,8,'express',80.00,'completed',NOW(),NOW()),
    (26,6,6,'standard',55.00,'completed',NOW(),NOW()),
    (27,7,7,'express',95.00,'completed',NOW(),NOW()),
    (28,8,8,'standard',40.00,'completed',NOW(),NOW()),
    (29,6,6,'express',70.00,'completed',NOW(),NOW()),
    (30,7,7,'standard',65.00,'completed',NOW(),NOW()),
    (31,8,8,'express',85.00,'completed',NOW(),NOW()),
    (32,6,6,'standard',50.00,'completed',NOW(),NOW()),
    (33,7,7,'express',110.00,'completed',NOW(),NOW()),
    (34,8,8,'standard',35.00,'completed',NOW(),NOW()),
    (35,6,6,'express',90.00,'completed',NOW(),NOW()),
    (36,7,7,'standard',60.00,'completed',NOW(),NOW()),
    (37,8,8,'express',100.00,'completed',NOW(),NOW()),
    (38,6,6,'standard',45.00,'completed',NOW(),NOW()),
    (39,7,7,'express',75.00,'completed',NOW(),NOW());

INSERT INTO orders (order_id, customer_id, address_id, shipping_method, order_total, order_status, created_at, updated_at)
VALUES
    (60, 8, 8, 'standard', 40.00, 'completed', NOW(), NOW()),
    (61, 6, 6, 'express', 35.00, 'completed', NOW(), NOW()),
    (62, 7, 7, 'standard', 50.00, 'completed', NOW(), NOW()),
    (63, 8, 8, 'express', 60.00, 'completed', NOW(), NOW()),
    (64, 6, 6, 'standard', 30.00, 'completed', NOW(), NOW()),
    (65, 7, 7, 'express', 55.00, 'completed', NOW(), NOW()),
    (66, 8, 8, 'standard', 45.00, 'completed', NOW(), NOW()),
    (67, 6, 6, 'express', 70.00, 'completed', NOW(), NOW()),
    (68, 7, 7, 'standard', 25.00, 'completed', NOW(), NOW()),
    (69, 8, 8, 'express', 65.00, 'completed', NOW(), NOW()),
    (70, 6, 6, 'standard', 30.00, 'completed', NOW(), NOW()),
    (71, 7, 7, 'express', 40.00, 'completed', NOW(), NOW()),
    (72, 8, 8, 'standard', 55.00, 'completed', NOW(), NOW()),
    (73, 6, 6, 'express', 60.00, 'completed', NOW(), NOW()),
    (74, 7, 7, 'standard', 35.00, 'completed', NOW(), NOW()),
    (75, 8, 8, 'express', 50.00, 'completed', NOW(), NOW()),
    (76, 6, 6, 'standard', 45.00, 'completed', NOW(), NOW()),
    (77, 7, 7, 'express', 60.00, 'completed', NOW(), NOW()),
    (78, 8, 8, 'standard', 40.00, 'completed', NOW(), NOW()),
    (79, 6, 6, 'express', 55.00, 'completed', NOW(), NOW());

INSERT INTO orders (order_id, customer_id, address_id, shipping_method, order_total, order_status, created_at, updated_at)
VALUES
    (85, 7, 7, 'standard', 45.00, 'completed', NOW(), NOW()),
    (86, 8, 8, 'express', 70.00, 'completed', NOW(), NOW()),
    (87, 6, 6, 'standard', 55.00, 'completed', NOW(), NOW()),
    (88, 7, 7, 'express', 90.00, 'completed', NOW(), NOW()),
    (89, 8, 8, 'standard', 35.00, 'completed', NOW(), NOW()),
    (90, 6, 6, 'express', 60.00, 'completed', NOW(), NOW()),
    (91, 7, 7, 'standard', 50.00, 'completed', NOW(), NOW()),
    (92, 8, 8, 'express', 80.00, 'completed', NOW(), NOW()),
    (93, 6, 6, 'standard', 40.00, 'completed', NOW(), NOW()),
    (94, 7, 7, 'express', 75.00, 'completed', NOW(), NOW()),
    (95, 8, 8, 'standard', 65.00, 'completed', NOW(), NOW()),
    (96, 6, 6, 'express', 85.00, 'completed', NOW(), NOW()),
    (97, 7, 7, 'standard', 55.00, 'completed', NOW(), NOW()),
    (98, 8, 8, 'express', 95.00, 'completed', NOW(), NOW()),
    (99, 6, 6, 'standard', 45.00, 'completed', NOW(), NOW()),
    (100, 7, 7, 'express', 70.00, 'completed', NOW(), NOW()),
    (101, 8, 8, 'standard', 60.00, 'completed', NOW(), NOW()),
    (102, 6, 6, 'express', 85.00, 'completed', NOW(), NOW()),
    (103, 7, 7, 'standard', 50.00, 'completed', NOW(), NOW()),
    (104, 8, 8, 'express', 90.00, 'completed', NOW(), NOW());

-- -------------------
-- ORDER ITEMS for the above orders (using item_id 1 & 2)
-- -------------------
INSERT INTO order_items (order_id, item_id, quantity, price)
VALUES
    (85, 1, 2, 12.00),
    (85, 2, 1, 12.00),
    (86, 1, 1, 12.00),
    (86, 2, 3, 12.00),
    (87, 1, 2, 12.00),
    (87, 2, 2, 12.00),
    (88, 1, 3, 12.00),
    (88, 2, 1, 12.00),
    (89, 1, 1, 12.00),
    (89, 2, 2, 12.00),
    (90, 1, 2, 12.00),
    (90, 2, 2, 12.00),
    (91, 1, 1, 12.00),
    (91, 2, 3, 12.00),
    (92, 1, 3, 12.00),
    (92, 2, 2, 12.00),
    (93, 1, 2, 12.00),
    (93, 2, 1, 12.00),
    (94, 1, 1, 12.00),
    (94, 2, 3, 12.00),
    (95, 1, 2, 12.00),
    (95, 2, 2, 12.00),
    (96, 1, 3, 12.00),
    (96, 2, 1, 12.00),
    (97, 1, 1, 12.00),
    (97, 2, 2, 12.00),
    (98, 1, 2, 12.00),
    (98, 2, 3, 12.00),
    (99, 1, 1, 12.00),
    (99, 2, 2, 12.00),
    (100, 1, 3, 12.00),
    (100, 2, 1, 12.00),
    (101, 1, 2, 12.00),
    (101, 2, 2, 12.00),
    (102, 1, 1, 12.00),
    (102, 2, 3, 12.00),
    (103, 1, 2, 12.00),
    (103, 2, 1, 12.00),
    (104, 1, 3, 12.00),
    (104, 2, 2, 12.00);

-- Also add some order items for the first set of orders to avoid inconsistencies
INSERT INTO order_items (order_id, item_id, quantity, price)
VALUES
    (20, 1, 2, 12.00), (21, 2, 3, 12.00), (22, 1, 1, 12.00), (23, 2, 2, 12.00), (24, 1, 1, 12.00),
    (25, 2, 2, 12.00), (26, 1, 3, 12.00), (27, 2, 1, 12.00), (28, 1, 2, 12.00), (29, 2, 2, 12.00),
    (30, 1, 1, 12.00), (31, 2, 3, 12.00), (32, 1, 2, 12.00), (33, 2, 1, 12.00), (34, 1, 1, 12.00),
    (35, 2, 2, 12.00), (36, 1, 3, 12.00), (37, 2, 1, 12.00), (38, 1, 2, 12.00), (39, 2, 2, 12.00),
    
    (60, 1, 2, 12.00), (61, 2, 3, 12.00), (62, 1, 1, 12.00), (63, 2, 2, 12.00), (64, 1, 1, 12.00),
    (65, 2, 2, 12.00), (66, 1, 3, 12.00), (67, 2, 1, 12.00), (68, 1, 2, 12.00), (69, 2, 2, 12.00),
    (70, 1, 1, 12.00), (71, 2, 3, 12.00), (72, 1, 2, 12.00), (73, 2, 1, 12.00), (74, 1, 1, 12.00),
    (75, 2, 2, 12.00), (76, 1, 3, 12.00), (77, 2, 1, 12.00), (78, 1, 2, 12.00), (79, 2, 2, 12.00);

-- Set all orders to completed
UPDATE orders
SET order_status = 'completed';

-- Update created_at to spread across different months (Jan–May)
SET @order_counter = 0;

UPDATE orders
SET created_at = DATE_ADD('2026-01-01', INTERVAL (@order_counter:=@order_counter+1)*5 DAY)
    ORDER BY order_id;

-- Recalculate stock and record stock logs based on seeded order_items
-- Insert aggregated stock log entries before updating actual stock
INSERT INTO stock_logs (item_id, old_stock, new_stock, quantity_changed, remarks, created_at)
SELECT
    i.item_id,
    i.stock AS old_stock,
    GREATEST(i.stock - oi.sold, 0) AS new_stock,
    oi.sold AS quantity_changed,
    'seed_orders' AS remarks,
    NOW()
FROM items i
JOIN (
    SELECT item_id, SUM(quantity) AS sold
    FROM order_items
    GROUP BY item_id
) oi ON oi.item_id = i.item_id;

-- Update item stock according to total sold quantities (never negative)
UPDATE items i
JOIN (
    SELECT item_id, SUM(quantity) AS sold
    FROM order_items
    GROUP BY item_id
) oi ON oi.item_id = i.item_id
SET i.stock = GREATEST(i.stock - oi.sold, 0);
