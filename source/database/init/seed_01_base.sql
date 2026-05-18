-- =============================================
-- SEED 01: Base tables (users, addresses, vendors, customers, categories)
-- Run against: dormdash_v4_migration
-- =============================================

-- ----- USERS (5 vendors + 3 customers) -----
INSERT IGNORE INTO users (username, email, email_verified_at, role, password, created_at, updated_at) VALUES
('snack_shack',    'snackshack@example.com',    NOW(), 'vendor',   '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW()),
('dorm_bites',     'dormbites@example.com',     NOW(), 'vendor',   '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW()),
('campus_pantry',  'campuspantry@example.com',  NOW(), 'vendor',   '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW()),
('quick_mart',     'quickmart@example.com',     NOW(), 'vendor',   '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW()),
('fresh_hub',      'freshhub@example.com',      NOW(), 'vendor',   '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW()),
('juan_cruz',      'juan@example.com',          NOW(), 'customer', '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW()),
('maria_santos',   'maria@example.com',         NOW(), 'customer', '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW()),
('carlo_reyes',    'carlo@example.com',         NOW(), 'customer', '$2y$12$Pu44kh5xjEnwyy.XikmXpOBBZj5aBiNNoSQqUPGTYxIuZH/em6ngK', NOW(), NOW());

-- ----- ADDRESSES -----
-- vendor addresses (user_id 1-5)
INSERT IGNORE INTO addresses (user_id, street, city, province_state, postal_code, phone, email, country, created_at, updated_at) VALUES
(1, '123 Rizal Ave',    'Batangas City', 'Batangas', '4200', '09171110001', 'snackshack@example.com',   'Philippines', NOW(), NOW()),
(2, '456 Mabini St',    'Lipa City',     'Batangas', '4217', '09171110002', 'dormbites@example.com',    'Philippines', NOW(), NOW()),
(3, '789 Laurel Rd',    'Tanauan',       'Batangas', '4232', '09171110003', 'campuspantry@example.com', 'Philippines', NOW(), NOW()),
(4, '12 Agoncillo Blvd','Batangas City', 'Batangas', '4200', '09171110004', 'quickmart@example.com',    'Philippines', NOW(), NOW()),
(5, '34 Diokno St',     'Lipa City',     'Batangas', '4217', '09171110005', 'freshhub@example.com',     'Philippines', NOW(), NOW()),
-- customer addresses (user_id 6-8)
(6, '100 Dorm A Bldg 1',  'Batangas City', 'Batangas', '4200', '09281110006', 'juan@example.com',  'Philippines', NOW(), NOW()),
(7, '200 Dorm B Bldg 2',  'Batangas City', 'Batangas', '4200', '09281110007', 'maria@example.com', 'Philippines', NOW(), NOW()),
(8, '300 Dorm C Bldg 3',  'Lipa City',     'Batangas', '4217', '09281110008', 'carlo@example.com', 'Philippines', NOW(), NOW());

-- ----- VENDORS (vendor_id = user_id) -----
INSERT IGNORE INTO vendors (vendor_id, name, email, phone, website, address_id, active, cover_img, profile_img, created_at, updated_at) VALUES
(1, 'Snack Shack',    'snackshack@example.com',   '09171110001', 'https://snackshack.ph',   1, 1, '/images/vendors/1/cover.jpg', '/images/vendors/1/profile.jpg', NOW(), NOW()),
(2, 'Dorm Bites',     'dormbites@example.com',    '09171110002', 'https://dormbites.ph',    2, 1, '/images/vendors/2/cover.jpg', '/images/vendors/2/profile.jpg', NOW(), NOW()),
(3, 'Campus Pantry',  'campuspantry@example.com', '09171110003', 'https://campuspantry.ph', 3, 1, '/images/vendors/3/cover.jpg', '/images/vendors/3/profile.jpg', NOW(), NOW()),
(4, 'Quick Mart',     'quickmart@example.com',    '09171110004', 'https://quickmart.ph',    4, 1, '/images/vendors/4/cover.jpg', '/images/vendors/4/profile.jpg', NOW(), NOW()),
(5, 'Fresh Hub',      'freshhub@example.com',     '09171110005', 'https://freshhub.ph',     5, 1, '/images/vendors/5/cover.jpg', '/images/vendors/5/profile.jpg', NOW(), NOW());

-- ----- CUSTOMERS (customer_id = user_id) -----
INSERT IGNORE INTO customers (customer_id, first_name, last_name, phone, birthdate, gender, profile_img, primary_address_id, created_at, updated_at) VALUES
(6, 'Juan',  'Cruz',   '09281110006', '2002-03-15', 'Male',   NULL, 6, NOW(), NOW()),
(7, 'Maria', 'Santos', '09281110007', '2001-07-22', 'Female', NULL, 7, NOW(), NOW()),
(8, 'Carlo', 'Reyes',  '09281110008', '2003-01-10', 'Male',   NULL, 8, NOW(), NOW());

-- ----- CATEGORIES -----
-- Top-level categories: use self-referencing parent_id since column is NOT NULL
SET FOREIGN_KEY_CHECKS = 0;
INSERT IGNORE INTO categories (category_id, name, is_active, slug, description, parent_id, created_at, updated_at) VALUES
(1, 'Food & Snacks',    1, 'food-snacks',    'All food and snack items',      1, NOW(), NOW()),
(2, 'Beverages',        1, 'beverages',      'Drinks and beverages',          2, NOW(), NOW()),
(3, 'School Supplies',  1, 'school-supplies','Notebooks, pens, and more',     3, NOW(), NOW()),
(4, 'Personal Care',    1, 'personal-care',  'Hygiene and grooming products', 4, NOW(), NOW()),
(5, 'Dorm Essentials',  1, 'dorm-essentials','Things every dorm room needs',  5, NOW(), NOW());
SET FOREIGN_KEY_CHECKS = 1;
-- Sub-categories
INSERT IGNORE INTO categories (name, is_active, slug, description, parent_id, created_at, updated_at) VALUES
('Instant Noodles',  1, 'instant-noodles','Quick noodle meals',              1, NOW(), NOW()),
('Chips & Crackers', 1, 'chips-crackers', 'Crunchy snacks',                  1, NOW(), NOW()),
('Coffee & Tea',     1, 'coffee-tea',     'Hot beverages',                   2, NOW(), NOW()),
('Soft Drinks',      1, 'soft-drinks',    'Carbonated beverages',            2, NOW(), NOW()),
('Writing Tools',    1, 'writing-tools',  'Pens, pencils, markers',          3, NOW(), NOW());
