-- =============================================
-- SEED 03: Vendor 2 – Dorm Bites items
-- =============================================

INSERT INTO items (vendor_id, price, name, description, is_active, stock, sku, is_bundle, unit_type, unit_value, brand, barcode, is_perishable, is_available, has_expiry, created_at, updated_at) VALUES
(2, 45.00, 'Argentina Corned Beef 150g',     'Classic canned corned beef',     1, 100, 'DB-001', 0, 'can',   1.00, 'Argentina',    'AR-CB-001', 1, 1, 1, NOW(), NOW()),
(2, 38.00, 'Century Tuna Flakes in Oil',     'Canned tuna in oil',            1, 120, 'DB-002', 0, 'can',   1.00, 'Century',      'CT-TF-001', 1, 1, 1, NOW(), NOW()),
(2, 22.00, 'Spam Luncheon Meat 115g',        'Canned luncheon meat',          1,  80, 'DB-003', 0, 'can',   1.00, 'Spam',         'SP-LM-001', 1, 1, 1, NOW(), NOW()),
(2, 55.00, 'Delimondo Corned Beef 380g',     'Premium corned beef',           1,  60, 'DB-004', 0, 'can',   1.00, 'Delimondo',    'DM-CB-001', 1, 1, 1, NOW(), NOW()),
(2, 28.00, 'Mega Sardines in Tomato Sauce',  'Canned sardines',               1, 150, 'DB-005', 0, 'can',   1.00, 'Mega',         'MG-SR-001', 1, 1, 1, NOW(), NOW()),
(2, 14.00, 'Bear Brand Fortified Milk 33g',  'Powdered milk sachet',          1, 200, 'DB-006', 0, 'sachet',1.00, 'Bear Brand',   'BB-FM-001', 0, 1, 1, NOW(), NOW()),
(2, 65.00, 'Milo 300g Powder',              'Chocolate malt drink powder',    1, 100, 'DB-007', 0, 'pouch', 1.00, 'Milo',         'ML-PW-001', 0, 1, 1, NOW(), NOW()),
(2, 10.00, 'Tang Orange Juice 25g',         'Powdered juice drink',           1, 250, 'DB-008', 0, 'sachet',1.00, 'Tang',         'TG-OJ-001', 0, 1, 1, NOW(), NOW()),
(2, 75.00, 'Energen Chocolate 10s',         'Cereal drink 10-pack',           1,  70, 'DB-009', 1, 'box',  10.00, 'Energen',      'EN-CH-001', 0, 1, 1, NOW(), NOW()),
(2, 40.00, 'Gardenia White Bread',          'Classic white bread loaf',       1,  50, 'DB-010', 0, 'loaf',  1.00, 'Gardenia',     'GD-WB-001', 1, 1, 1, NOW(), NOW());

SET @v2_start = (SELECT MIN(item_id) FROM items WHERE vendor_id = 2);

INSERT INTO item_images (item_id, image, created_at, updated_at) VALUES
(@v2_start + 0, '/images/items/11/1.jpg', NOW(), NOW()),
(@v2_start + 1, '/images/items/12/1.jpg', NOW(), NOW()),
(@v2_start + 2, '/images/items/13/1.jpg', NOW(), NOW()),
(@v2_start + 3, '/images/items/14/1.jpg', NOW(), NOW()),
(@v2_start + 4, '/images/items/15/1.jpg', NOW(), NOW()),
(@v2_start + 5, '/images/items/16/1.jpg', NOW(), NOW()),
(@v2_start + 6, '/images/items/17/1.jpg', NOW(), NOW()),
(@v2_start + 7, '/images/items/18/1.jpg', NOW(), NOW()),
(@v2_start + 8, '/images/items/19/1.jpg', NOW(), NOW()),
(@v2_start + 9, '/images/items/20/1.jpg', NOW(), NOW());

INSERT INTO stock_logs (item_id, old_stock, new_stock, quantity_changed, remarks, created_at) VALUES
(@v2_start + 0, 0, 100, 100, 'Initial stock', NOW()),
(@v2_start + 1, 0, 120, 120, 'Initial stock', NOW()),
(@v2_start + 2, 0,  80,  80, 'Initial stock', NOW()),
(@v2_start + 3, 0,  60,  60, 'Initial stock', NOW()),
(@v2_start + 4, 0, 150, 150, 'Initial stock', NOW()),
(@v2_start + 5, 0, 200, 200, 'Initial stock', NOW()),
(@v2_start + 6, 0, 100, 100, 'Initial stock', NOW()),
(@v2_start + 7, 0, 250, 250, 'Initial stock', NOW()),
(@v2_start + 8, 0,  70,  70, 'Initial stock', NOW()),
(@v2_start + 9, 0,  50,  50, 'Initial stock', NOW());

INSERT INTO category_items (item_id, category_id) VALUES
(@v2_start + 0, 1),
(@v2_start + 1, 1),
(@v2_start + 2, 1),
(@v2_start + 3, 1),
(@v2_start + 4, 1),
(@v2_start + 5, 2), (@v2_start + 5, 8),
(@v2_start + 6, 2), (@v2_start + 6, 8),
(@v2_start + 7, 2), (@v2_start + 7, 9),
(@v2_start + 8, 2),
(@v2_start + 9, 1);
