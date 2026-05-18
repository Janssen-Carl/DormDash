-- =============================================
-- SEED 02: Vendor 1 – Snack Shack items
-- =============================================

INSERT INTO items (vendor_id, price, name, description, is_active, stock, sku, is_bundle, unit_type, unit_value, brand, barcode, is_perishable, is_available, has_expiry, created_at, updated_at) VALUES
(1, 12.00, 'Lucky Me Pancit Canton Original',   'Classic instant pancit canton', 1, 200, 'SS-001', 0, 'pack', 1.00, 'Lucky Me',     'LM-PC-001', 1, 1, 1, NOW(), NOW()),
(1, 12.00, 'Lucky Me Pancit Canton Chilimansi',  'Spicy citrus pancit canton',   1, 180, 'SS-002', 0, 'pack', 1.00, 'Lucky Me',     'LM-PC-002', 1, 1, 1, NOW(), NOW()),
(1, 15.00, 'Nissin Cup Noodles Seafood',         'Cup noodles seafood flavor',   1, 150, 'SS-003', 0, 'cup',  1.00, 'Nissin',       'NS-CN-001', 1, 1, 1, NOW(), NOW()),
(1, 25.00, 'Piattos Cheese',                     'Potato chips cheese flavor',   1, 120, 'SS-004', 0, 'pack', 1.00, 'Jack n Jill',  'JJ-PT-001', 1, 1, 1, NOW(), NOW()),
(1, 30.00, 'Nova Cheddar Cheese',                'Multi-grain snack chips',      1, 100, 'SS-005', 0, 'pack', 1.00, 'Jack n Jill',  'JJ-NV-001', 1, 1, 1, NOW(), NOW()),
(1, 20.00, 'SkyFlakes Crackers',                 'Plain crackers pack',          1, 250, 'SS-006', 0, 'pack', 1.00, 'M.Y. San',     'MY-SF-001', 0, 1, 1, NOW(), NOW()),
(1,  8.00, 'Kopiko Brown Coffee 3in1',           'Instant coffee sachet',        1, 300, 'SS-007', 0, 'sachet',1.00,'Kopiko',       'KP-BC-001', 0, 1, 1, NOW(), NOW()),
(1, 10.00, 'Great Taste White 3in1',             'Creamy coffee sachet',         1, 300, 'SS-008', 0, 'sachet',1.00,'Great Taste',  'GT-WH-001', 0, 1, 1, NOW(), NOW()),
(1, 35.00, 'Oishi Prawn Crackers',               'Light prawn-flavored crackers',1,  90, 'SS-009', 0, 'pack', 1.00, 'Oishi',        'OS-PR-001', 1, 1, 1, NOW(), NOW()),
(1, 18.00, 'C2 Green Tea Apple',                 'Ready-to-drink green tea',     1, 160, 'SS-010', 0, 'bottle',1.00,'C2',           'C2-GT-001', 1, 1, 1, NOW(), NOW());

-- Get the starting item_id for vendor 1 items (assumes these are the first 10 items)
SET @v1_start = (SELECT MIN(item_id) FROM items WHERE vendor_id = 1);

-- Item images (path template: /images/items/{item_id}/{n}.jpg)
INSERT INTO item_images (item_id, image, created_at, updated_at) VALUES
(@v1_start + 0, '/images/items/1/1.jpg', NOW(), NOW()),
(@v1_start + 1, '/images/items/2/1.jpg', NOW(), NOW()),
(@v1_start + 2, '/images/items/3/1.jpg', NOW(), NOW()),
(@v1_start + 3, '/images/items/4/1.jpg', NOW(), NOW()),
(@v1_start + 4, '/images/items/5/1.jpg', NOW(), NOW()),
(@v1_start + 5, '/images/items/6/1.jpg', NOW(), NOW()),
(@v1_start + 6, '/images/items/7/1.jpg', NOW(), NOW()),
(@v1_start + 7, '/images/items/8/1.jpg', NOW(), NOW()),
(@v1_start + 8, '/images/items/9/1.jpg', NOW(), NOW()),
(@v1_start + 9, '/images/items/10/1.jpg', NOW(), NOW());

-- Stock logs (initial stock entry)
INSERT INTO stock_logs (item_id, old_stock, new_stock, quantity_changed, remarks, created_at) VALUES
(@v1_start + 0, 0, 200, 200, 'Initial stock', NOW()),
(@v1_start + 1, 0, 180, 180, 'Initial stock', NOW()),
(@v1_start + 2, 0, 150, 150, 'Initial stock', NOW()),
(@v1_start + 3, 0, 120, 120, 'Initial stock', NOW()),
(@v1_start + 4, 0, 100, 100, 'Initial stock', NOW()),
(@v1_start + 5, 0, 250, 250, 'Initial stock', NOW()),
(@v1_start + 6, 0, 300, 300, 'Initial stock', NOW()),
(@v1_start + 7, 0, 300, 300, 'Initial stock', NOW()),
(@v1_start + 8, 0,  90,  90, 'Initial stock', NOW()),
(@v1_start + 9, 0, 160, 160, 'Initial stock', NOW());

-- Category assignments (category_id: 1=Food, 2=Beverages, 6=Noodles, 7=Chips, 8=Coffee)
INSERT INTO category_items (item_id, category_id) VALUES
(@v1_start + 0, 1), (@v1_start + 0, 6),
(@v1_start + 1, 1), (@v1_start + 1, 6),
(@v1_start + 2, 1), (@v1_start + 2, 6),
(@v1_start + 3, 1), (@v1_start + 3, 7),
(@v1_start + 4, 1), (@v1_start + 4, 7),
(@v1_start + 5, 1), (@v1_start + 5, 7),
(@v1_start + 6, 2), (@v1_start + 6, 8),
(@v1_start + 7, 2), (@v1_start + 7, 8),
(@v1_start + 8, 1), (@v1_start + 8, 7),
(@v1_start + 9, 2), (@v1_start + 9, 9);
