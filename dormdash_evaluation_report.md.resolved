# DormDash — E-Commerce Project Evaluation Report

This report evaluates the **DormDash** e-commerce platform against the criteria defined in the **E-Commerce Project Evaluation Rubric**. The assessment is based on a detailed audit of the codebase, covering database schemas, Laravel controllers, Eloquent models, and Alpine.js-powered views.

---

## 📊 Score Card

| Category | Rubric Criteria | Score | Code Evidence & Rationale |
|:---|:---|:---:|:---|
| **Core 1** | **User Account Management** | **4 / 5** | Strong role-based access, validation, and multi-profile sync, but lacks active password recovery routes and email verification. |
| **Core 2** | **Product Browsing** | **4 / 5** | Rich Alpine.js-powered sidebar, compound category-vendor search, and carousels, but lacks price/alphabetical sorting options. |
| **Core 3** | **Shopping Cart** | **5 / 5** | Fully persistent DB cart, vendor grouping, Alpine.js dynamic pricing, and seamless quantity decrement-to-delete handling. |
| **Core 4** | **Checkout & Order Processing** | **5 / 5** | Rich multi-source (cart, reorder, buy-now) flow, database transactions, invoice summary with date/time, delivery addresses, payment types, tracking numbers, subtotal/total calculations. |
| **Core 5** | **Payment Management** | **4 / 5** | Secure tokenized card profile management (acc_last4_no and mock token), but card processing gateway is simulated. |
| **Bonus 1** | **Order Tracking** | **4 / 5** | Complete multi-stage order tracking (Pending ➔ To Ship ➔ Shipped ➔ Delivered ➔ Completed) with tracking numbers; track page is a placeholder. |
| **Bonus 2** | **Admin/Vendor Management** | **5 / 5** | Outstanding platform administration, user approvals, admin logs with CSV export, sales insights, and active Python Flask AI forecasting. |
| **Bonus 3** | **Promos / Vouchers** | **5 / 5** | Dynamic Eloquent-level time and limit-checked promo system handling percentage and fixed rates automatically during retrieval. |

### 📈 Final Summary Score
* **Core Metrics:** `22 / 25` (88.0%)
* **Bonus Points:** `13 / 15` (86.7%)
* **Combined Platform Rating:** **Excellent**

---

## 🔍 Criteria-by-Criteria Analysis

### 1. User Account Management
* **Score:** `4 / 5` (Very Good)
* **Code Evidence:**
  * **Role-Based Access Control:** Configured in `routes/web.php` through middlewares (`auth`, `role:customer`, `role:vendor`, `role:admin`) mapping to specific user roles in the `users` table.
  * **Validation & Security:** `UserController@register` implements robust validation (`unique:users`, `confirmed`, `min:8` password length) and secures passwords using bcrypt hashing (`Hash::make`).
  * **Profile Customization:** Support for profile image uploads (`UserController@updatePhoto`), phone updates, default/alternative shipping address management (`UserController@addAddress`, `UserController@deleteAddress`), and payment card management.
  * **Synchronization:** Database transactions inside `UserController@register` sync user creations with the corresponding `vendors` or `customers` profiles. New vendors are registered as inactive (`active = false`) and must be approved by the admin.
* **Why not a 5?** Under the rubric, a level 5 requires **password recovery**. Currently, there are no active password reset/recovery routes, and email verification is noted in the future improvements list as pending active SMTP configurations.

---

### 2. Product Browsing
* **Score:** `4 / 5` (Very Good)
* **Code Evidence:**
  * **Advanced Searching:** `ProductController@index` performs compound queries against product name, description, brand, SKU, barcode, vendor name, and category descriptions.
  * **Alpine.js Dynamic Filters:** In `resources/views/pages/products.blade.php`, Alpine.js drives the vendor and category sidebar filters.
  * **Specialty Toggles:** Direct support for filtering standard categories vs Featured Bundles (`is_bundle`) and Discounted Items (`has_discount`).
  * **UI/UX & Carousels:** Products are presented grouped by parent categories in smooth horizontal scroll carousels (`productCarousel()` using Alpine.js and Tailwind). Product detail pages (`ProductController@show`) pull images, active discounts, categories, and dynamically calculate related products based on shared category IDs and vendor IDs.
* **Why not a 5?** Level 5 requires **sorting**. The index page currently lacks sorting drop-downs (e.g., sorting by price low-to-high, high-to-low, popularity, or alphabetical order).

---

### 3. Shopping Cart
* **Score:** `5 / 5` (Excellent)
* **Code Evidence:**
  * **Data Persistence:** Cart items are stored directly in the database (`carts` table) via the `Cart` model, satisfying the persistence requirement.
  * **Logic:** `CartController@store` increments the quantity if an item is already present in the customer's cart, preventing duplicate entries.
  * **Dynamic Increments/Decrements:** `CartController@update` handles both increments and decrements, automatically purging/deleting the item if its quantity drops to zero.
  * **Dynamic Calculations:** `CartController@index` calculates the subtotal on the fly based on active promotional pricing. It builds a structured JSON dataset (`cartData`) for real-time price calculations in the browser.
  * **UI Polish:** Instant feedback via success flash messages ("Added to cart successfully", "Item removed from cart").

---

### 4. Checkout & Order Processing
* **Score:** `5 / 5` (Excellent)
* **Code Evidence:**
  * **Multi-Source Checkout:** `CheckoutController@index` handles checkouts from the persistent shopping cart (`selected_items` arrays), direct "Buy Now" triggers, and historic completed orders ("Reorder").
  * **Transactional Safety:** `CheckoutController@store` executes within a `DB::transaction` block to guarantee database integrity.
  * **Order Generation & Invoicing:** Creates an `Order` entry, attaches items with checkout-time prices to `order_items`, registers a `PaymentTransaction` with a physical reference (`'REF' . strtoupper(uniqid())`), and clears the corresponding cart items.
  * **Invoice View:** The customer orders page (`resources/views/pages/orders.blade.php`) features an expand/collapse card details view that serves as an interactive invoice, displaying:
    * Detailed product line-items (image, name, quantity, unit price).
    * Subtotal, shipping fees (flat ₱50.00), and grand totals.
    * Order timestamps, payment methods, payment status, and tracking numbers.

---

### 5. Payment Management
* **Score:** `4 / 5` (Very Good)
* **Code Evidence:**
  * **Multiple Options:** Supports Cash on Delivery (`cod`) and Credit/Debit card options (`card`).
  * **PCI-Compliance Design (Tokenization):** `UserController@addPayment` sanitizes card numbers, extracts and stores only the last 4 digits (`acc_last4_no`), and generates a secure mock token (`TOK_` + unique ID) to simulate secure payment tokenization.
  * **State Flow:** COD starts as `pending` and transitions to `paid` when the vendor marks the order as delivered (`VendorOrderController@deliver`). Card payments are marked as `success` immediately.
* **Why not a 5?** While the card details are stored securely using tokenization principles, there is no real-world payment gateway (e.g., Stripe, PayPal) integrated; card processing is mock/simulated.

---

### 6. Order Tracking (Bonus Point Category)
* **Score:** `4 / 5` (Very Good)
* **Code Evidence:**
  * **Multi-State Lifecycle:** Driven actively by vendor actions.
    * **Confirming:** Pending ➔ To Ship (`VendorOrderController@confirm`). Stock is automatically decremented from inventory (including child products if a bundle is purchased).
    * **Shipping:** To Ship ➔ Shipped (`VendorOrderController@ship`). Generates physical tracking numbers (`'TRK-' . date('Ymd') . '-' . str_pad($orderId, 3, '0', STR_PAD_LEFT)`).
    * **Delivering:** Shipped ➔ Delivered (`VendorOrderController@deliver`). Sets `receive_date` and marks COD payments as paid.
    * **Completing:** Customer confirms delivery ➔ Completed (`OrderController@complete`), which unlocks the Reorder button.
* **Why not a 5?** While the database state machine and tracking logs are functional, the live tracking page (`pages/track.blade.php`) is currently a developmental placeholder.

---

### 7. Admin / Vendor Management (Bonus Point Category)
* **Score:** `5 / 5` (Excellent)
* **Code Evidence:**
  * **Platform Administration:** `AdminController` manages users, approves/activates new vendors, and deletes accounts with transactional cascades.
  * **Audit Trails:** Logs administrative actions into the `AdminLog` model with search, filtering, and a direct CSV data exporter (`exportLogs`).
  * **Platform Insights:** Dynamic insights dashboard featuring sales metrics (Revenue, Orders, Units Sold, AOV), trend charts, and structured CSV exports.
  * **AI Forecasting:** Communicates with a Python Flask AI forecasting microservice over local network networks, fetching 90-day global revenue predictions, future growth percentages, and status alerts.
  * **Vendor Capabilities:** Complete vendor portals for inventory CRUD, stocking alerts, bundle builders, promotions managers, and state-machine order dispatching.

---

## 🌟 Highlighted Engineering Strengths

1. **Eloquent Active Promotion Bindings:** Computing discounted prices directly at the Eloquent model level (`getDiscountedPriceAttribute`) guarantees pricing integrity across the storefront, carousels, shopping cart, and checkouts.
2. **Transactional Inventory Deductions for Bundles:** When a vendor confirms an order, `VendorOrderController@confirm` recursively deducts inventory from both standard items and child components of custom-built bundles, ensuring stock calculations remain accurate.
3. **Card Detail Tokenization:** Storing only `acc_last4_no` and assigning a secure mock token `token => TOK_...` exhibits excellent database design and security awareness.
4. **Flask AI Forecasting Microservice Integration:** Interfacing with a separate Python Flask microservice to stream platform-wide revenue forecasts onto the administrator's dashboard shows a strong microservice-oriented design.

---

## 🛠️ Areas for Future Improvement

* **SMTP/API Email Integration:** Standardize email configurations to support verification codes for vendor registration and password recovery triggers.
* **Product Sorting:** Introduce drop-down menus on the storefront index to allow users to sort products by price (ascending/descending), popularity (sold count), and alphabetically.
* **Live GPS Tracking Page:** Replace the tracking page placeholder with an active status timeline or a leaflet-based map interface displaying the package's transit steps.
* **Payment Gateway Integration:** Upgrade simulated transactions with a sandboxed Stripe, PayPal, or GCash API integration.
