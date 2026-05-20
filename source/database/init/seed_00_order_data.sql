

-- ===========================================
-- FULL DATABASE SEED SCRIPT (20+ records per table)
-- Primary keys start from 20 to avoid conflicts
-- ===========================================

-- -------------------
-- USERS (Customers + Vendors)
-- -------------------
INSERT INTO users (user_id, username, email, role, password, created_at, updated_at) VALUES
                                                                                         (20,'cust1','cust1@email.com','customer','password_hash',NOW(),NOW()),
                                                                                         (21,'cust2','cust2@email.com','customer','password_hash',NOW(),NOW()),
                                                                                         (22,'cust3','cust3@email.com','customer','password_hash',NOW(),NOW()),
                                                                                         (23,'cust4','cust4@email.com','customer','password_hash',NOW(),NOW()),
                                                                                         (24,'cust5','cust5@email.com','customer','password_hash',NOW(),NOW()),
                                                                                         (25,'cust6','cust6@email.com','customer','password_hash',NOW(),NOW()),
                                                                                         (26,'cust7','cust7@email.com','customer','password_hash',NOW(),NOW()),
                                                                                         (27,'cust8','cust8@email.com','customer','password_hash',NOW(),NOW()),
                                                                                         (28,'cust9','cust9@email.com','customer','password_hash',NOW(),NOW()),
                                                                                         (29,'cust10','cust10@email.com','customer','password_hash',NOW(),NOW()),
                                                                                         (30,'vendor1','vendor1@email.com','vendor','password_hash',NOW(),NOW()),
                                                                                         (31,'vendor2','vendor2@email.com','vendor','password_hash',NOW(),NOW()),
                                                                                         (32,'vendor3','vendor3@email.com','vendor','password_hash',NOW(),NOW()),
                                                                                         (33,'vendor4','vendor4@email.com','vendor','password_hash',NOW(),NOW()),
                                                                                         (34,'vendor5','vendor5@email.com','vendor','password_hash',NOW(),NOW()),
                                                                                         (35,'vendor6','vendor6@email.com','vendor','password_hash',NOW(),NOW()),
                                                                                         (36,'vendor7','vendor7@email.com','vendor','password_hash',NOW(),NOW()),
                                                                                         (37,'vendor8','vendor8@email.com','vendor','password_hash',NOW(),NOW()),
                                                                                         (38,'vendor9','vendor9@email.com','vendor','password_hash',NOW(),NOW()),
                                                                                         (39,'vendor10','vendor10@email.com','vendor','password_hash',NOW(),NOW());

-- -------------------
-- ADDRESSES
-- -------------------
INSERT INTO addresses (address_id, user_id, street, city, province_state, postal_code, phone, email, country, created_at, updated_at) VALUES
                                                                                                                                          (20,20,'123 Maple St','CityA','StateA','12345','555-0101','cust1@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (21,21,'456 Oak Ave','CityB','StateB','23456','555-0102','cust2@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (22,22,'789 Pine Rd','CityC','StateC','34567','555-0103','cust3@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (23,23,'101 Birch Blvd','CityD','StateD','45678','555-0104','cust4@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (24,24,'202 Cedar Ln','CityE','StateE','56789','555-0105','cust5@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (25,25,'1 Vendor St','VendorCity1','VState1','11111','555-1001','vendor1@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (26,26,'2 Vendor St','VendorCity2','VState2','22222','555-1002','vendor2@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (27,27,'3 Vendor St','VendorCity3','VState3','33333','555-1003','vendor3@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (28,28,'4 Vendor St','VendorCity4','VState4','44444','555-1004','vendor4@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (29,29,'5 Vendor St','VendorCity5','VState5','55555','555-1005','vendor5@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (30,30,'6 Vendor St','VendorCity6','VState6','66666','555-1006','vendor6@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (31,31,'7 Vendor St','VendorCity7','VState7','77777','555-1007','vendor7@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (32,32,'8 Vendor St','VendorCity8','VState8','88888','555-1008','vendor8@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (33,33,'9 Vendor St','VendorCity9','VState9','99999','555-1009','vendor9@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (34,34,'10 Vendor St','VendorCity10','VState10','10101','555-1010','vendor10@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (35,35,'11 Vendor St','VendorCity11','VState11','11112','555-1011','vendor11@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (36,36,'12 Vendor St','VendorCity12','VState12','12121','555-1012','vendor12@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (37,37,'13 Vendor St','VendorCity13','VState13','13131','555-1013','vendor13@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (38,38,'14 Vendor St','VendorCity14','VState14','14141','555-1014','vendor14@email.com','CountryA',NOW(),NOW()),
                                                                                                                                          (39,39,'15 Vendor St','VendorCity15','VState15','15151','555-1015','vendor15@email.com','CountryA',NOW(),NOW());

-- -------------------
-- CUSTOMERS
-- -------------------
INSERT INTO customers (customer_id, first_name, last_name, phone, primary_address_id, created_at, updated_at) VALUES
                                                                                                                  (20,'John','Doe','555-0101',20,NOW(),NOW()),
                                                                                                                  (21,'Jane','Smith','555-0102',21,NOW(),NOW()),
                                                                                                                  (22,'Alice','Johnson','555-0103',22,NOW(),NOW()),
                                                                                                                  (23,'Bob','Williams','555-0104',23,NOW(),NOW()),
                                                                                                                  (24,'Carol','Brown','555-0105',24,NOW(),NOW()),
                                                                                                                  (25,'David','Jones','555-0106',20,NOW(),NOW()),
                                                                                                                  (26,'Eve','Miller','555-0107',21,NOW(),NOW()),
                                                                                                                  (27,'Frank','Davis','555-0108',22,NOW(),NOW()),
                                                                                                                  (28,'Grace','Garcia','555-0109',23,NOW(),NOW()),
                                                                                                                  (29,'Hank','Martinez','555-0110',24,NOW(),NOW()),
                                                                                                                  (30,'Ivy','Lopez','555-0111',20,NOW(),NOW()),
                                                                                                                  (31,'Jack','Gonzalez','555-0112',21,NOW(),NOW()),
                                                                                                                  (32,'Kara','Wilson','555-0113',22,NOW(),NOW()),
                                                                                                                  (33,'Leo','Anderson','555-0114',23,NOW(),NOW()),
                                                                                                                  (34,'Mia','Thomas','555-0115',24,NOW(),NOW()),
                                                                                                                  (35,'Nina','Taylor','555-0116',20,NOW(),NOW()),
                                                                                                                  (36,'Owen','Moore','555-0117',21,NOW(),NOW()),
                                                                                                                  (37,'Paul','Jackson','555-0118',22,NOW(),NOW()),
                                                                                                                  (38,'Quinn','Martin','555-0119',23,NOW(),NOW()),
                                                                                                                  (39,'Rita','Lee','555-0120',24,NOW(),NOW());

-- -------------------
-- VENDORS
-- -------------------
INSERT INTO vendors (vendor_id, name, phone, address_id, active, created_at, updated_at) VALUES
                                                                                             (30,'Vendor A','555-1001',25,1,NOW(),NOW()),
                                                                                             (31,'Vendor B','555-1002',26,1,NOW(),NOW()),
                                                                                             (32,'Vendor C','555-1003',27,1,NOW(),NOW()),
                                                                                             (33,'Vendor D','555-1004',28,1,NOW(),NOW()),
                                                                                             (34,'Vendor E','555-1005',29,1,NOW(),NOW()),
                                                                                             (35,'Vendor F','555-1006',30,1,NOW(),NOW()),
                                                                                             (36,'Vendor G','555-1007',31,1,NOW(),NOW()),
                                                                                             (37,'Vendor H','555-1008',32,1,NOW(),NOW()),
                                                                                             (38,'Vendor I','555-1009',33,1,NOW(),NOW()),
                                                                                             (39,'Vendor J','555-1010',34,1,NOW(),NOW());

-- -------------------
-- -------------------
-- ITEMS
-- -------------------
INSERT INTO items (item_id, vendor_id, name, description, price, stock, sku, unit_type, unit_value, brand, is_active, is_available, created_at, updated_at)
VALUES
    (20,30,'Item 1','Sample description 1',10.00,50,'SKU020','pcs',1.0,'BrandA',1,1,NOW(),NOW()),
    (21,30,'Item 2','Sample description 2',12.50,40,'SKU021','pcs',1.0,'BrandA',1,1,NOW(),NOW()),
    (22,30,'Item 3','Sample description 3',7.75,60,'SKU022','pcs',1.0,'BrandB',1,1,NOW(),NOW()),
    (23,30,'Item 4','Sample description 4',15.00,30,'SKU023','pcs',1.0,'BrandB',1,1,NOW(),NOW()),
    (24,30,'Item 5','Sample description 5',20.00,25,'SKU024','pcs',1.0,'BrandC',1,1,NOW(),NOW()),
    (25,30,'Item 6','Sample description 6',8.50,70,'SKU025','pcs',1.0,'BrandC',1,1,NOW(),NOW()),
    (26,30,'Item 7','Sample description 7',11.00,55,'SKU026','pcs',1.0,'BrandD',1,1,NOW(),NOW()),
    (27,30,'Item 8','Sample description 8',13.25,45,'SKU027','pcs',1.0,'BrandD',1,1,NOW(),NOW()),
    (28,30,'Item 9','Sample description 9',9.75,60,'SKU028','pcs',1.0,'BrandE',1,1,NOW(),NOW()),
    (29,30,'Item 10','Sample description 10',14.00,35,'SKU029','pcs',1.0,'BrandE',1,1,NOW(),NOW()),
    (30,30,'Item 11','Sample description 11',16.50,20,'SKU030','pcs',1.0,'BrandF',1,1,NOW(),NOW()),
    (31,30,'Item 12','Sample description 12',18.00,40,'SKU031','pcs',1.0,'BrandF',1,1,NOW(),NOW()),
    (32,30,'Item 13','Sample description 13',22.50,30,'SKU032','pcs',1.0,'BrandG',1,1,NOW(),NOW()),
    (33,30,'Item 14','Sample description 14',6.75,80,'SKU033','pcs',1.0,'BrandG',1,1,NOW(),NOW()),
    (34,30,'Item 15','Sample description 15',12.00,45,'SKU034','pcs',1.0,'BrandH',1,1,NOW(),NOW()),
    (35,30,'Item 16','Sample description 16',19.00,25,'SKU035','pcs',1.0,'BrandH',1,1,NOW(),NOW()),
    (36,30,'Item 17','Sample description 17',21.00,15,'SKU036','pcs',1.0,'BrandI',1,1,NOW(),NOW()),
    (37,30,'Item 18','Sample description 18',9.50,55,'SKU037','pcs',1.0,'BrandI',1,1,NOW(),NOW()),
    (38,30,'Item 19','Sample description 19',17.25,35,'SKU038','pcs',1.0,'BrandJ',1,1,NOW(),NOW()),
    (39,30,'Item 20','Sample description 20',14.50,40,'SKU039','pcs',1.0,'BrandJ',1,1,NOW(),NOW());

-- -------------------
-- ORDERS (Sample 20 orders)
-- -------------------
INSERT INTO orders (order_id, customer_id, address_id, shipping_method, order_total, order_status, created_at, updated_at)
VALUES
    (20,20,20,'standard',50.00,'completed',NOW(),NOW()),
    (21,21,21,'express',75.00,'completed',NOW(),NOW()),
    (22,22,22,'standard',60.00,'completed',NOW(),NOW()),
    (23,23,23,'express',120.00,'completed',NOW(),NOW()),
    (24,24,24,'standard',30.00,'completed',NOW(),NOW()),
    (25,20,20,'express',80.00,'completed',NOW(),NOW()),
    (26,21,21,'standard',55.00,'completed',NOW(),NOW()),
    (27,22,22,'express',95.00,'completed',NOW(),NOW()),
    (28,23,23,'standard',40.00,'completed',NOW(),NOW()),
    (29,24,24,'express',70.00,'completed',NOW(),NOW()),
    (30,20,20,'standard',65.00,'completed',NOW(),NOW()),
    (31,21,21,'express',85.00,'completed',NOW(),NOW()),
    (32,22,22,'standard',50.00,'completed',NOW(),NOW()),
    (33,23,23,'express',110.00,'completed',NOW(),NOW()),
    (34,24,24,'standard',35.00,'completed',NOW(),NOW()),
    (35,20,20,'express',90.00,'completed',NOW(),NOW()),
    (36,21,21,'standard',60.00,'completed',NOW(),NOW()),
    (37,22,22,'express',100.00,'completed',NOW(),NOW()),
    (38,23,23,'standard',45.00,'completed',NOW(),NOW()),
    (39,24,24,'express',75.00,'completed',NOW(),NOW());

-- -------------------
-- CARTS, ORDER_ITEMS, PAYMENT_TRANSACTIONS, CATEGORY_ITEMS, ITEM_IMAGES, DISCOUNTS, PAGES, PAGE_ITEMS, BUNDLES, STOCK_LOGS, ETC.
-- -------------------
-- You can continue the pattern: use IDs starting 20+,
-- maintain foreign key relationships,
-- insert 20 records per entity, use NOW() for timestamps.
INSERT INTO orders (order_id, customer_id, address_id, shipping_method, order_total, order_status, created_at, updated_at)
VALUES
    (60, 20, 20, 'standard', 40.00, 'completed', NOW(), NOW()),
    (61, 21, 21, 'express', 35.00, 'completed', NOW(), NOW()),
    (62, 22, 22, 'standard', 50.00, 'completed', NOW(), NOW()),
    (63, 23, 23, 'express', 60.00, 'completed', NOW(), NOW()),
    (64, 24, 24, 'standard', 30.00, 'completed', NOW(), NOW()),
    (65, 25, 20, 'express', 55.00, 'completed', NOW(), NOW()),
    (66, 26, 21, 'standard', 45.00, 'completed', NOW(), NOW()),
    (67, 27, 22, 'express', 70.00, 'completed', NOW(), NOW()),
    (68, 28, 23, 'standard', 25.00, 'completed', NOW(), NOW()),
    (69, 29, 24, 'express', 65.00, 'completed', NOW(), NOW()),
    (70, 20, 20, 'standard', 30.00, 'completed', NOW(), NOW()),
    (71, 21, 21, 'express', 40.00, 'completed', NOW(), NOW()),
    (72, 22, 22, 'standard', 55.00, 'completed', NOW(), NOW()),
    (73, 23, 23, 'express', 60.00, 'completed', NOW(), NOW()),
    (74, 24, 24, 'standard', 35.00, 'completed', NOW(), NOW()),
    (75, 25, 20, 'express', 50.00, 'completed', NOW(), NOW()),
    (76, 26, 21, 'standard', 45.00, 'completed', NOW(), NOW()),
    (77, 27, 22, 'express', 60.00, 'completed', NOW(), NOW()),
    (78, 28, 23, 'standard', 40.00, 'completed', NOW(), NOW()),
    (79, 29, 24, 'express', 55.00, 'completed', NOW(), NOW());

INSERT INTO orders (order_id, customer_id, address_id, shipping_method, order_total, order_status)
VALUES
    (85, 20, 20, 'standard', 45.00, 'completed'),
    (86, 21, 21, 'express', 70.00, 'completed'),
    (87, 22, 22, 'standard', 55.00, 'completed'),
    (88, 23, 23, 'express', 90.00, 'completed'),
    (89, 24, 24, 'standard', 35.00, 'completed'),
    (90, 20, 20, 'express', 60.00, 'completed'),
    (91, 21, 21, 'standard', 50.00, 'completed'),
    (92, 22, 22, 'express', 80.00, 'completed'),
    (93, 23, 23, 'standard', 40.00, 'completed'),
    (94, 24, 24, 'express', 75.00, 'completed'),
    (95, 20, 20, 'standard', 65.00, 'completed'),
    (96, 21, 21, 'express', 85.00, 'completed'),
    (97, 22, 22, 'standard', 55.00, 'completed'),
    (98, 23, 23, 'express', 95.00, 'completed'),
    (99, 24, 24, 'standard', 45.00, 'completed'),
    (100, 20, 20, 'express', 70.00, 'completed'),
    (101, 21, 21, 'standard', 60.00, 'completed'),
    (102, 22, 22, 'express', 85.00, 'completed'),
    (103, 23, 23, 'standard', 50.00, 'completed'),
    (104, 24, 24, 'express', 90.00, 'completed');

-- -------------------
-- ORDER ITEMS for the above orders (Vendor 30 items only: item_id 20 & 21)
-- -------------------
INSERT INTO order_items (order_id, item_id, quantity, price)
VALUES
    (85, 20, 2, 10.00),
    (85, 21, 1, 12.50),
    (86, 20, 1, 10.00),
    (86, 21, 3, 12.50),
    (87, 20, 2, 10.00),
    (87, 21, 2, 12.50),
    (88, 20, 3, 10.00),
    (88, 21, 1, 12.50),
    (89, 20, 1, 10.00),
    (89, 21, 2, 12.50),
    (90, 20, 2, 10.00),
    (90, 21, 2, 12.50),
    (91, 20, 1, 10.00),
    (91, 21, 3, 12.50),
    (92, 20, 3, 10.00),
    (92, 21, 2, 12.50),
    (93, 20, 2, 10.00),
    (93, 21, 1, 12.50),
    (94, 20, 1, 10.00),
    (94, 21, 3, 12.50),
    (95, 20, 2, 10.00),
    (95, 21, 2, 12.50),
    (96, 20, 3, 10.00),
    (96, 21, 1, 12.50),
    (97, 20, 1, 10.00),
    (97, 21, 2, 12.50),
    (98, 20, 2, 10.00),
    (98, 21, 3, 12.50),
    (99, 20, 1, 10.00),
    (99, 21, 2, 12.50),
    (100, 20, 3, 10.00),
    (100, 21, 1, 12.50),
    (101, 20, 2, 10.00),
    (101, 21, 2, 12.50),
    (102, 20, 1, 10.00),
    (102, 21, 3, 12.50),
    (103, 20, 2, 10.00),
    (103, 21, 1, 12.50),
    (104, 20, 3, 10.00),
    (104, 21, 2, 12.50);

USE dormdash_db_v4;

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

