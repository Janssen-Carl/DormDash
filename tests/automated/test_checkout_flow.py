import pytest
import re
from playwright.sync_api import expect

def test_cart_and_checkout_redirect(page_with_errors, base_url):
    page = page_with_errors
    # 1. Login as customer
    page.goto(f"{base_url}/login")
    page.fill('input[name="email"]', 'juan@example.com')  # Assuming a customer account exists
    page.fill('input[name="password"]', 'password')
    page.click('button[type="submit"]')
    page.wait_for_load_state("networkidle")
    
    # 2. Visit a product page and add to cart (or just visit cart)
    page.goto(f"{base_url}/products")
    
    # Find any add to cart button and click it if available
    add_to_cart_buttons = page.locator('button:has-text("Add to Cart")')
    if add_to_cart_buttons.count() > 0:
        add_to_cart_buttons.first.click()
        page.wait_for_load_state("networkidle")
        
    # 3. Visit cart
    page.goto(f"{base_url}/cart")
    expect(page).to_have_url(f"{base_url}/cart")
    
    # 4. Proceed to checkout (assuming a checkout button exists)
    checkout_button = page.locator('a[href*="/checkout"], button:has-text("Checkout")')
    if checkout_button.count() > 0:
        checkout_button.first.click()
        page.wait_for_load_state("networkidle")
        expect(page).to_have_url(re.compile(rf"^{base_url}/checkout.*"))

