CREATE DATABASE IF NOT EXISTS dormdash_db_v3;
USE dormdash_db_v3;

-- =========================================
--  Only use on dev
-- =========================================
-- CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'pass';
-- GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
-- FLUSH PRIVILEGES;
-- also apply revisions create v4

-- =========================================
-- use rsa pub key for prod (IAS)
-- =========================================

-- =========================================
-- 1) USER
-- =========================================
CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    type ENUM('customer', 'vendor', 'both') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================
-- 2) ADDRESS
-- =========================================
CREATE TABLE address (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    street VARCHAR(150) NOT NULL,
    city VARCHAR(100) NOT NULL,
    province_state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20),
    phone VARCHAR(30),
    email VARCHAR(100),
    country VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_address_user
        FOREIGN KEY (user_id) REFERENCES user(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 3) VENDOR
-- subclass of user
-- =========================================
CREATE TABLE vendor (
    vendor_id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30),
    website VARCHAR(255),
    address_id INT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    cover_img VARCHAR(255),
    profile_img VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_vendor_user
        FOREIGN KEY (vendor_id) REFERENCES user(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_vendor_address
        FOREIGN KEY (address_id) REFERENCES address(address_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 4) CUSTOMER
-- subclass of user
-- =========================================
CREATE TABLE customer (
    customer_id INT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(30),
    birthdate DATE,
    gender VARCHAR(20),
    profile_img VARCHAR(255),
    primary_address INT NULL,
    primary_banking_info INT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_customer_user
        FOREIGN KEY (customer_id) REFERENCES user(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 5) VENDOR BANKING INFO
-- =========================================
CREATE TABLE ven_banking_info (
    ven_banking_id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT NOT NULL,
    provider VARCHAR(100) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    token VARCHAR(255),
    account_type VARCHAR(50),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_ven_banking_vendor
        FOREIGN KEY (vendor_id) REFERENCES vendor(vendor_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 6) CUSTOMER BANKING INFO
-- =========================================
CREATE TABLE cus_banking_info (
    banking_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    provider VARCHAR(100),
    account_name VARCHAR(100),
    phone_number VARCHAR(30),
    email VARCHAR(100),
    token VARCHAR(255),
    acc_last4_no CHAR(4),
    account_type VARCHAR(50),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_cus_banking_customer
        FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Add customer optional references after banking/address tables exist
ALTER TABLE customer
    ADD CONSTRAINT fk_customer_primary_address
        FOREIGN KEY (primary_address) REFERENCES address(address_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    ADD CONSTRAINT fk_customer_primary_banking
        FOREIGN KEY (primary_banking_info) REFERENCES cus_banking_info(banking_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE;

-- =========================================
-- 7) PAGE
-- =========================================
CREATE TABLE page (
    page_id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    slug VARCHAR(150) NOT NULL UNIQUE,
    is_public BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_page_vendor
        FOREIGN KEY (vendor_id) REFERENCES vendor(vendor_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 8) CATEGORY
-- =========================================
CREATE TABLE category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    slug VARCHAR(150) NOT NULL UNIQUE,
    parent_id INT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_category_parent
        FOREIGN KEY (parent_id) REFERENCES category(category_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 9) ITEM
-- is_active = record is enabled / not removed
-- is_available = currently sellable
-- =========================================
CREATE TABLE item (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    price DECIMAL(10,2) NOT NULL,
    name VARCHAR(150) NOT NULL,
    vendor_id INT NOT NULL,
    description TEXT,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    stock INT NOT NULL DEFAULT 0,
    sku VARCHAR(100) UNIQUE,
    is_bundle BOOLEAN NOT NULL DEFAULT FALSE,
    unit_type VARCHAR(30),
    unit_value DECIMAL(10,2),
    brand VARCHAR(100),
    barcode VARCHAR(100),
    is_perishable BOOLEAN NOT NULL DEFAULT FALSE,
    is_available BOOLEAN NOT NULL DEFAULT TRUE,
    has_expiry BOOLEAN NOT NULL DEFAULT FALSE,

    CONSTRAINT fk_item_vendor
        FOREIGN KEY (vendor_id) REFERENCES vendor(vendor_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 10) PAGE_ITEM
-- =========================================
CREATE TABLE page_item (
    item_id INT NOT NULL,
    page_id INT NOT NULL,
    display_order INT NOT NULL DEFAULT 1,
    PRIMARY KEY (item_id, page_id),

    CONSTRAINT fk_page_item_item
        FOREIGN KEY (item_id) REFERENCES item(item_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_page_item_page
        FOREIGN KEY (page_id) REFERENCES page(page_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 11) BUNDLE_ITEM
-- bundle_id references item(item_id)
-- =========================================
CREATE TABLE bundle_item (
    item_id INT NOT NULL,
    bundle_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (item_id, bundle_id),

    CONSTRAINT fk_bundle_item_item
        FOREIGN KEY (item_id) REFERENCES item(item_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_bundle_item_bundle
        FOREIGN KEY (bundle_id) REFERENCES item(item_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 12) CATEGORY_ITEM
-- corrected naming from diagram typo
-- =========================================
CREATE TABLE category_item (
    category_item_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    item_id INT NOT NULL,

    CONSTRAINT uq_category_item UNIQUE (category_id, item_id),

    CONSTRAINT fk_category_item_category
        FOREIGN KEY (category_id) REFERENCES category(category_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_category_item_item
        FOREIGN KEY (item_id) REFERENCES item(item_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 13) ITEM_IMAGE
-- =========================================
CREATE TABLE item_image (
    item_image_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_item_image_item
        FOREIGN KEY (item_id) REFERENCES item(item_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 14) STOCK_LOG
-- =========================================
CREATE TABLE stock_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    old_stock INT NOT NULL,
    new_stock INT NOT NULL,
    quantity_changed INT NOT NULL,
    remarks VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_stock_log_item
        FOREIGN KEY (item_id) REFERENCES item(item_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 15) CART
-- =========================================
CREATE TABLE cart (
    customer_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    PRIMARY KEY (customer_id, item_id),

    CONSTRAINT fk_cart_customer
        FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_cart_item
        FOREIGN KEY (item_id) REFERENCES item(item_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 16) ORDER
-- removed `amount` from order to avoid overlap with order_total
-- added updated_at
-- =========================================
CREATE TABLE `order` (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    banking_id INT NULL,
    address_id INT NOT NULL,
    shipping_method VARCHAR(50),
    order_total DECIMAL(10,2) NOT NULL,
    send_date DATETIME NULL,
    receive_date DATETIME NULL,
    order_status VARCHAR(50) NOT NULL DEFAULT 'pending',
    tracking_number VARCHAR(100),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_order_customer
        FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_order_banking
        FOREIGN KEY (banking_id) REFERENCES cus_banking_info(banking_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_order_address
        FOREIGN KEY (address_id) REFERENCES address(address_id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 17) ORDER_ITEM
-- =========================================
CREATE TABLE order_item (
    order_id INT NOT NULL,
    item_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    PRIMARY KEY (order_id, item_id),

    CONSTRAINT fk_order_item_order
        FOREIGN KEY (order_id) REFERENCES `order`(order_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_order_item_item
        FOREIGN KEY (item_id) REFERENCES item(item_id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 18) PAYMENT_TRANSACTION
-- No payment_id added because you said exclude fix #2
-- Added amount as discussed
-- =========================================
CREATE TABLE payment_transaction (
    order_id INT PRIMARY KEY,
    amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    reference_no VARCHAR(100),
    token VARCHAR(255),
    account_type VARCHAR(50),
    acc_last4_no CHAR(4),

    CONSTRAINT fk_payment_transaction_order
        FOREIGN KEY (order_id) REFERENCES `order`(order_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 19) DISCOUNT
-- basic item-level discount only
-- =========================================
CREATE TABLE discount (
    discount_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    date_end DATETIME,
    date_start DATETIME,
    type ENUM('percentage', 'fixed') NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    use_limit INT,
    description TEXT,
    name VARCHAR(100) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_discount_item
        FOREIGN KEY (item_id) REFERENCES item(item_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- INDEXES
-- =========================================
CREATE INDEX idx_address_user_id ON address(user_id);
CREATE INDEX idx_vendor_address_id ON vendor(address_id);
CREATE INDEX idx_ven_banking_vendor_id ON ven_banking_info(vendor_id);
CREATE INDEX idx_cus_banking_customer_id ON cus_banking_info(customer_id);
CREATE INDEX idx_page_vendor_id ON page(vendor_id);
CREATE INDEX idx_category_parent_id ON category(parent_id);
CREATE INDEX idx_item_vendor_id ON item(vendor_id);
CREATE INDEX idx_item_name ON item(name);
CREATE INDEX idx_item_brand ON item(brand);
CREATE INDEX idx_item_is_available ON item(is_available);
CREATE INDEX idx_page_item_page_id ON page_item(page_id);
CREATE INDEX idx_bundle_item_bundle_id ON bundle_item(bundle_id);
CREATE INDEX idx_category_item_category_id ON category_item(category_id);
CREATE INDEX idx_category_item_item_id ON category_item(item_id);
CREATE INDEX idx_item_image_item_id ON item_image(item_id);
CREATE INDEX idx_stock_log_item_id ON stock_log(item_id);
CREATE INDEX idx_cart_item_id ON cart(item_id);
CREATE INDEX idx_order_customer_id ON `order`(customer_id);
CREATE INDEX idx_order_banking_id ON `order`(banking_id);
CREATE INDEX idx_order_address_id ON `order`(address_id);
CREATE INDEX idx_order_status ON `order`(order_status);
CREATE INDEX idx_order_created_at ON `order`(created_at);
CREATE INDEX idx_order_item_item_id ON order_item(item_id);
CREATE INDEX idx_payment_status ON payment_transaction(status);
CREATE INDEX idx_discount_item_id ON discount(item_id);
CREATE INDEX idx_discount_date_start ON discount(date_start);
CREATE INDEX idx_discount_date_end ON discount(date_end);

