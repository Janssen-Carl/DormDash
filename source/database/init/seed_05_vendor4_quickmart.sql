-- =============================================
-- SEED 05: Vendor 4 – Quick Mart items
-- =============================================

INSERT INTO items (vendor_id, price, name, description, is_active, stock, sku, is_bundle, unit_type, unit_value, brand, barcode, is_perishable, is_available, has_expiry, created_at, updated_at) VALUES
(4, 42.00, 'Safeguard Soap Ivory 130g',       'Antibacterial bath soap',        1, 200, 'QM-001', 0, 'bar',    1.00, 'Safeguard',    'SG-SP-001', 0, 1, 0, NOW(), NOW()),
(4, 85.00, 'Head & Shoulders Shampoo 180ml',  'Anti-dandruff shampoo',          1, 120, 'QM-002', 0, 'bottle', 1.00, 'Head&Shoulders','HS-SH-001', 0, 1, 0, NOW(), NOW()),
(4, 65.00, 'Colgate Triple Action 100ml',     'Toothpaste tube',                1, 180, 'QM-003', 0, 'tube',   1.00, 'Colgate',      'CG-TP-001', 0, 1, 0, NOW(), NOW()),
(4, 25.00, 'Rexona Deo Roll-on 40ml',         'Antiperspirant deodorant',       1, 150, 'QM-004', 0, 'piece',  1.00, 'Rexona',       'RX-DO-001', 0, 1, 0, NOW(), NOW()),
(4, 38.00, 'Dove Conditioner 180ml',          'Hair conditioner',               1, 100, 'QM-005', 0, 'bottle', 1.00, 'Dove',         'DV-CO-001', 0, 1, 0, NOW(), NOW()),
(4, 15.00, 'Palmolive Shampoo Sachet 15ml',   'Shampoo sachet',                 1, 400, 'QM-006', 0, 'sachet', 1.00, 'Palmolive',    'PL-SH-001', 0, 1, 0, NOW(), NOW()),
(4, 55.00, 'Lux Body Wash 250ml',             'Moisturizing body wash',         1,  80, 'QM-007', 0, 'bottle', 1.00, 'Lux',          'LX-BW-001', 0, 1, 0, NOW(), NOW()),
(4, 30.00, 'Oral-B Toothbrush Medium',        'Manual toothbrush',              1, 200, 'QM-008', 0, 'piece',  1.00, 'Oral-B',       'OB-TB-001', 0, 1, 0, NOW(), NOW()),
(4, 18.00, 'Johnson''s Baby Powder 100g',     'Baby powder for freshness',      1, 160, 'QM-009', 0, 'bottle', 1.00, 'Johnsons',     'JN-BP-001', 0, 1, 0, NOW(), NOW()),
(4, 48.00, 'Nivea Lotion 200ml',              'Body moisturizing lotion',       1,  90, 'QM-010', 0, 'bottle', 1.00, 'Nivea',        'NV-LT-001', 0, 1, 0, NOW(), NOW());

SET @v4_start = (SELECT MIN(item_id) FROM items WHERE vendor_id = 4);

INSERT INTO item_images (item_id, image, created_at, updated_at) VALUES
(@v4_start + 0, '/images/items/safeguard-soap-ivory-130g.jpg', NOW(), NOW()),
(@v4_start + 1, '/images/items/head-shoulders-shampoo-180ml.jpg', NOW(), NOW()),
(@v4_start + 2, '/images/items/colgate-triple-action-100ml.jpg', NOW(), NOW()),
(@v4_start + 3, '/images/items/rexona-deo-roll-on-40ml.jpg', NOW(), NOW()),
(@v4_start + 4, '/images/items/dove-conditioner-180ml.jpg', NOW(), NOW()),
(@v4_start + 5, '/images/items/palmolive-shampoo-sachet-15ml.jpg', NOW(), NOW()),
(@v4_start + 6, '/images/items/lux-body-wash-250ml.jpg', NOW(), NOW()),
(@v4_start + 7, '/images/items/oral-b-toothbrush-medium.jpg', NOW(), NOW()),
(@v4_start + 8, '/images/items/johnson.jpg', NOW(), NOW()),
(@v4_start + 9, '/images/items/nivea-lotion-200ml.jpg', NOW(), NOW());

INSERT INTO stock_logs (item_id, old_stock, new_stock, quantity_changed, remarks, created_at) VALUES
(@v4_start + 0, 0, 200, 200, 'Initial stock', NOW()),
(@v4_start + 1, 0, 120, 120, 'Initial stock', NOW()),
(@v4_start + 2, 0, 180, 180, 'Initial stock', NOW()),
(@v4_start + 3, 0, 150, 150, 'Initial stock', NOW()),
(@v4_start + 4, 0, 100, 100, 'Initial stock', NOW()),
(@v4_start + 5, 0, 400, 400, 'Initial stock', NOW()),
(@v4_start + 6, 0,  80,  80, 'Initial stock', NOW()),
(@v4_start + 7, 0, 200, 200, 'Initial stock', NOW()),
(@v4_start + 8, 0, 160, 160, 'Initial stock', NOW()),
(@v4_start + 9, 0,  90,  90, 'Initial stock', NOW());

-- All items → Personal Care (4)
INSERT INTO category_items (item_id, category_id) VALUES
(@v4_start + 0, 4),
(@v4_start + 1, 4),
(@v4_start + 2, 4),
(@v4_start + 3, 4),
(@v4_start + 4, 4),
(@v4_start + 5, 4),
(@v4_start + 6, 4),
(@v4_start + 7, 4),
(@v4_start + 8, 4),
(@v4_start + 9, 4);
