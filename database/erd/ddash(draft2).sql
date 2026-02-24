-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema multivendor_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema multivendor_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `multivendor_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `multivendor_db` ;

-- -----------------------------------------------------
-- Table `multivendor_db`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`users` (
  `id` CHAR(36) NOT NULL DEFAULT uuid(),
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('customer', 'vendor', 'admin') NOT NULL DEFAULT 'customer',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `contact_number` VARCHAR(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email` (`email` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`vendors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`vendors` (
  `id` CHAR(36) NOT NULL DEFAULT uuid(),
  `user_id` CHAR(36) NOT NULL,
  `store_name` VARCHAR(150) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_id` (`user_id` ASC) VISIBLE,
  CONSTRAINT `vendors_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `multivendor_db`.`users` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`bundles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`bundles` (
  `id` CHAR(36) NOT NULL DEFAULT uuid(),
  `vendor_id` CHAR(36) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `bundle_price` DECIMAL(10,2) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_bundles_vendor` (`vendor_id` ASC) VISIBLE,
  CONSTRAINT `bundles_ibfk_1`
    FOREIGN KEY (`vendor_id`)
    REFERENCES `multivendor_db`.`vendors` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`categories` (
  `id` CHAR(36) NOT NULL DEFAULT uuid(),
  `name` VARCHAR(100) NOT NULL,
  `parent_id` CHAR(36) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `parent_id` (`parent_id` ASC) VISIBLE,
  CONSTRAINT `categories_ibfk_1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `multivendor_db`.`categories` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`products` (
  `id` CHAR(36) NOT NULL DEFAULT uuid(),
  `vendor_id` CHAR(36) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock_qty` INT NOT NULL DEFAULT '0',
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `category_id` CHAR(36) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_products_vendor` (`vendor_id` ASC) VISIBLE,
  INDEX `category_id` (`category_id` ASC) VISIBLE,
  CONSTRAINT `products_ibfk_1`
    FOREIGN KEY (`vendor_id`)
    REFERENCES `multivendor_db`.`vendors` (`id`),
  CONSTRAINT `products_ibfk_2`
    FOREIGN KEY (`category_id`)
    REFERENCES `multivendor_db`.`categories` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`bundle_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`bundle_items` (
  `id` CHAR(36) NOT NULL DEFAULT uuid(),
  `bundle_id` CHAR(36) NOT NULL,
  `product_id` CHAR(36) NOT NULL,
  `quantity_required` INT NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_bundle_product` (`bundle_id` ASC, `product_id` ASC) VISIBLE,
  INDEX `product_id` (`product_id` ASC) VISIBLE,
  CONSTRAINT `bundle_items_ibfk_1`
    FOREIGN KEY (`bundle_id`)
    REFERENCES `multivendor_db`.`bundles` (`id`),
  CONSTRAINT `bundle_items_ibfk_2`
    FOREIGN KEY (`product_id`)
    REFERENCES `multivendor_db`.`products` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`cart`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`cart` (
  `id` CHAR(36) NOT NULL,
  `user_id` CHAR(36) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_cart_user_id` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_cart_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `multivendor_db`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`product_variants`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`product_variants` (
  `id` CHAR(36) NOT NULL,
  `product_id` CHAR(36) NOT NULL,
  `name` VARCHAR(100) NOT NULL COMMENT 'e.g. Size, Color',
  `value` VARCHAR(100) NOT NULL COMMENT 'e.g. Large, Red',
  `price_modifier` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Added on top of base product price',
  `stock_qty` INT NOT NULL DEFAULT '0',
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  INDEX `idx_product_variants_product_id` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_product_variants_product`
    FOREIGN KEY (`product_id`)
    REFERENCES `multivendor_db`.`products` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`cart_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`cart_items` (
  `id` CHAR(36) NOT NULL,
  `cart_id` CHAR(36) NOT NULL,
  `product_id` CHAR(36) NULL DEFAULT NULL COMMENT 'NULL if item is a bundle',
  `variant_id` CHAR(36) NULL DEFAULT NULL COMMENT 'NULL if product has no variants',
  `bundle_id` CHAR(36) NULL DEFAULT NULL COMMENT 'NULL if item is a product',
  `quantity` INT NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  INDEX `idx_cart_items_cart_id` (`cart_id` ASC) VISIBLE,
  INDEX `idx_cart_items_product_id` (`product_id` ASC) VISIBLE,
  INDEX `idx_cart_items_variant_id` (`variant_id` ASC) VISIBLE,
  INDEX `idx_cart_items_bundle_id` (`bundle_id` ASC) VISIBLE,
  CONSTRAINT `fk_cart_items_bundle`
    FOREIGN KEY (`bundle_id`)
    REFERENCES `multivendor_db`.`bundles` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_cart_items_cart`
    FOREIGN KEY (`cart_id`)
    REFERENCES `multivendor_db`.`cart` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_cart_items_product`
    FOREIGN KEY (`product_id`)
    REFERENCES `multivendor_db`.`products` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_cart_items_variant`
    FOREIGN KEY (`variant_id`)
    REFERENCES `multivendor_db`.`product_variants` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`discounts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`discounts` (
  `id` CHAR(36) NOT NULL DEFAULT uuid(),
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
  UNIQUE INDEX `code` (`code` ASC) VISIBLE,
  INDEX `vendor_id` (`vendor_id` ASC) VISIBLE,
  CONSTRAINT `discounts_ibfk_1`
    FOREIGN KEY (`vendor_id`)
    REFERENCES `multivendor_db`.`vendors` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`user_addresses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`user_addresses` (
  `id` CHAR(36) NOT NULL DEFAULT uuid(),
  `user_id` CHAR(36) NOT NULL,
  `label` VARCHAR(50) NULL DEFAULT NULL,
  `address_line` VARCHAR(255) NOT NULL,
  `is_default` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `user_id` (`user_id` ASC) VISIBLE,
  CONSTRAINT `user_addresses_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `multivendor_db`.`users` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`orders` (
  `id` CHAR(36) NOT NULL DEFAULT uuid(),
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
  INDEX `discount_id` (`discount_id` ASC) VISIBLE,
  INDEX `idx_orders_user` (`user_id` ASC) VISIBLE,
  INDEX `address_id` (`address_id` ASC) VISIBLE,
  CONSTRAINT `orders_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `multivendor_db`.`users` (`id`),
  CONSTRAINT `orders_ibfk_2`
    FOREIGN KEY (`discount_id`)
    REFERENCES `multivendor_db`.`discounts` (`id`),
  CONSTRAINT `orders_ibfk_3`
    FOREIGN KEY (`address_id`)
    REFERENCES `multivendor_db`.`user_addresses` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`order_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`order_items` (
  `id` CHAR(36) NOT NULL DEFAULT uuid(),
  `order_id` CHAR(36) NOT NULL,
  `product_id` CHAR(36) NULL DEFAULT NULL,
  `variant_id` CHAR(36) NULL DEFAULT NULL COMMENT 'References the specific variant purchased, if any',
  `bundle_id` CHAR(36) NULL DEFAULT NULL,
  `quantity` INT NOT NULL DEFAULT '1',
  `unit_price` DECIMAL(10,2) NOT NULL,
  `discount_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `total` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `product_id` (`product_id` ASC) VISIBLE,
  INDEX `bundle_id` (`bundle_id` ASC) VISIBLE,
  INDEX `idx_order_items_order` (`order_id` ASC) VISIBLE,
  INDEX `idx_order_items_variant_id` (`variant_id` ASC) VISIBLE,
  CONSTRAINT `fk_order_items_variant`
    FOREIGN KEY (`variant_id`)
    REFERENCES `multivendor_db`.`product_variants` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `order_items_ibfk_1`
    FOREIGN KEY (`order_id`)
    REFERENCES `multivendor_db`.`orders` (`id`),
  CONSTRAINT `order_items_ibfk_2`
    FOREIGN KEY (`product_id`)
    REFERENCES `multivendor_db`.`products` (`id`),
  CONSTRAINT `order_items_ibfk_3`
    FOREIGN KEY (`bundle_id`)
    REFERENCES `multivendor_db`.`bundles` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`payments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`payments` (
  `id` CHAR(36) NOT NULL DEFAULT uuid(),
  `order_id` CHAR(36) NOT NULL,
  `method` VARCHAR(50) NOT NULL,
  `status` ENUM('pending', 'paid', 'failed', 'refunded') NULL DEFAULT 'pending',
  `amount` DECIMAL(10,2) NOT NULL,
  `reference_no` VARCHAR(100) NULL DEFAULT NULL,
  `paid_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `order_id` (`order_id` ASC) VISIBLE,
  CONSTRAINT `payments_ibfk_1`
    FOREIGN KEY (`order_id`)
    REFERENCES `multivendor_db`.`orders` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `multivendor_db`.`reviews`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `multivendor_db`.`reviews` (
  `id` CHAR(36) NOT NULL,
  `user_id` CHAR(36) NOT NULL,
  `product_id` CHAR(36) NOT NULL,
  `order_id` CHAR(36) NOT NULL COMMENT 'Ensures reviewer actually purchased the product',
  `rating` TINYINT NOT NULL COMMENT '1 to 5',
  `comment` TEXT NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_reviews_user_product_order` (`user_id` ASC, `product_id` ASC, `order_id` ASC) VISIBLE,
  INDEX `idx_reviews_product_id` (`product_id` ASC) VISIBLE,
  INDEX `idx_reviews_order_id` (`order_id` ASC) VISIBLE,
  CONSTRAINT `fk_reviews_order`
    FOREIGN KEY (`order_id`)
    REFERENCES `multivendor_db`.`orders` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_reviews_product`
    FOREIGN KEY (`product_id`)
    REFERENCES `multivendor_db`.`products` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_reviews_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `multivendor_db`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
