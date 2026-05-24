# Vendor Product Automated Test Suite Documentation

This document provides a comprehensive breakdown and technical explanation of the automated vendor product test suite built using **Laravel Feature Testing (PHPUnit)**.

---

# 1. Vendor Product Creation Test
## Function: `test_vendor_can_create_a_product()`

This test validates the product creation workflow for authenticated vendor accounts. It ensures that vendors can successfully submit product information through the application and that the product data is correctly persisted within the database.

### Functionality Tested
- Vendor authentication
- Product creation
- Database insertion
- Product image record creation
- Success response handling

### Objective of the Test
Verify that an authenticated vendor can create a new product and that all associated product information, including image records, is properly stored in the database.

### Steps/Procedure
1. Create a vendor account using the test helper method.
2. Authenticate the vendor using `actingAs()`.
3. Submit a POST request to the product creation route.
4. Provide complete product details including:
    - Product name
    - Description
    - Price
    - Stock
    - SKU
    - Brand
    - Barcode
    - Unit information
5. Verify that the application redirects to the vendor products page.
6. Confirm that a success session message is returned.
7. Assert that the product exists in the `items` table.
8. Assert that the related image record exists in the `item_images` table.

### Test Data/Input
```php
[
    'name' => 'Iced Coffee',
    'description' => 'Ready to drink coffee.',
    'price' => 89.50,
    'stock' => 25,
    'sku' => 'COFFEE-001',
    'brand' => 'DormDash',
]
```

### Key Components
- **`actingAs($vendorUser)`**
  Simulates an authenticated vendor session.

- **`assertDatabaseHas()`**
  Validates that records were successfully inserted into the database.

- **`assertRedirect()`**
  Verifies correct application routing after successful creation.

---

# 2. Vendor Product Update Test
## Function: `test_vendor_can_update_their_product()`

This test validates that vendors can successfully modify existing product information.

### Functionality Tested
- Product updating
- Inventory modification
- Pricing updates
- Database synchronization

### Objective of the Test
Verify that vendors can edit product details and that all updates are accurately reflected in the database.

### Steps/Procedure
1. Create a vendor account.
2. Create a sample product assigned to the vendor.
3. Authenticate the vendor session.
4. Submit updated product information using a POST request.
5. Verify redirect behavior after update.
6. Confirm that a success session message is displayed.
7. Assert that updated values exist in the database.

### Test Data/Input
```php
[
    'unit_type' => 'pack',
    'unit_value' => 2,
    'stock' => 40,
    'price' => 45.75,
]
```

### Key Components
- **Database Assertions**
  Confirms that modified product values persist correctly.

- **Authenticated Vendor Session**
  Ensures only the owner vendor can update the product.

---

# 3. Vendor Product Restocking Test
## Function: `test_vendor_can_restock_their_product_and_reactivate_availability()`

This test validates inventory replenishment and automatic availability reactivation.

### Functionality Tested
- Product restocking
- Inventory recalculation
- Product availability activation
- Redirect parameter preservation

### Objective of the Test
Verify that vendors can add inventory stock and that unavailable products automatically become available again once replenished.

### Steps/Procedure
1. Create a vendor account.
2. Create a product with low stock and unavailable status.
3. Authenticate the vendor session.
4. Submit a restocking request with additional stock quantity.
5. Verify redirect response with preserved query parameters.
6. Confirm success session notification.
7. Assert updated stock quantity.
8. Verify that availability status becomes active.

### Test Data/Input
```php
[
    'quantity' => 12,
    'search' => 'snack',
    'status' => 'inactive',
    'sort' => 'stock',
    'dir' => 'asc',
]
```

### Key Components
- **Stock Increment Validation**
  Ensures inventory is mathematically updated correctly.

- **Availability Reactivation**
  Automatically changes unavailable products to available when stock exists.

---

# 4. Vendor Product Soft Delete Test
## Function: `test_vendor_can_soft_delete_their_product()`

This test validates the product soft deletion mechanism.

### Functionality Tested
- Product deactivation
- Soft deletion workflow
- Availability disabling

### Objective of the Test
Verify that products are marked inactive instead of being permanently removed from the database.

### Steps/Procedure
1. Create a vendor account.
2. Create an active product.
3. Authenticate the vendor session.
4. Submit a DELETE request for the product.
5. Verify redirect response.
6. Confirm success notification.
7. Assert that the product remains in the database with inactive status fields.

### Test Data/Input
```php
[
    'is_active' => 1,
    'is_available' => 1,
]
```

### Key Components
- **Soft Delete Pattern**
  Preserves historical product data while disabling visibility.

- **Database Status Validation**
  Confirms product flags are updated correctly.

---

# 5. Vendor Product Search & Filtering Test
## Function: `test_vendor_products_index_filters_searches_and_limits_to_authenticated_vendor()`

This test validates vendor product filtering, searching, and ownership isolation.

### Functionality Tested
- Product searching
- Product filtering
- Vendor ownership isolation
- Authenticated query restrictions

### Objective of the Test
Verify that vendors only see their own products and that filtering/search functionality operates correctly.

### Steps/Procedure
1. Create two vendor accounts.
2. Create multiple products with different names and statuses.
3. Authenticate as one vendor.
4. Submit search and filter parameters.
5. Invoke the controller index method directly.
6. Verify returned product collection.
7. Assert that only matching products belonging to the authenticated vendor are visible.

### Test Data/Input
```php
[
    'search' => 'Matcha',
    'status' => 'active',
]
```

### Key Components
- **Ownership Isolation**
  Prevents vendors from viewing products owned by others.

- **Controller-Level Testing**
  Directly invokes controller methods for isolated logic validation.

---

# 6. Vendor Authorization Protection Test
## Function: `test_vendor_cannot_manage_another_vendors_product()`

This test validates authorization security boundaries between vendors.

### Functionality Tested
- Access restriction
- Authorization middleware
- Ownership validation
- Forbidden request handling

### Objective of the Test
Verify that vendors cannot modify, restock, or delete products belonging to other vendors.

### Steps/Procedure
1. Create two separate vendor accounts.
2. Create a product assigned to the second vendor.
3. Authenticate using the first vendor account.
4. Attempt unauthorized product update.
5. Attempt unauthorized restocking.
6. Attempt unauthorized deletion.
7. Verify all actions return `403 Forbidden`.

### Test Data/Input
```php
[
    'stock' => 99,
    'price' => 99,
    'quantity' => 5,
]
```

### Key Components
- **`assertForbiddenRequest()`**
  Reusable helper validating authorization failures.

- **403 Status Validation**
  Ensures security restrictions are correctly enforced.

---

# 7. Customer Access Restriction Test
## Function: `test_customer_cannot_access_vendor_product_routes()`

This test validates role-based access restrictions for customer accounts.

### Functionality Tested
- Role-based access control
- Vendor route protection
- Customer access restriction

### Objective of the Test
Verify that customer accounts cannot access vendor-only product management routes.

### Steps/Procedure
1. Create a customer account.
2. Authenticate the customer session.
3. Attempt to access vendor product routes.
4. Verify that the request is denied.
5. Confirm that the application returns a `403 Forbidden` response.

### Test Data/Input
```php
[
    'role' => 'customer',
]
```

### Key Components
- **Role Validation**
  Ensures vendor-only functionality is protected from customers.

- **Route Authorization**
  Verifies middleware and permission enforcement.

---

# Summary

The Vendor Product Automated Test Suite validates the complete vendor product management lifecycle, including:

- Product creation
- Product updates
- Inventory restocking
- Soft deletion
- Product filtering/search
- Vendor ownership validation
- Customer access restriction

The suite ensures that the application maintains:
- Data integrity
- Role-based security
- Vendor isolation
- Correct inventory management behavior
- Proper route authorization

By using Laravel Feature Testing with isolated in-memory database schemas, the test suite provides fast, reliable, and repeatable validation of the vendor product subsystem.
