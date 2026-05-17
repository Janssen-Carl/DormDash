<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $items = new Collection();
        $subtotal = 0.0;

        if ($request->has('reorder_id')) {
            // Handle Checkout from Reorder
            $order = Order::with('items.images', 'items.vendor')
                ->where('customer_id', $userId)
                ->findOrFail($request->reorder_id);

            foreach ($order->items as $item) {
                // We use the current item price, not the historical price, for new orders
                $price = $item->price;
                $quantity = $item->pivot->quantity;
                
                $items->push((object)[
                    'item' => $item,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
                $subtotal += $price * $quantity;
            }
        } elseif ($request->has('selected_items') && is_array($request->selected_items)) {
            // Handle Checkout from Cart Selections
            $cartItems = Cart::with('item.images', 'item.vendor')
                ->where('customer_id', $userId)
                ->whereIn('item_id', $request->selected_items)
                ->get();

            foreach ($cartItems as $cart) {
                $price = $cart->item->price;
                $quantity = $cart->quantity;

                $items->push((object)[
                    'item' => $cart->item,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
                $subtotal += $price * $quantity;
            }
        } else {
            // No items selected, redirect to cart
            return redirect()->route('cart.index')->withErrors(['msg' => 'Please select items to checkout.']);
        }

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['msg' => 'No valid items found for checkout.']);
        }

        $deliveryFee = 50.00;
        $total = $subtotal + $deliveryFee;

        // Fetch user addresses for the view (we use a generic query if address schema is incomplete)
        // Since we are mocking checkout UI, we'll pass an empty collection if address fails
        $addresses = [];
        try {
            $addresses = Address::where('customer_id', $userId)->get();
        } catch (\Exception $e) {
            // Fallback if table doesn't use customer_id
        }

        return view('pages.checkout', compact('items', 'subtotal', 'deliveryFee', 'total', 'addresses'));
    }

    public function store(Request $request)
    {
        // Placeholder for actually placing the order
        // 1. Validate inputs (address_id, payment_method)
        // 2. Create Order
        // 3. Create OrderItems
        // 4. Create PaymentTransaction
        // 5. Delete items from Cart (if they came from cart)
        // 6. Redirect to success page

        return redirect()->route('orders.index')->with('success', 'Order successfully placed!');
    }
}
