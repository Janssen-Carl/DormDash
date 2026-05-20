import pytest
from playwright.sync_api import sync_playwright

@pytest.fixture(scope="session")
def base_url():
    """Returns the base URL for the local DormDash instance."""
    return "http://localhost:8000"

@pytest.fixture(scope="function")
def page_with_errors(page):
    """
    Returns a page object that will automatically check for PHP errors 
    after tests are run on it, helping us catch errors across the app.
    """
    yield page
    
    # Check for PHP errors in the page source after the test yields back
    body_text = page.locator("body").inner_text().lower()
    errors = ["warning:", "fatal error:", "parse error:", "exception:", "syntax error:"]
    for error in errors:
        assert error not in body_text, f"Found PHP error '{error}' on page {page.url}"
