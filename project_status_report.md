# Project Functionalities Context Summary

This report maps the provided functional requirements against the current state of the codebase. It highlights what is fully implemented, what is partially implemented, and what is currently missing.

## 3.1 User Account Management
- **FR-01: User Registration** - ✅ Implemented (`UserController@register`, `/register`)
- **FR-02: User Login** - ✅ Implemented (`UserController@login`, `/login`)
- **FR-03: User Logout** - ✅ Implemented (`UserController@logout`, `/logout`)
- **FR-04: Update Profiles** - ✅ Implemented (`UserController@update`, `/profile`)

## 3.2 Product Browsing
- **FR-05: Product List** - ✅ Implemented (`ProductController@index`, `/products`)
- **FR-06: Product Search** - ✅ Implemented (Handled via `q` query parameter in `ProductController`)
- **FR-07: Filter by Category** - ✅ Implemented (Handled via `categories` array in `ProductController`)
- **FR-08: Product Details** - ❌ **Missing** (There is currently no dedicated route or view for a single product page, e.g., `/products/{id}`).

## 3.3 Shopping Cart
- **FR-09: Add items to cart** - ✅ Implemented (`CartController@store`)
- **FR-10: Update quantity** - ✅ Implemented (`CartController@update`)
- **FR-11: Remove items from cart** - ✅ Implemented (`CartController@destroy`)
- **FR-12: Compute total price** - ✅ Implemented (Handled within the cart and checkout controllers)

## 3.4 Checkout and Orders
- **FR-13: Collect shipping information** - ✅ Implemented (Uses `address_id` referencing the user's addresses in `CheckoutController`)
- **FR-14: Confirm orders** - ✅ Implemented (`CheckoutController@store` saves the order state)
- **FR-15: Generate order numbers** - ✅ Implemented (The system uses `order_id` along with a generated `reference_no` in `PaymentTransaction`)
- **FR-16: Save order records** - ✅ Implemented (Stored across `orders`, `order_items`, and `payment_transactions` tables)

## 3.5 Payment
- **FR-17: Cash on Delivery** - ✅ Implemented (Checkout form accepts and validates `payment_method` as 'cod')
- **FR-18: Record payment methods** - ❌ **Missing** (Although `payment_method` is validated in `CheckoutController`, it is **not saved** to the database. Neither the `orders` nor the `payment_transactions` table has a column for the payment method).
- **FR-19: Mark order as paid/unpaid** - ⚠️ **Incomplete** (The system defaults all new checkouts to a payment status of `success` in `PaymentTransaction::create`. There is no dedicated flow to handle pending payments or update the payment status when a COD order is delivered).

## 3.6 Order Tracking
- **FR-20: View order history** - ✅ Implemented (`OrderController@index`, `/orders` view)
- **FR-21: Display order status** - ✅ Implemented (Customer tracking available at `/track/{tracking}` and overview)

## 3.7 Admin (Vendor) Management
- **FR-22: Add products** - ✅ Implemented (`VendorProductController@store`)
- **FR-23: Edit products** - ✅ Implemented (`VendorProductController@update`)
- **FR-24: Delete products** - ✅ Implemented (`VendorProductController@destroy`)
- **FR-25: Update order status** - ✅ Implemented (Vendors can `confirm`, `ship`, and `deliver` orders via `VendorOrderController`)
- **FR-26: View sales reports** - ✅ Implemented (`DashboardController@index`, `/vendor-analytics`)

---

### Key Missing / Incomplete Features Summary:
1. **Product Detail Page (FR-08):** A dedicated view for users to see full details of a specific product is absent.
2. **Payment Method Persistence (FR-18):** The checkout logic checks for the payment method (`cod` or `card`), but forgets to save this choice to the database schema.
3. **Payment Status Workflow (FR-19):** Orders are automatically marked as having a 'success' payment status regardless of whether it's COD. There's no mechanism to mark an unpaid COD order as "Paid".
