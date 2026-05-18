-- =============================================
-- SEED 08: AI demo orders for multiple vendors
-- Generates realistic order history across vendors 30..39
-- Inserts orders, order_items, aggregates stock_logs and updates item stock
-- =============================================

-- Determine starting order_id
SET @next_order_id = (SELECT COALESCE(MAX(order_id), 199) + 1 FROM orders);

-- Create a stored procedure to insert demo orders
DROP PROCEDURE IF EXISTS create_demo_orders;
DELIMITER $$
CREATE PROCEDURE create_demo_orders()
BEGIN
  DECLARE v INT DEFAULT 30; -- vendor start
  DECLARE end_v INT DEFAULT 39; -- vendor end
  DECLARE day INT;
  DECLARE o_id INT;
  DECLARE cust_id INT DEFAULT 20;
  WHILE v <= end_v DO
    -- get vendor's first item id
    SET @first_item = (SELECT MIN(item_id) FROM items WHERE vendor_id = v);
    IF @first_item IS NULL THEN
      SET v = v + 1;
      ITERATE;
    END IF;

    SET day = 1;
    WHILE day <= 30 DO
      SET o_id = @next_order_id;

      INSERT INTO orders (order_id, customer_id, address_id, shipping_method, order_total, order_status, created_at, updated_at)
      VALUES (o_id, cust_id, cust_id, 'standard', 0.00, 'completed', DATE_SUB(NOW(), INTERVAL (31 - day) DAY), NOW());

      -- choose two items per order deterministically
      SET @item_a = @first_item + ((day + v) % 8);
      SET @item_b = @first_item + ((day + v + 3) % 8);
      IF NOT EXISTS (SELECT 1 FROM items WHERE item_id = @item_a) THEN
        SET @item_a = @first_item;
      END IF;
      IF NOT EXISTS (SELECT 1 FROM items WHERE item_id = @item_b) THEN
        SET @item_b = @first_item;
      END IF;

      SET @qty_a = ((day % 3) + 1);
      SET @qty_b = (((day + 1) % 4) + 1);

      INSERT INTO order_items (order_id, item_id, quantity, price)
      SELECT o_id, @item_a, @qty_a, price FROM items WHERE item_id = @item_a;

      INSERT INTO order_items (order_id, item_id, quantity, price)
      SELECT o_id, @item_b, @qty_b, price FROM items WHERE item_id = @item_b;

      -- increment counters
      SET @next_order_id = @next_order_id + 1;
      SET cust_id = cust_id + 1;
      IF cust_id > 39 THEN SET cust_id = 20; END IF;
      SET day = day + 1;
    END WHILE;

    SET v = v + 1;
  END WHILE;
END$$
DELIMITER ;

-- Execute the procedure to create orders
CALL create_demo_orders();

-- Clean up: drop the procedure
DROP PROCEDURE IF EXISTS create_demo_orders;

-- Aggregate stock logs per item based on created order_items
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

-- Update items' stock according to sold quantities (never negative)
UPDATE items i
LEFT JOIN (
  SELECT item_id, SUM(quantity) AS sold
  FROM order_items
  GROUP BY item_id
) oi ON oi.item_id = i.item_id
SET i.stock = GREATEST(i.stock - IFNULL(oi.sold, 0), 0);

-- Recompute order totals for inserted orders
UPDATE orders o
SET order_total = (
  SELECT COALESCE(SUM(oi.price * oi.quantity), 0)
  FROM order_items oi
  WHERE oi.order_id = o.order_id
)
WHERE o.order_id >= (SELECT COALESCE(MIN(order_id), 99999) FROM orders);

-- Add payment_transactions for demo completed orders
INSERT INTO payment_transactions (order_id, amount, created_at, status, reference_no, token, acc_last4_no)
SELECT o.order_id, o.order_total, NOW(), 'completed', CONCAT('DEMO-', o.order_id), NULL, '0000'
FROM orders o
WHERE o.order_status = 'completed' AND NOT EXISTS (
  SELECT 1 FROM payment_transactions p WHERE p.order_id = o.order_id
);

-- Done
SELECT 'seed_08_ai_demo completed' AS status;
