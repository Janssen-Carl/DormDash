import pytest
from playwright.sync_api import expect

def test_crawl_pages_for_php_errors(page_with_errors, base_url):
    """
    This test fulfills the 'Detect PHP warnings/errors' requirement in the laboratory activity.
    It navigates to several key pages without logging in and ensures no PHP warnings or fatal errors are printed.
    """
    page = page_with_errors
    
    # List of public endpoints to test
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
