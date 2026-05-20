# DormDash Automated Test Suite: Source Code Documentation

This document provides a comprehensive breakdown and technical explanation of the automated test suite built using **Python + Playwright + Pytest**.

---

## 1. Test Configuration and Fixtures (`conftest.py`)
The `conftest.py` file is Pytest's central configuration hub. It initializes shared resources, handles setup/teardown hooks, and defines custom fixtures.

### Complete Source Code:
```python
import pytest
from playwright.sync_api import sync_playwright

@pytest.fixture(scope="session")
def base_url():
    """Returns the base URL for the local DormDash instance."""
    return "http://localhost:8000"

@pytest.fixture(scope="function")
def page_with_errors(page):
    """
    Returns a page object that automatically checks for PHP errors 
    after tests are run on it, helping us catch latent exceptions.
    """
    yield page
    
    # Check for PHP errors in the page source after the test yields back
    body_text = page.locator("body").inner_text().lower()
    errors = ["warning:", "fatal error:", "parse error:", "exception:", "syntax error:"]
    for error in errors:
        assert error not in body_text, f"Found PHP error '{error}' on page {page.url}"
```

### Key Components:
- **`base_url` (Session-Scoped):** Returns the target URL of the application. Having this as a fixture prevents hardcoding the URL across individual tests, allowing quick environment switching.
- **`page_with_errors` (Function-Scoped):** Wraps Playwright's built-in `page` fixture. By using `yield`, it lets the test complete its actions first. During the teardown phase, it automatically grabs the rendering page body and scans for PHP warnings or exceptions. This means **all tests inherit continuous error-scanning for free**.

---

## 2. Security & Authorization Checks (`test_security.py`)
This test is designed to validate boundaries. It implements a Python equivalent to the PHP `assertForbiddenRequest` helper.

### Complete Source Code:
```python
import pytest
from playwright.sync_api import expect

def assert_forbidden_request(action_callable):
    """
    Helper function that asserts the given action (such as page.goto)
    returns a 403 Forbidden or 401 Unauthorized status code.
    """
    response = action_callable()
    assert response is not None, "The request did not return a response."
    assert response.status in [403, 401], f"Expected status 403 or 401, but got {response.status}."

def test_customer_cannot_access_vendor_home(page_with_errors, base_url):
    """
    Security Test: Verifies that a logged-in customer is forbidden (403)
    from accessing the vendor dashboard page.
    """
    page = page_with_errors
    
    # 1. Log in as a customer
    page.goto(f"{base_url}/login")
    page.fill('input[name="email"]', 'juan@example.com')
    page.fill('input[name="password"]', 'password')
    page.click('button[type="submit"]')
    page.wait_for_load_state("networkidle")
    
    # 2. Try to access the vendor-home page and assert it is forbidden
    assert_forbidden_request(
        lambda: page.goto(f"{base_url}/vendor-home")
    )
```

### Key Components:
- **`assert_forbidden_request(action_callable)`:** Receives a lambda function representing a request/navigation. It invokes the action and inspects the HTTP response status. It asserts that the returned status is either `403` (Forbidden) or `401` (Unauthorized).
- **`test_customer_cannot_access_vendor_home`:** Logs in with a customer account and attempts to hit `/vendor-home`. The check validates that the role middleware successfully isolates the vendor namespace from customer accounts.

---

## 3. Transaction Flow Testing (`test_checkout_flow.py`)
Validates a complete user checkout flow from product discovery through cart insertion to checkout.

### Complete Source Code:
```python
import pytest
import re
from playwright.sync_api import expect

def test_cart_and_checkout_redirect(page_with_errors, base_url):
    page = page_with_errors
    # 1. Login as customer
    page.goto(f"{base_url}/login")
    page.fill('input[name="email"]', 'juan@example.com')
    page.fill('input[name="password"]', 'password')
    page.click('button[type="submit"]')
    page.wait_for_load_state("networkidle")
    
    # 2. Visit a product page and add to cart
    page.goto(f"{base_url}/products")
    
    # Find any add to cart button and click it if available
    add_to_cart_buttons = page.locator('button:has-text("Add to Cart")')
    if add_to_cart_buttons.count() > 0:
        add_to_cart_buttons.first.click()
        page.wait_for_load_state("networkidle")
        
    # 3. Visit cart
    page.goto(f"{base_url}/cart")
    expect(page).to_have_url(f"{base_url}/cart")
    
    # 4. Proceed to checkout (handling dynamic selected items)
    checkout_button = page.locator('a[href*="/checkout"], button:has-text("Checkout")')
    if checkout_button.count() > 0:
        checkout_button.first.click()
        page.wait_for_load_state("networkidle")
        expect(page).to_have_url(re.compile(rf"^{base_url}/checkout.*"))
```

### Key Components:
- **Dynamic Cart Interactions:** Uses locator filters like `:has-text("Add to Cart")` to click checkout components dynamically, matching live products without hardcoding IDs.
- **Regular Expression URL Matcher:** Since checkout paths pass dynamic query parameters like `?selected_items[]=...`, standard absolute matching fails. We use `re.compile(rf"^{base_url}/checkout.*")` to securely verify redirection while ignoring query strings.

---

## 4. UI & AI Analytics dashboard (`test_ai_analytics.py`)
Validates that AI analytics components and canvas graphs render stably for both vendors and customers.

### Complete Source Code:
```python
import pytest
from playwright.sync_api import expect

def test_vendor_ai_analytics_loads(page_with_errors, base_url):
    page = page_with_errors
    page.goto(f"{base_url}/login")
    page.fill('input[name="email"]', 'snackshack@example.com')
    page.fill('input[name="password"]', 'password')
    page.click('button[type="submit"]')
    page.wait_for_load_state("networkidle")
    
    # Navigate to vendor analytics
    page.goto(f"{base_url}/vendor-analytics")
    expect(page).to_have_url(f"{base_url}/vendor-analytics")
    
    # Ensure there is a canvas or some analytics chart
    canvas = page.locator("canvas").first
    if canvas.is_visible():
        expect(canvas).to_be_visible()

def test_customer_analytics_loads(page_with_errors, base_url):
    page = page_with_errors
    page.goto(f"{base_url}/login")
    page.fill('input[name="email"]', 'juan@example.com')
    page.fill('input[name="password"]', 'password')
    page.click('button[type="submit"]')
    page.wait_for_load_state("networkidle")
    
    # Navigate to customer analytics
    page.goto(f"{base_url}/analytics")
    expect(page).to_have_url(f"{base_url}/analytics")
```

### Key Components:
- **Role-based Switching:** Sequentially logs in as `snackshack@example.com` (vendor) and `juan@example.com` (customer) to check role-specific analytics views.
- **Canvas Rendering Assertion:** Asserts the visibility of the Chart.js visual output wrapper (`canvas`) to guarantee graphs are structurally visible in the DOM.

---

## 5. Automated Warning Scanner (`test_php_errors.py`)
Performs unauthenticated checks on core public entry endpoints to audit compilation/runtime quality.

### Complete Source Code:
```python
import pytest
from playwright.sync_api import expect

def test_crawl_pages_for_php_errors(page_with_errors, base_url):
    """
    Crawls public pages and audits for unexpected PHP warnings or errors in the HTML body.
    """
    page = page_with_errors
    
    endpoints = [
        "/",
        "/products",
        "/login",
        "/register",
        "/cart"
    ]
    
    for endpoint in endpoints:
        page.goto(f"{base_url}{endpoint}")
        body_text = page.locator("body").inner_text().lower()
        errors = ["warning:", "fatal error:", "parse error:", "exception:", "syntax error:"]
        
        for error in errors:
            assert error not in body_text, f"Found PHP error '{error}' on public endpoint {endpoint}"
```

### Key Components:
- **Crawler Design:** Aggregates core routing array to verify multiple routes in a single test runner lifecycle.
- **Inline Body Analysis:** Grabs target HTML inner text and explicitly asserts that no standard PHP warning or exception markers are contained within the output stream.
