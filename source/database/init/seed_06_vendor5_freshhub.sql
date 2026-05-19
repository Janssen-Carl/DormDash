-- =============================================
-- SEED 06: Vendor 5 – Fresh Hub items
-- =============================================

INSERT INTO items (vendor_id, price, name, description, is_active, stock, sku, is_bundle, unit_type, unit_value, brand, barcode, is_perishable, is_available, has_expiry, created_at, updated_at) VALUES
(5, 120.00, 'Extension Cord 3-Gang 2m',      '3-outlet power extension',       1,  60, 'FH-001', 0, 'piece', 1.00, 'Omni',         'OM-EC-001', 0, 1, 0, NOW(), NOW()),
(5,  45.00, 'LED Desk Lamp USB',             'USB powered desk lamp',           1,  50, 'FH-002', 0, 'piece', 1.00, 'Firefly',      'FF-DL-001', 0, 1, 0, NOW(), NOW()),
(5,  35.00, 'Plastic Hangers 10s',           'Pack of 10 plastic hangers',      1, 100, 'FH-003', 1, 'pack', 10.00, 'HomeStyle',    'HS-PH-001', 0, 1, 0, NOW(), NOW()),
(5,  85.00, 'Mini Electric Fan USB',         'Portable USB fan',                1,  40, 'FH-004', 0, 'piece', 1.00, 'Akari',        'AK-MF-001', 0, 1, 0, NOW(), NOW()),
(5,  25.00, 'Clothespin Plastic 24s',        'Plastic clothespins pack',        1, 150, 'FH-005', 1, 'pack', 24.00, 'HomeStyle',    'HS-CP-001', 0, 1, 0, NOW(), NOW()),
(5,  60.00, 'Microfiber Towel Large',        'Quick-dry microfiber towel',      1,  80, 'FH-006', 0, 'piece', 1.00, 'TowelPro',     'TP-MT-001', 0, 1, 0, NOW(), NOW()),
(5,  15.00, 'Trash Bags 10s Medium',         'Black garbage bags pack',         1, 200, 'FH-007', 1, 'pack', 10.00, 'Glad',         'GL-TB-001', 0, 1, 0, NOW(), NOW()),
(5,  95.00, 'Tumbler Stainless 500ml',       'Insulated water tumbler',         1,  70, 'FH-008', 0, 'piece', 1.00, 'Aqua Flask',   'AF-TM-001', 0, 1, 0, NOW(), NOW()),
(5,  30.00, 'Laundry Detergent 500g',        'Powder laundry soap',             1, 160, 'FH-009', 0, 'pouch', 1.00, 'Tide',         'TD-LD-001', 0, 1, 0, NOW(), NOW()),
(5,  40.00, 'Broom and Dustpan Set Mini',    'Compact broom and dustpan',       1,  45, 'FH-010', 0, 'set',   1.00, 'HomeStyle',    'HS-BD-001', 0, 1, 0, NOW(), NOW());

SET @v5_start = (SELECT MIN(item_id) FROM items WHERE vendor_id = 5);

INSERT INTO item_images (item_id, image, created_at, updated_at) VALUES
(@v5_start + 0, '/images/items/extension-cord-3-gang-2m.jpg', NOW(), NOW()),
(@v5_start + 1, '/images/items/led-desk-lamp-usb.jpg', NOW(), NOW()),
(@v5_start + 2, '/images/items/plastic-hangers-10s.jpg', NOW(), NOW()),
(@v5_start + 3, '/images/items/mini-electric-fan-usb.jpg', NOW(), NOW()),
(@v5_start + 4, '/images/items/clothespin-plastic-24s.jpg', NOW(), NOW()),
(@v5_start + 5, '/images/items/microfiber-towel-large.jpg', NOW(), NOW()),
(@v5_start + 6, '/images/items/trash-bags-10s-medium.jpg', NOW(), NOW()),
(@v5_start + 7, '/images/items/tumbler-stainless-500ml.jpg', NOW(), NOW()),
(@v5_start + 8, '/images/items/laundry-detergent-500g.jpg', NOW(), NOW()),
(@v5_start + 9, '/images/items/broom-and-dustpan-set-mini.jpg', NOW(), NOW());

INSERT INTO stock_logs (item_id, old_stock, new_stock, quantity_changed, remarks, created_at) VALUES
(@v5_start + 0, 0,  60,  60, 'Initial stock', NOW()),
(@v5_start + 1, 0,  50,  50, 'Initial stock', NOW()),
(@v5_start + 2, 0, 100, 100, 'Initial stock', NOW()),
(@v5_start + 3, 0,  40,  40, 'Initial stock', NOW()),
(@v5_start + 4, 0, 150, 150, 'Initial stock', NOW()),
(@v5_start + 5, 0,  80,  80, 'Initial stock', NOW()),
(@v5_start + 6, 0, 200, 200, 'Initial stock', NOW()),
(@v5_start + 7, 0,  70,  70, 'Initial stock', NOW()),
(@v5_start + 8, 0, 160, 160, 'Initial stock', NOW()),
(@v5_start + 9, 0,  45,  45, 'Initial stock', NOW());

-- All items → Dorm Essentials (5)
INSERT INTO category_items (item_id, category_id) VALUES
(@v5_start + 0, 5),
(@v5_start + 1, 5),
(@v5_start + 2, 5),
(@v5_start + 3, 5),
(@v5_start + 4, 5),
(@v5_start + 5, 5),
(@v5_start + 6, 5),
(@v5_start + 7, 5),
(@v5_start + 8, 5),
(@v5_start + 9, 5);
