# DormDash Automated Test Suite: Test Scenario Documentation

To run this test suite and generate live headed browser test sessions for evidence collection, open a terminal in the `tests/automated` directory and run:
```bash
python -m pytest -v --headed
```

Below is the required **Test Scenario Documentation** for every automated test conducted on the DormDash platform.

---

## Scenario 1: Customer Authorization Security Control
* **Functionality Tested:** Role-Based Access Control and Route Security
* **Objective of the Test:** Verify that normal customer accounts are forbidden (HTTP 403) from accessing the protected vendor namespace dashboard (`/vendor-home`).
* **Steps/Procedure:**
  1. Initialize the Playwright sandbox page.
  2. Navigate to the login page (`/login`).
  3. Fill in the email input field with `juan@example.com`.
  4. Fill in the password input field with `password`.
  5. Click the submit button to execute authentication.
  6. Wait for the page load state to complete successfully.
  7. Attempt to navigate to the restricted route `/vendor-home`.
  8. Capture the HTTP response status code using the custom `assert_forbidden_request` callable.
* **Test Data/Input:**
  * Active URL: `/login` -> `/vendor-home`
  * Credentials: `email="juan@example.com"`, `password="password"` (Seeded customer role)
* **Expected Result:** The server intercepts the request and returns an HTTP status code of `403 Forbidden` (or `401 Unauthorized`), restricting access to the dashboard.
* **Actual Result:** Server successfully returned `HTTP Status 403` and blocked page rendering.
* **Status:** **PASSED**
* **Screenshot or Evidence:**
  ```text
  tests/automated/test_security.py::test_customer_cannot_access_vendor_home[chromium] PASSED
  ```

---

## Scenario 2: Transaction Flow (Cart & Checkout Routing)
* **Functionality Tested:** Product discovery, Cart Insertion, and Checkout Redirection
* **Objective of the Test:** Verify that an authenticated customer can view products, add an item to their shopping cart, inspect their cart, and proceed to the checkout panel with chosen item parameters.
* **Steps/Procedure:**
  1. Navigate to `/login` and authenticate using `juan@example.com` and `password`.
  2. Direct the browser to the customer catalog page (`/products`).
  3. Locate the first "Add to Cart" action button and click it to add the item.
  4. Navigate to the shopping cart page (`/cart`).
  5. Assert that the current URL matches the standard cart endpoint (`/cart`).
  6. Locate the main checkout call-to-action button and click it.
  7. Confirm that the browser redirects the user to the `/checkout` controller.
  8. Assert using a regular expression that the redirection route starts with `/checkout` (handling any appended query variables tracking selected items).
* **Test Data/Input:**
  * Catalog URL: `/products`
  * Cart Selector: `a[href*="/checkout"], button:has-text("Checkout")`
* **Expected Result:** The user is successfully redirected to the checkout interface, showing the chosen items.
* **Actual Result:** The browser redirected smoothly to: `http://localhost:8000/checkout?selected_items%5B%5D=1&...` matching the compiled regular expression.
* **Status:** **PASSED**
* **Screenshot or Evidence:**
  ```text
  tests/automated/test_checkout_flow.py::test_cart_and_checkout_redirect[chromium] PASSED
  ```

---

## Scenario 3: Vendor AI Business Analytics Dashboard
* **Functionality Tested:** Vendor Analytics & Graph Canvas Rendering
* **Objective of the Test:** Verify that the vendor AI analytics portal loads stably and the primary graph canvases are generated successfully inside the view.
* **Steps/Procedure:**
  1. Navigate to `/login` and authenticate using vendor credentials (`snackshack@example.com`, `password`).
  2. Wait for the redirect to complete, then navigate to `/vendor-analytics`.
  3. Assert that the current URL matches the expected `/vendor-analytics` route.
  4. Query the DOM for the primary graphics container element (`canvas`).
  5. Assert that the graph canvas is visible on-screen.
* **Test Data/Input:**
  * Active URL: `/vendor-analytics`
  * Credentials: `email="snackshack@example.com"`, `password="password"` (Seeded vendor role)
* **Expected Result:** The vendor dashboard loads without backend exceptions, and the Chart.js canvas elements are loaded and visible.
* **Actual Result:** The view loaded successfully with standard charts rendered inside a visible canvas element.
* **Status:** **PASSED**
* **Screenshot or Evidence:**
  ```text
  tests/automated/test_ai_analytics.py::test_vendor_ai_analytics_loads[chromium] PASSED
  ```

---

## Scenario 4: Customer Analytics Interface
* **Functionality Tested:** Customer-facing Orders History and Spending Analytics Portal
* **Objective of the Test:** Verify that the customer spending metrics and analytics view loads stably without throwing page errors or database warnings.
* **Steps/Procedure:**
  1. Navigate to `/login` and authenticate using customer credentials (`juan@example.com`, `password`).
  2. Navigate to the spending analytics page (`/analytics`).
  3. Assert that the URL matches `/analytics` and verify page body execution stability.
* **Test Data/Input:**
  * Active URL: `/analytics`
  * Credentials: `email="juan@example.com"`, `password="password"` (Seeded customer role)
* **Expected Result:** The page loads cleanly, retrieving customer order statistics without any server warnings or exceptions.
* **Actual Result:** Navigated to `/analytics` successfully; page rendered with a full metrics breakdown.
* **Status:** **PASSED**
* **Screenshot or Evidence:**
  ```text
  tests/automated/test_ai_analytics.py::test_customer_analytics_loads[chromium] PASSED
  ```

---

## Scenario 5: Global PHP Execution & Compilation Health Audit
* **Functionality Tested:** Automated Warnings and Syntax Errors Scanner (Crawler)
* **Objective of the Test:** Sequentially crawl the core public entry points of the web application and scan the body text to confirm that the PHP backend is not emitting warnings, fatal errors, or parse errors.
* **Steps/Procedure:**
  1. Define a list of core public pages to test (`/`, `/products`, `/login`, `/register`, `/cart`).
  2. Iterate through each page URL and navigate to it unauthenticated.
  3. Retrieve the parsed text string of the HTML body.
  4. Search the text against standard PHP warning identifiers (`warning:`, `fatal error:`, `parse error:`, `exception:`, `syntax error:`).
  5. Assert that none of the warning tokens are contained inside the page's output.
* **Test Data/Input:**
  * Page list: `["/", "/products", "/login", "/register", "/cart"]`
  * Scanner tokens: `["warning:", "fatal error:", "parse error:", "exception:", "syntax error:"]`
* **Expected Result:** No PHP execution warnings, fatal compilation blocks, or database exceptions are found on any of the target pages.
* **Actual Result:** All 5 public endpoints returned clean HTML structures with zero warning tokens found.
* **Status:** **PASSED**
* **Screenshot or Evidence:**
  ```text
  tests/automated/test_php_errors.py::test_crawl_pages_for_php_errors[chromium] PASSED
  ```
