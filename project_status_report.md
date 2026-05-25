# Project Functionalities Status Report

This report maps the functional requirements against the current codebase state.

## 3.1 User Account Management

| # | Requirement | Status | Details |
|---|-------------|--------|---------|
| FR-01 | User Registration | ✅ Done | `UserController@register`, `/register` |
| FR-02 | User Login | ✅ Done | `UserController@login`, `/login` |
| FR-03 | User Logout | ✅ Done | `UserController@logout`, `/logout` |
| FR-04 | Update Profiles | ✅ Done | `UserController@update`, `/profile` |

## 3.2 Product Browsing

| # | Requirement | Status | Details |
|---|-------------|--------|---------|
| FR-05 | Product List | ✅ Done | `ProductController@index`, `/products` |
| FR-06 | Product Search | ✅ Done | `q` query parameter in `ProductController` |
| FR-07 | Filter by Category | ✅ Done | `categories` array in `ProductController` |
| FR-08 | Product Details | ✅ Done | `ProductController@show`, `/products/{id}` |

## 3.3 Shopping Cart

| # | Requirement | Status | Details |
|---|-------------|--------|---------|
| FR-09 | Add items to cart | ✅ Done | `CartController@store` |
| FR-10 | Update quantity | ✅ Done | `CartController@update` |
| FR-11 | Remove items from cart | ✅ Done | `CartController@destroy` |
| FR-12 | Compute total price | ✅ Done | Cart and checkout controllers |

## 3.4 Checkout and Orders

| # | Requirement | Status | Details |
|---|-------------|--------|---------|
| FR-13 | Collect shipping info | ✅ Done | Uses `address_id` in `CheckoutController` |
| FR-14 | Confirm orders | ✅ Done | `CheckoutController@store` |
| FR-15 | Generate order numbers | ✅ Done | `reference_no` in `PaymentTransaction` |
| FR-16 | Save order records | ✅ Done | `orders`, `order_items`, `payment_transactions` tables |

## 3.5 Payment

| # | Requirement | Status | Details |
|---|-------------|--------|---------|
| FR-17 | Cash on Delivery | ✅ Done | Checkout validates `payment_method` as 'cod' |
| FR-18 | Record payment methods | ✅ Done | `payment_method` column added to `payment_transactions`; saved from `CheckoutController` |
| FR-19 | Mark order as paid/unpaid | ✅ Done | COD → `pending` at checkout, `paid` when vendor marks delivered; Card → `success` immediately |

## 3.6 Order Tracking

| # | Requirement | Status | Details |
|---|-------------|--------|---------|
| FR-20 | View order history | ✅ Done | `OrderController@index`, `/orders` |
| FR-21 | Display order status | ✅ Done | `/track/{tracking}` and overview |

## 3.7 Vendor Management

| # | Requirement | Status | Details |
|---|-------------|--------|---------|
| FR-22 | Add products | ✅ Done | `VendorProductController@store` |
| FR-23 | Edit products | ✅ Done | `VendorProductController@update` |
| FR-24 | Delete products | ✅ Done | `VendorProductController@destroy` |
| FR-25 | Update order status | ✅ Done | `VendorOrderController` (confirm, ship, deliver) |
| FR-26 | View sales reports | ✅ Done | `DashboardController@index`, `/vendor-analytics` |

---

## Summary

| Status | Count | Items |
|--------|-------|-------|
| ✅ Done | 24 | All FR-01 to FR-26 |
| ⚠️ Partial | 0 | — |
| ❌ Missing | 0 | — |

### All requirements are now implemented. ✅

---

## 3.8 Future Improvements & Remaining Tasks

The following tasks have been identified for subsequent phases of development:

<!-- - 💳 **Card Payment Processing Improvement**: Implement a functional card payment gateway integration during checkout (currently simulated/non-functional).
- 📧 **Email Integrations**: Set up active email services (SMTP/API) for vendor email verification and secure user password resets.
- 🎨 **Continuous UI/UX Refinement**: Further polish responsive layouts, micro-animations, and overall interface usability across all storefront and dashboard screens. -->

To do:

- fix profile and edit profile on customer and vendor (add eye on the password entry)
- add toasts and success modals accross the system 
(
example: 
 - when adding items to cart (done)
 - when placing order (done)
 - when updating profile
 - when deleting items from cart
 - when updating items in cart
 - when updating order status 
 - when deleting products
 - when adding products 
 - when updating products 
)

- add a way to cancel an order when the order status is still pending
- update the cart and checkout to display if the item is a bundle or has discounts (add info abt it)

- discounts and promotion page.. change the button color from black to green
- " Campaign / Promotion Name " is not used on product cards


- on vendor products page the drop down of " 2 products running low on stock ", add a input section on adding stocks not just fixed 10 25 50 100

- 📧 **Email Integrations**: Set up active email services (SMTP/API) for vendor email verification and secure user password resets. Use PHPMailer 