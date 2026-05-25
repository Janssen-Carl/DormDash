<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Order;
use App\Models\Address;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $items = new Collection();
        $subtotal = 0.0;
        
        $sourceType = null;
        $sourceData = null;

        if ($request->has('reorder_id') && is_numeric($request->reorder_id)) {
            // Handle Checkout from Reorder
            $sourceType = 'reorder';
            $sourceData = $request->reorder_id;
            
            $order = Order::with(['items.images', 'items.vendor', 'items.discounts' => function ($q) {
                $q->where('is_active', true)
                  ->where('date_start', '<=', now())
                  ->where('date_end', '>=', now());
            }])
                ->where('customer_id', $userId)
                ->findOrFail($request->reorder_id);

            foreach ($order->items as $item) {
                // We use the current item price, not the historical price, for new orders
                $price = $item->discounted_price;
                $quantity = $item->pivot->quantity;
                
                $items->push((object)[
                    'item' => $item,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
                $subtotal += $price * $quantity;
            }
        } elseif ($request->has('buy_item') && is_numeric($request->buy_item) && $request->has('qty') && is_numeric($request->qty)) {
            // Handle Checkout from Buy Now
            $sourceType = 'buy_now';
            $sourceData = ['item_id' => $request->buy_item, 'qty' => $request->qty];
            
            $item = Item::with(['images', 'vendor', 'discounts' => function ($q) {
                $q->where('is_active', true)
                  ->where('date_start', '<=', now())
                  ->where('date_end', '>=', now());
            }])->findOrFail($request->buy_item);
            $price = $item->discounted_price;
            $quantity = $request->qty;
            
            $items->push((object)[
                'item' => $item,
                'quantity' => $quantity,
                'price' => $price,
            ]);
            $subtotal += $price * $quantity;
            
        } elseif ($request->has('selected_items') && is_array($request->selected_items)) {
            // Handle Checkout from Cart Selections
            $selectedItems = array_filter((array) $request->selected_items, 'is_numeric');
            if (empty($selectedItems)) {
                return redirect()->route('cart.index')->withErrors(['msg' => 'No valid items selected.']);
            }
            $sourceType = 'cart';
            $sourceData = $selectedItems;
            
            $cartItems = Cart::with(['item.images', 'item.vendor', 'item.discounts' => function ($q) {
                $q->where('is_active', true)
                  ->where('date_start', '<=', now())
                  ->where('date_end', '>=', now());
            }])
                ->where('customer_id', $userId)
                ->whereIn('item_id', $selectedItems)
                ->get();

            foreach ($cartItems as $cart) {
                $price = $cart->item->discounted_price;
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

        // Fetch user addresses for the view
        $addresses = Address::where('user_id', $userId)->get();

        return view('pages.checkout', compact('items', 'subtotal', 'deliveryFee', 'total', 'addresses', 'sourceType', 'sourceData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,address_id',
            'payment_method' => 'required|in:card,cod',
            'source' => 'required|in:cart,reorder,buy_now',
        ]);

        $userId = auth()->id();
        $items = new Collection();
        $subtotal = 0.0;

        $updatedQuantities = $request->input('quantities', []);

        // Reconstruct items based on source
        if ($request->source === 'reorder') {
            if (!$request->filled('reorder_id') || !is_numeric($request->reorder_id)) {
                return redirect()->route('cart.index')->withErrors(['msg' => 'Invalid reorder reference.']);
            }
            $order = Order::with(['items.discounts' => function ($q) {
                $q->where('is_active', true)
                  ->where('date_start', '<=', now())
                  ->where('date_end', '>=', now());
            }])->where('customer_id', $userId)->findOrFail($request->reorder_id);
            foreach ($order->items as $item) {
                $quantity = isset($updatedQuantities[$item->item_id]) ? (int)$updatedQuantities[$item->item_id] : $item->pivot->quantity;
                if ($quantity > 0) {
                    $items->push((object)[
                        'item_id' => $item->item_id,
                        'quantity' => $quantity,
                        'price' => $item->discounted_price,
                    ]);
                    $subtotal += $item->discounted_price * $quantity;
                }
            }
        } elseif ($request->source === 'buy_now') {
            if (!$request->filled('buy_item') || !is_numeric($request->buy_item) || !$request->filled('qty') || !is_numeric($request->qty)) {
                return redirect()->route('cart.index')->withErrors(['msg' => 'Invalid item reference.']);
            }
            $item = Item::with(['discounts' => function ($q) {
                $q->where('is_active', true)
                  ->where('date_start', '<=', now())
                  ->where('date_end', '>=', now());
            }])->findOrFail($request->buy_item);
            $quantity = isset($updatedQuantities[$item->item_id]) ? (int)$updatedQuantities[$item->item_id] : (int)$request->qty;
            if ($quantity > 0) {
                $items->push((object)[
                    'item_id' => $item->item_id,
                    'quantity' => $quantity,
                    'price' => $item->discounted_price,
                ]);
                $subtotal += $item->discounted_price * $quantity;
            }
        } elseif ($request->source === 'cart') {
            $selectedItems = $request->input('selected_items', []);
            if (!is_array($selectedItems) || empty($selectedItems)) {
                return redirect()->route('cart.index')->withErrors(['msg' => 'No items selected.']);
            }
            $selectedItems = array_filter($selectedItems, 'is_numeric');
            $cartItems = Cart::with(['item.discounts' => function ($q) {
                $q->where('is_active', true)
                  ->where('date_start', '<=', now())
                  ->where('date_end', '>=', now());
            }])
                ->where('customer_id', $userId)
                ->whereIn('item_id', $selectedItems)
                ->get();
                
            foreach ($cartItems as $cart) {
                $quantity = isset($updatedQuantities[$cart->item_id]) ? (int)$updatedQuantities[$cart->item_id] : $cart->quantity;
                if ($quantity > 0) {
                    $items->push((object)[
                        'item_id' => $cart->item_id,
                        'quantity' => $quantity,
                        'price' => $cart->item->discounted_price,
                    ]);
                    $subtotal += $cart->item->discounted_price * $quantity;
                }
            }
        }

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['msg' => 'Order could not be processed.']);
        }

        $deliveryFee = 50.00;
        $total = $subtotal + $deliveryFee;

        DB::transaction(function () use ($userId, $request, $items, $total) {
            // 1. Create Order
            $order = Order::create([
                'customer_id' => $userId,
                'address_id' => $request->address_id,
                'shipping_method' => 'Standard',
                'order_total' => $total,
                'order_status' => 'pending'
            ]);

            // 2. Attach Items
            $syncData = [];
            foreach ($items as $entry) {
                $syncData[$entry->item_id] = [
                    'quantity' => $entry->quantity,
                    'price' => $entry->price
                ];
            }
            $order->items()->attach($syncData);

            // 3. Create Payment Transaction
            // FR-18: record the payment method chosen at checkout
            // FR-19: COD starts as 'pending' (cash collected on delivery); card starts as 'success'
            $paymentMethod = $request->payment_method; // 'cod' or 'card'
            $paymentStatus = ($paymentMethod === 'cod') ? 'pending' : 'success';

            PaymentTransaction::create([
                'order_id'       => $order->order_id,
                'amount'         => $total,
                'status'         => $paymentStatus,
                'payment_method' => $paymentMethod,
                'reference_no'   => 'REF' . strtoupper(uniqid()),
            ]);

            // 4. Cleanup Cart
            if ($request->source === 'cart') {
                $selectedItems = $request->input('selected_items', []);
                if (is_array($selectedItems)) {
                    $selectedItems = array_filter($selectedItems, 'is_numeric');
                    Cart::where('customer_id', $userId)
                        ->whereIn('item_id', $selectedItems)
                        ->delete();
                }
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order successfully placed!');
    }
}
