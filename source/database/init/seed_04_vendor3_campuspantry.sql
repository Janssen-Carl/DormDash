-- =============================================
-- SEED 04: Vendor 3 – Campus Pantry items
-- =============================================

INSERT INTO items (vendor_id, price, name, description, is_active, stock, sku, is_bundle, unit_type, unit_value, brand, barcode, is_perishable, is_available, has_expiry, created_at, updated_at) VALUES
(3, 85.00, 'Mongol No. 2 Pencil 12s',       'Classic yellow pencils box',       1, 200, 'CP-001', 1, 'box',   12.00, 'Mongol',       'MG-P2-001', 0, 1, 0, NOW(), NOW()),
(3, 12.00, 'HBW Ballpen Black',             'Ballpoint pen black ink',          1, 500, 'CP-002', 0, 'piece',  1.00, 'HBW',          'HB-BP-001', 0, 1, 0, NOW(), NOW()),
(3, 45.00, 'Campus Notebook 80 Leaves',     'College ruled notebook',           1, 300, 'CP-003', 0, 'piece',  1.00, 'National',     'NT-NB-001', 0, 1, 0, NOW(), NOW()),
(3, 65.00, 'Yellow Pad Paper 80 Leaves',    'Legal size yellow pad',            1, 200, 'CP-004', 0, 'pad',    1.00, 'National',     'NT-YP-001', 0, 1, 0, NOW(), NOW()),
(3, 35.00, 'Scotch Tape 24mm',              'Clear adhesive tape',              1, 150, 'CP-005', 0, 'roll',   1.00, '3M',           '3M-ST-001', 0, 1, 0, NOW(), NOW()),
(3, 18.00, 'Crayola Sharpener',             'Single hole pencil sharpener',     1, 100, 'CP-006', 0, 'piece',  1.00, 'Crayola',      'CR-SH-001', 0, 1, 0, NOW(), NOW()),
(3, 55.00, 'Elmer''s Glue 120ml',           'White school glue',                1, 120, 'CP-007', 0, 'bottle', 1.00, 'Elmers',       'EL-GL-001', 0, 1, 0, NOW(), NOW()),
(3, 25.00, 'Stabilo Highlighter Yellow',    'Fluorescent highlighter pen',      1, 180, 'CP-008', 0, 'piece',  1.00, 'Stabilo',      'ST-HL-001', 0, 1, 0, NOW(), NOW()),
(3, 15.00, 'Plastic Folder A4 Clear',       'Clear document folder',            1, 250, 'CP-009', 0, 'piece',  1.00, 'Best Buy',     'BB-PF-001', 0, 1, 0, NOW(), NOW()),
(3, 95.00, 'Scientific Calculator FX-82',   'Casio scientific calculator',      1,  40, 'CP-010', 0, 'piece',  1.00, 'Casio',        'CS-SC-001', 0, 1, 0, NOW(), NOW());

SET @v3_start = (SELECT MIN(item_id) FROM items WHERE vendor_id = 3);

INSERT INTO item_images (item_id, image, created_at, updated_at) VALUES
(@v3_start + 0, '/images/items/21/1.jpg', NOW(), NOW()),
(@v3_start + 1, '/images/items/22/1.jpg', NOW(), NOW()),
(@v3_start + 2, '/images/items/23/1.jpg', NOW(), NOW()),
(@v3_start + 3, '/images/items/24/1.jpg', NOW(), NOW()),
(@v3_start + 4, '/images/items/25/1.jpg', NOW(), NOW()),
(@v3_start + 5, '/images/items/26/1.jpg', NOW(), NOW()),
(@v3_start + 6, '/images/items/27/1.jpg', NOW(), NOW()),
(@v3_start + 7, '/images/items/28/1.jpg', NOW(), NOW()),
(@v3_start + 8, '/images/items/29/1.jpg', NOW(), NOW()),
(@v3_start + 9, '/images/items/30/1.jpg', NOW(), NOW());

INSERT INTO stock_logs (item_id, old_stock, new_stock, quantity_changed, remarks, created_at) VALUES
(@v3_start + 0, 0, 200, 200, 'Initial stock', NOW()),
(@v3_start + 1, 0, 500, 500, 'Initial stock', NOW()),
(@v3_start + 2, 0, 300, 300, 'Initial stock', NOW()),
(@v3_start + 3, 0, 200, 200, 'Initial stock', NOW()),
(@v3_start + 4, 0, 150, 150, 'Initial stock', NOW()),
(@v3_start + 5, 0, 100, 100, 'Initial stock', NOW()),
(@v3_start + 6, 0, 120, 120, 'Initial stock', NOW()),
(@v3_start + 7, 0, 180, 180, 'Initial stock', NOW()),
(@v3_start + 8, 0, 250, 250, 'Initial stock', NOW()),
(@v3_start + 9, 0,  40,  40, 'Initial stock', NOW());

-- All items → School Supplies (3), specific ones → Writing Tools (10)
INSERT INTO category_items (item_id, category_id) VALUES
(@v3_start + 0, 3), (@v3_start + 0, 10),
(@v3_start + 1, 3), (@v3_start + 1, 10),
(@v3_start + 2, 3),
(@v3_start + 3, 3),
(@v3_start + 4, 3),
(@v3_start + 5, 3),
(@v3_start + 6, 3),
(@v3_start + 7, 3), (@v3_start + 7, 10),
(@v3_start + 8, 3),
(@v3_start + 9, 3);
