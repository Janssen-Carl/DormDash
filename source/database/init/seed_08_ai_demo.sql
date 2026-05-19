-- =============================================
-- SEED 08: AI demo orders + extra customers for analytics
-- Generates 60-day order history across vendors 1..5
-- Adds 5 extra customer accounts for richer data variety
-- Sets some items to low stock for inventory alerts
-- =============================================

-- ===== EXTRA CUSTOMER USERS (user_id 9..13) =====
INSERT IGNORE INTO users (username, email, email_verified_at, role, password, created_at, updated_at) VALUES
('ana_garcia',    'ana@example.com',    NOW(), 'customer', '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW()),
('ben_lim',       'ben@example.com',    NOW(), 'customer', '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW()),
('clara_tan',     'clara@example.com',  NOW(), 'customer', '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW()),
('david_sy',      'david@example.com',  NOW(), 'customer', '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW()),
('ella_mendoza',  'ella@example.com',   NOW(), 'customer', '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW());

-- Extra addresses (user_id 9..13)
INSERT IGNORE INTO addresses (user_id, street, city, province_state, postal_code, phone, email, country, created_at, updated_at) VALUES
(9,  '401 Dorm D Bldg 4',  'Batangas City', 'Batangas', '4200', '09281110009', 'ana@example.com',   'Philippines', NOW(), NOW()),
(10, '502 Dorm E Bldg 5',  'Lipa City',     'Batangas', '4217', '09281110010', 'ben@example.com',   'Philippines', NOW(), NOW()),
(11, '603 Dorm F Bldg 6',  'Tanauan',       'Batangas', '4232', '09281110011', 'clara@example.com', 'Philippines', NOW(), NOW()),
(12, '704 Dorm G Bldg 7',  'Batangas City', 'Batangas', '4200', '09281110012', 'david@example.com', 'Philippines', NOW(), NOW()),
(13, '805 Dorm H Bldg 8',  'Lipa City',     'Batangas', '4217', '09281110013', 'ella@example.com',  'Philippines', NOW(), NOW());

-- Extra customers (customer_id = user_id)
INSERT IGNORE INTO customers (customer_id, first_name, last_name, phone, birthdate, gender, profile_img, primary_address_id, created_at, updated_at) VALUES
(9,  'Ana',   'Garcia',  '09281110009', '2002-06-18', 'Female', NULL, 9,  NOW(), NOW()),
(10, 'Ben',   'Lim',     '09281110010', '2001-11-02', 'Male',   NULL, 10, NOW(), NOW()),
(11, 'Clara', 'Tan',     '09281110011', '2003-04-25', 'Female', NULL, 11, NOW(), NOW()),
(12, 'David', 'Sy',      '09281110012', '2002-09-30', 'Male',   NULL, 12, NOW(), NOW()),
(13, 'Ella',  'Mendoza', '09281110013', '2001-12-14', 'Female', NULL, 13, NOW(), NOW());

-- ===== Set some items to very low stock for inventory alerts =====
-- (These will be visible in the analytics Inventory Alerts section)
SET @v1s = (SELECT MIN(item_id) FROM items WHERE vendor_id = 1);
SET @v2s = (SELECT MIN(item_id) FROM items WHERE vendor_id = 2);
SET @v3s = (SELECT MIN(item_id) FROM items WHERE vendor_id = 3);
SET @v4s = (SELECT MIN(item_id) FROM items WHERE vendor_id = 4);
SET @v5s = (SELECT MIN(item_id) FROM items WHERE vendor_id = 5);

-- Vendor 1: 2 items low stock
UPDATE items SET stock = 3  WHERE item_id = @v1s + 4;
UPDATE items SET stock = 7  WHERE item_id = @v1s + 8;

-- Vendor 2: 2 items low stock
UPDATE items SET stock = 2  WHERE item_id = @v2s + 3;
UPDATE items SET stock = 5  WHERE item_id = @v2s + 6;

-- Vendor 3: 3 items low stock
UPDATE items SET stock = 1  WHERE item_id = @v3s + 1;
UPDATE items SET stock = 4  WHERE item_id = @v3s + 5;
UPDATE items SET stock = 8  WHERE item_id = @v3s + 9;

-- Vendor 4: 2 items low stock
UPDATE items SET stock = 6  WHERE item_id = @v4s + 2;
UPDATE items SET stock = 3  WHERE item_id = @v4s + 7;

-- Vendor 5: 2 items low stock
UPDATE items SET stock = 2  WHERE item_id = @v5s + 0;
UPDATE items SET stock = 9  WHERE item_id = @v5s + 4;

-- ===== GENERATE 60-DAY ORDER HISTORY =====
-- Determine starting order_id
SET @next_order_id = (SELECT COALESCE(MAX(order_id), 199) + 1 FROM orders);

-- Create a stored procedure to insert demo orders
DROP PROCEDURE IF EXISTS create_demo_orders;
DELIMITER $$
CREATE PROCEDURE create_demo_orders()
BEGIN
  DECLARE v INT DEFAULT 1;
  DECLARE end_v INT DEFAULT 5;
  DECLARE day INT;
  DECLARE o_id INT;
  DECLARE cust_id INT DEFAULT 6;
  DECLARE ship_method VARCHAR(20);
  DECLARE ord_status VARCHAR(20);
  WHILE v <= end_v DO
    SET @first_item = (SELECT MIN(item_id) FROM items WHERE vendor_id = v);
    SET @item_count = (SELECT COUNT(*) FROM items WHERE vendor_id = v);
    IF @first_item IS NOT NULL THEN
      SET day = 1;
      WHILE day <= 60 DO
        SET o_id = @next_order_id;

        -- Vary shipping method
        IF day % 3 = 0 THEN SET ship_method = 'express';
        ELSEIF day % 5 = 0 THEN SET ship_method = 'same_day';
        ELSE SET ship_method = 'standard';
        END IF;

        -- Vary order status: older orders completed, recent ones mixed
        IF day <= 45 THEN SET ord_status = 'completed';
        ELSEIF day <= 50 THEN SET ord_status = 'delivered';
        ELSEIF day <= 55 THEN SET ord_status = 'shipped';
        ELSEIF day <= 58 THEN SET ord_status = 'to_ship';
        ELSE SET ord_status = 'pending';
        END IF;

        INSERT INTO orders (order_id, customer_id, address_id, shipping_method, order_total, order_status, created_at, updated_at)
        VALUES (o_id, cust_id, cust_id, ship_method, 0.00, ord_status, DATE_SUB(NOW(), INTERVAL (61 - day) DAY), NOW());

        -- Choose 2-3 items per order deterministically with varied offsets
        SET @item_a = @first_item + ((day + v) % LEAST(@item_count, 10));
        SET @item_b = @first_item + ((day + v + 3) % LEAST(@item_count, 10));
        SET @item_c = @first_item + ((day + v + 7) % LEAST(@item_count, 10));

        IF NOT EXISTS (SELECT 1 FROM items WHERE item_id = @item_a AND vendor_id = v) THEN
          SET @item_a = @first_item;
        END IF;
        IF NOT EXISTS (SELECT 1 FROM items WHERE item_id = @item_b AND vendor_id = v) THEN
          SET @item_b = @first_item;
        END IF;
        IF NOT EXISTS (SELECT 1 FROM items WHERE item_id = @item_c AND vendor_id = v) THEN
          SET @item_c = @first_item;
        END IF;

        -- Varied quantities (1-4)
        SET @qty_a = ((day % 4) + 1);
        SET @qty_b = (((day + 2) % 3) + 1);
        SET @qty_c = (((day + 1) % 2) + 1);

        INSERT INTO order_items (order_id, item_id, quantity, price)
        SELECT o_id, @item_a, @qty_a, price FROM items WHERE item_id = @item_a;

        -- Only add item_b if different from item_a
        IF @item_b != @item_a THEN
          INSERT INTO order_items (order_id, item_id, quantity, price)
          SELECT o_id, @item_b, @qty_b, price FROM items WHERE item_id = @item_b;
        END IF;

        -- Every 3rd day add a 3rd item for higher-value orders
        IF day % 3 = 0 AND @item_c != @item_a AND @item_c != @item_b THEN
          INSERT INTO order_items (order_id, item_id, quantity, price)
          SELECT o_id, @item_c, @qty_c, price FROM items WHERE item_id = @item_c;
        END IF;

        -- increment counters
        SET @next_order_id = @next_order_id + 1;
        SET cust_id = cust_id + 1;
        IF cust_id > 13 THEN SET cust_id = 6; END IF;
        SET day = day + 1;
      END WHILE;
    END IF;

    SET v = v + 1;
  END WHILE;
END$$
DELIMITER ;

CALL create_demo_orders();
DROP PROCEDURE IF EXISTS create_demo_orders;

-- ===== Aggregate stock logs =====
INSERT INTO stock_logs (item_id, old_stock, new_stock, quantity_changed, remarks, created_at)
SELECT
  i.item_id,
  i.stock AS old_stock,
  GREATEST(i.stock - IFNULL(oi.sold, 0), 0) AS new_stock,
  IFNULL(oi.sold, 0) AS quantity_changed,
  'seed_ai_orders' AS remarks,
  NOW()
FROM items i
LEFT JOIN (
  SELECT item_id, SUM(quantity) AS sold
  FROM order_items
  GROUP BY item_id
) oi ON oi.item_id = i.item_id
WHERE IFNULL(oi.sold, 0) > 0;

-- ===== Recompute order totals =====
UPDATE orders o
SET order_total = (
  SELECT COALESCE(SUM(oi.price * oi.quantity), 0)
  FROM order_items oi
  WHERE oi.order_id = o.order_id
);

-- ===== Add send_date and tracking for shipped/delivered/completed orders =====
UPDATE orders
SET send_date = DATE_ADD(created_at, INTERVAL 1 DAY),
    tracking_number = CONCAT('TRK-', DATE_FORMAT(created_at, '%Y%m%d'), '-', order_id)
WHERE order_status IN ('shipped', 'delivered', 'completed');

-- Add receive_date for delivered/completed orders
UPDATE orders
SET receive_date = DATE_ADD(created_at, INTERVAL 3 DAY)
WHERE order_status IN ('delivered', 'completed');

-- ===== Payment transactions for completed/delivered/shipped orders =====
INSERT INTO payment_transactions (order_id, amount, created_at, status, reference_no, token, acc_last4_no)
SELECT o.order_id, o.order_total, DATE_ADD(o.created_at, INTERVAL 5 MINUTE), 'completed',
       CONCAT('DEMO-', o.order_id), NULL,
       LPAD(FLOOR(RAND() * 10000), 4, '0')
FROM orders o
WHERE o.order_status IN ('completed', 'delivered', 'shipped', 'to_ship')
  AND NOT EXISTS (SELECT 1 FROM payment_transactions p WHERE p.order_id = o.order_id);

-- Done
SELECT 'seed_08_ai_demo completed' AS status;
