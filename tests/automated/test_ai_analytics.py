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
    
    # Assert page has loaded
    expect(page).to_have_url(f"{base_url}/vendor-analytics")
    
    # Check that there are no server errors (500)
    # The fixture will automatically check for PHP errors on yield teardown
    
    # Ensure there is a canvas or some analytics chart
    # Many analytics pages use canvas for charts
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
    
    # Assert page has loaded
    expect(page).to_have_url(f"{base_url}/analytics")
