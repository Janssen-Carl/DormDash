-- ============================================================
-- 01_schema.sql — Full schema for multivendor_db
-- Auto-run by MySQL on first container boot
-- ============================================================

USE `multivendor_db`;

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` CHAR(36) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('customer', 'vendor', 'admin') NOT NULL DEFAULT 'customer',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `contact_number` VARCHAR(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email` (`email` ASC)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `vendors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vendors` (
  `id` CHAR(36) NOT NULL,
  `user_id` CHAR(36) NOT NULL,
  `store_name` VARCHAR(150) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_id` (`user_id` ASC),
  CONSTRAINT `vendors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` CHAR(36) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `parent_id` CHAR(36) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `parent_id` (`parent_id` ASC),
  CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` CHAR(36) NOT NULL,
  `vendor_id` CHAR(36) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock_qty` INT NOT NULL DEFAULT '0',
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `category_id` CHAR(36) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_products_vendor` (`vendor_id` ASC),
  INDEX `category_id` (`category_id` ASC),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `bundles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bundles` (
  `id` CHAR(36) NOT NULL,
  `vendor_id` CHAR(36) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `bundle_price` DECIMAL(10,2) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_bundles_vendor` (`vendor_id` ASC),
  CONSTRAINT `bundles_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `bundle_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bundle_items` (
  `id` CHAR(36) NOT NULL,
  `bundle_id` CHAR(36) NOT NULL,
  `product_id` CHAR(36) NOT NULL,
  `quantity_required` INT NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_bundle_product` (`bundle_id` ASC, `product_id` ASC),
  INDEX `product_id` (`product_id` ASC),
  CONSTRAINT `bundle_items_ibfk_1` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`),
  CONSTRAINT `bundle_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `product_variants`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_variants` (
  `id` CHAR(36) NOT NULL,
  `product_id` CHAR(36) NOT NULL,
  `name` VARCHAR(100) NOT NULL COMMENT 'e.g. Size, Color',
  `value` VARCHAR(100) NOT NULL COMMENT 'e.g. Large, Red',
  `price_modifier` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `stock_qty` INT NOT NULL DEFAULT '0',
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  INDEX `idx_product_variants_product_id` (`product_id` ASC),
  CONSTRAINT `fk_product_variants_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `user_addresses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_addresses` (
  `id` CHAR(36) NOT NULL,
  `user_id` CHAR(36) NOT NULL,
  `label` VARCHAR(50) NULL DEFAULT NULL,
  `address_line` VARCHAR(255) NOT NULL,
  `is_default` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `user_id` (`user_id` ASC),
  CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `discounts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `discounts` (
  `id` CHAR(36) NOT NULL,
  `vendor_id` CHAR(36) NULL DEFAULT NULL,
  `code` VARCHAR(50) NULL DEFAULT NULL,
  `type` ENUM('percentage', 'fixed_amount') NOT NULL,
  `value` DECIMAL(10,2) NOT NULL,
  `target_type` ENUM('product', 'bundle', 'order') NULL DEFAULT NULL,
  `target_id` CHAR(36) NULL DEFAULT NULL,
  `expires_at` DATETIME NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code` (`code` ASC),
  INDEX `vendor_id` (`vendor_id` ASC),
  CONSTRAINT `discounts_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` CHAR(36) NOT NULL,
  `user_id` CHAR(36) NOT NULL,
  `discount_id` CHAR(36) NULL DEFAULT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `discount_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `total` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending', 'paid', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `address_id` CHAR(36) NULL DEFAULT NULL,
  `delivery_type` ENUM('pickup', 'delivery') NULL DEFAULT 'delivery',
  PRIMARY KEY (`id`),
  INDEX `discount_id` (`discount_id` ASC),
  INDEX `idx_orders_user` (`user_id` ASC),
  INDEX `address_id` (`address_id` ASC),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`),
  CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`address_id`) REFERENCES `user_addresses` (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `order_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` CHAR(36) NOT NULL,
  `order_id` CHAR(36) NOT NULL,
  `product_id` CHAR(36) NULL DEFAULT NULL,
  `variant_id` CHAR(36) NULL DEFAULT NULL,
  `bundle_id` CHAR(36) NULL DEFAULT NULL,
  `quantity` INT NOT NULL DEFAULT '1',
  `unit_price` DECIMAL(10,2) NOT NULL,
  `discount_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `total` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `product_id` (`product_id` ASC),
  INDEX `bundle_id` (`bundle_id` ASC),
  INDEX `idx_order_items_order` (`order_id` ASC),
  INDEX `idx_order_items_variant_id` (`variant_id` ASC),
  CONSTRAINT `fk_order_items_variant`
    FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `payments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id` CHAR(36) NOT NULL,
  `order_id` CHAR(36) NOT NULL,
  `method` VARCHAR(50) NOT NULL,
  `status` ENUM('pending', 'paid', 'failed', 'refunded') NULL DEFAULT 'pending',
  `amount` DECIMAL(10,2) NOT NULL,
  `reference_no` VARCHAR(100) NULL DEFAULT NULL,
  `paid_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `order_id` (`order_id` ASC),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `cart`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cart` (
  `id` CHAR(36) NOT NULL,
  `user_id` CHAR(36) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_cart_user_id` (`user_id` ASC),
  CONSTRAINT `fk_cart_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `cart_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` CHAR(36) NOT NULL,
  `cart_id` CHAR(36) NOT NULL,
  `product_id` CHAR(36) NULL DEFAULT NULL,
  `variant_id` CHAR(36) NULL DEFAULT NULL,
  `bundle_id` CHAR(36) NULL DEFAULT NULL,
  `quantity` INT NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  INDEX `idx_cart_items_cart_id` (`cart_id` ASC),
  INDEX `idx_cart_items_product_id` (`product_id` ASC),
  INDEX `idx_cart_items_variant_id` (`variant_id` ASC),
  INDEX `idx_cart_items_bundle_id` (`bundle_id` ASC),
  CONSTRAINT `fk_cart_items_bundle`
    FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_cart_items_cart`
    FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cart_items_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_cart_items_variant`
    FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `reviews`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` CHAR(36) NOT NULL,
  `user_id` CHAR(36) NOT NULL,
  `product_id` CHAR(36) NOT NULL,
  `order_id` CHAR(36) NOT NULL,
  `rating` TINYINT NOT NULL COMMENT '1 to 5',
  `comment` TEXT NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_reviews_user_product_order` (`user_id` ASC, `product_id` ASC, `order_id` ASC),
  INDEX `idx_reviews_product_id` (`product_id` ASC),
  INDEX `idx_reviews_order_id` (`order_id` ASC),
  CONSTRAINT `fk_reviews_order`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reviews_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reviews_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;