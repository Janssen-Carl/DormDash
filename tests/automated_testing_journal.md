# Laboratory Activity: Automated Software Testing and Quality Assurance
## Final Project: DormDash Automated Testing Journal

---

## 1. Group & Project Information

* **Project Title:** DormDash (E-commerce Web Application for Dormitories)
* **Group Name:** [Insert Group Name Here]
* **Testing Framework:** Python + Playwright + Pytest

### Team Members & Assigned Contributions
Since the laboratory rubric requires individual contributions, here is a recommended template mapping your team members to the test scripts we built:

| Member Name | Assigned Role/Contribution | Feature Tested | Tool / Framework |
| :--- | :--- | :--- | :--- |
| **Member 1 (Leader)** | Test Suite Architecture & Environment Setup | conftest.py, base configuration | Python + Playwright + Pytest |
| **Member 2** | Security & Authorization Validation | Unauthorized access prevention (403 assertions) | Python + Playwright (test_security.py) |
| **Member 3** | Core E-Commerce Transactions | Cart & checkout flow validation | Python + Playwright (test_checkout_flow.py) |
| **Member 4** | Advanced UI & AI Analytics | Customer & Vendor AI Analytics dashboard stability | Python + Playwright (test_ai_analytics.py) |
| **Member 5** | Quality Assurance & Error Auditing | Automated PHP error and warning scanner crawler | Python + Playwright (test_php_errors.py) |

---

## 2. Test Scenario Documentation

### Scenario 1: Authorization & Security Access Controls (Bad Request Assertions)
* **Functionality Tested:** Vendor Dashboard Access Control
* **Objective of the Test:** Verify that normal customer roles are strictly forbidden (HTTP 403) from accessing the vendor panel `/vendor-home`.
* **Test Script Name:** `test_security.py`
* **Test Data / Input:** 
  * User Email: `juan@example.com` (Customer role)
  * Target URL: `/vendor-home`
* **Steps / Procedure:**
  1. Navigate to the login page (`/login`).
  2. Input customer credentials (`juan@example.com`, `password`) and click Login.
  3. Attempt a navigation to `/vendor-home`.
  4. Call `assert_forbidden_request` to capture and verify the response status code.
* **Expected Result:** The application returns an HTTP status code of `403 Forbidden` or `401 Unauthorized`.
* **Actual Result:** Returned HTTP Status code `403`. 
* **Status:** **PASSED**

---

### Scenario 2: E-Commerce Transaction flow (Checkout & Cart Integration)
* **Functionality Tested:** Product discovery, Cart insertion, and Checkout redirection
* **Objective of the Test:** Verify that a logged-in user can add items to the cart, view the cart, and proceed to the checkout interface with selected item parameters.
* **Test Script Name:** `test_checkout_flow.py`
* **Test Data / Input:**
  * User Email: `juan@example.com` (Customer role)
  * Targets: `/products`, `/cart`, `/checkout`
* **Steps / Procedure:**
  1. Navigate to `/login` and authenticate using customer credentials.
  2. Navigate to the products page (`/products`).
  3. Locates and clicks "Add to Cart" on the first available item.
  4. Navigate to the Cart (`/cart`) and assert that the current page is indeed the cart.
  5. Locate and click the "Checkout" button.
  6. Assert that the URL redirects to `/checkout` with the corresponding item query parameters.
* **Expected Result:** The user is successfully redirected to the checkout screen.
* **Actual Result:** User successfully redirected to `http://localhost:8000/checkout?selected_items[]=...`
* **Status:** **PASSED**

---

### Scenario 3: AI Analytics Dashboard & Chart Rendering
* **Functionality Tested:** AI Business Analytics Interface (Customer & Vendor panels)
* **Objective of the Test:** Verify that the AI analytics portals load stably and visual chart canvases are rendered without breaking or throwing server errors.
* **Test Script Name:** `test_ai_analytics.py`
* **Test Data / Input:**
  * Vendor Email: `snackshack@example.com`
  * Customer Email: `juan@example.com`
* **Steps / Procedure:**
  1. Authenticate as a vendor, navigate to `/vendor-analytics`, and assert the page loaded stably.
  2. Check that the analytics chart `<canvas>` is visible in the DOM.
  3. Authenticate as a customer, navigate to `/analytics`, and assert the page loaded.
* **Expected Result:** Analytics views load stably with standard DOM canvases populated.
* **Actual Result:** Both customer and vendor pages loaded successfully with interactive canvases populated.
* **Status:** **PASSED**

---

### Scenario 4: Automated PHP Warnings & Errors Audit (Crawler)
* **Functionality Tested:** Global Application Code Quality & Execution Stability
* **Objective of the Test:** Automatically crawl the core public entry-points of the web application and scan the HTML document structure for any latent PHP warning, fatal error, or parse exceptions.
* **Test Script Name:** `test_php_errors.py`
* **Test Data / Input:** Core endpoints: `/`, `/products`, `/login`, `/register`, `/cart`
* **Steps / Procedure:**
  1. Navigate sequentially to each target URL.
  2. Retrieve the inner text of the `<body>` tag.
  3. Scan the document text for keywords: `warning:`, `fatal error:`, `parse error:`, `exception:`, `syntax error:`.
* **Expected Result:** No PHP warnings or compilation exceptions are found in the body text of the responses.
* **Actual Result:** Clean HTML outputted. No error keywords matched.
* **Status:** **PASSED**

---

## 3. Reflection / Findings

### A. Issues Encountered & Resolved
1. **Dynamic Checkout Route Parameters:** The initial checkout test asserted an exact URL match (`/checkout`). However, the DormDash platform appends query parameters tracking the chosen cart items (`/checkout?selected_items[]=...`). We resolved this by converting the absolute URL assertion into a flexible regular expression match (`re.compile(rf"^{base_url}/checkout.*")`).
2. **Deterministic Authentication Seeds:** Playwright tests are isolated and run in sandboxed contexts. We utilized Laravel's database seeders to query active, real credentials (`snackshack@example.com` and `juan@example.com`) directly via Tinker in order to make the automation 100% stable without manual registration steps.

### B. Improvements Made After Testing
* **PHP Error Auditor Integration:** By implementing a global custom Pytest fixture (`page_with_errors`) inside `conftest.py`, every single page navigation in any automated test now passively scans the site's rendering for PHP Warnings, ensuring continuous quality assurance.

### C. Lessons Learned
* **Role-Based Isolation:** Automated testing is extremely valuable for validating security rules. Writing `assert_forbidden_request` allowed us to quickly verify that our backend role middleware is correctly restricting customers from crossing boundary contexts.
* **Declarative Declarators:** Playwright's auto-waiting feature greatly reduces "flaky" tests compared to older Selenium drivers, making it the superior modern choice for automated end-to-end web system QA.
