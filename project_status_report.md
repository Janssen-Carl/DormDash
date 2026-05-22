# Project Functionalities Context Summary

This report maps the provided functional requirements against the current state of the codebase. It highlights what is fully implemented, what is partially implemented, and what is currently missing.

## 3.1 User Account Management

- [x] **FR-01: User Registration** - Implemented (`UserController@register`, `/register`)
- [x] **FR-02: User Login** - Implemented (`UserController@login`, `/login`)
- [x] **FR-03: User Logout** - Implemented (`UserController@logout`, `/logout`)
- [x] **FR-04: Update Profiles** - Implemented (`UserController@update`, `/profile`)

## 3.2 Product Browsing

- [x] **FR-05: Product List** - Implemented (`ProductController@index`, `/products`)
- [x] **FR-06: Product Search** - Implemented (Handled via `q` query parameter in `ProductController`)
- [x] **FR-07: Filter by Category** - Implemented (Handled via `categories` array in `ProductController`)
- [x] **FR-08: Product Details** - Implemented (`ProductController@show`, `/products/{id}`, `pages/product-detail.blade.php`)

## 3.3 Shopping Cart

- [x] **FR-09: Add items to cart** - Implemented (`CartController@store`)
- [x] **FR-10: Update quantity** - Implemented (`CartController@update`)
- [x] **FR-11: Remove items from cart** - Implemented (`CartController@destroy`)
- [x] **FR-12: Compute total price** - Implemented (Handled within the cart and checkout controllers)

## 3.4 Checkout and Orders

- [x] **FR-13: Collect shipping information** - Implemented (Uses `address_id` referencing the user's addresses in `CheckoutController`)
- [x] **FR-14: Confirm orders** - Implemented (`CheckoutController@store` saves the order state)
- [x] **FR-15: Generate order numbers** - Implemented (The system uses `order_id` along with a generated `reference_no` in `PaymentTransaction`)
- [x] **FR-16: Save order records** - Implemented (Stored across `orders`, `order_items`, and `payment_transactions` tables)

## 3.5 Payment

- [x] **FR-17: Cash on Delivery** - Implemented (Checkout form accepts and validates `payment_method` as 'cod')
- [ ] **FR-18: Record payment methods** - Missing (Although `payment_method` is validated in `CheckoutController`, it is **not saved** to the database. Neither the `orders` nor the `payment_transactions` table has a column for the payment method).
- [~] **FR-19: Mark order as paid/unpaid** - Incomplete (The system defaults all new checkouts to a payment status of `success` in `PaymentTransaction::create`. There is no dedicated flow to handle pending payments or update the payment status when a COD order is delivered).

## 3.6 Order Tracking

- [x] **FR-20: View order history** - Implemented (`OrderController@index`, `/orders` view)
- [x] **FR-21: Display order status** - Implemented (Customer tracking available at `/track/{tracking}` and overview)

## 3.7 Admin (Vendor) Management

- [x] **FR-22: Add products** - Implemented (`VendorProductController@store`)
- [x] **FR-23: Edit products** - Implemented (`VendorProductController@update`)
- [x] **FR-24: Delete products** - Implemented (`VendorProductController@destroy`)
- [x] **FR-25: Update order status** - Implemented (Vendors can `confirm`, `ship`, and `deliver` orders via `VendorOrderController`)
- [x] **FR-26: View sales reports** - Implemented (`DashboardController@index`, `/vendor-analytics`)

---

### Key Missing / Incomplete Features Summary:

1. **Payment Method Persistence (FR-18):** The checkout logic checks for the payment method (`cod` or `card`), but forgets to save this choice to the database schema.
2. **Payment Status Workflow (FR-19):** Orders are automatically marked as having a 'success' payment status regardless of whether it's COD. There's no mechanism to mark an unpaid COD order as "Paid".

### Checklist Legend:
- `[x]` = Implemented / Complete
- `[~]` = Partially Implemented / Incomplete
- `[ ]` = Missing / Not Started
