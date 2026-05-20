import pytest
from playwright.sync_api import expect

def assert_forbidden_request(action_callable):
    """
    Helper function that asserts the given action (such as page.goto)
    returns a 403 Forbidden or 401 Unauthorized status code, mirroring
    the PHP assertForbiddenRequest structure.
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
    # We wrap the navigation in a lambda callable to pass to assert_forbidden_request
    assert_forbidden_request(
        lambda: page.goto(f"{base_url}/vendor-home")
    )
