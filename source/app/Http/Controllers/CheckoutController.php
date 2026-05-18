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

        if ($request->has('reorder_id')) {
            // Handle Checkout from Reorder
            $sourceType = 'reorder';
            $sourceData = $request->reorder_id;
            
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
        } elseif ($request->has('buy_item') && $request->has('qty')) {
            // Handle Checkout from Buy Now
            $sourceType = 'buy_now';
            $sourceData = ['item_id' => $request->buy_item, 'qty' => $request->qty];
            
            $item = Item::with('images', 'vendor')->findOrFail($request->buy_item);
            $price = $item->price;
            $quantity = $request->qty;
            
            $items->push((object)[
                'item' => $item,
                'quantity' => $quantity,
                'price' => $price,
            ]);
            $subtotal += $price * $quantity;
            
        } elseif ($request->has('selected_items') && is_array($request->selected_items)) {
            // Handle Checkout from Cart Selections
            $sourceType = 'cart';
            $sourceData = $request->selected_items;
            
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
            $order = Order::where('customer_id', $userId)->findOrFail($request->reorder_id);
            foreach ($order->items as $item) {
                $quantity = isset($updatedQuantities[$item->item_id]) ? (int)$updatedQuantities[$item->item_id] : $item->pivot->quantity;
                if ($quantity > 0) {
                    $items->push((object)[
                        'item_id' => $item->item_id,
                        'quantity' => $quantity,
                        'price' => $item->price,
                    ]);
                    $subtotal += $item->price * $quantity;
                }
            }
        } elseif ($request->source === 'buy_now') {
            $item = Item::findOrFail($request->buy_item);
            $quantity = isset($updatedQuantities[$item->item_id]) ? (int)$updatedQuantities[$item->item_id] : (int)$request->qty;
            if ($quantity > 0) {
                $items->push((object)[
                    'item_id' => $item->item_id,
                    'quantity' => $quantity,
                    'price' => $item->price,
                ]);
                $subtotal += $item->price * $quantity;
            }
        } elseif ($request->source === 'cart') {
            $cartItems = Cart::with('item')
                ->where('customer_id', $userId)
                ->whereIn('item_id', $request->selected_items)
                ->get();
                
            foreach ($cartItems as $cart) {
                $quantity = isset($updatedQuantities[$cart->item_id]) ? (int)$updatedQuantities[$cart->item_id] : $cart->quantity;
                if ($quantity > 0) {
                    $items->push((object)[
                        'item_id' => $cart->item_id,
                        'quantity' => $quantity,
                        'price' => $cart->item->price,
                    ]);
                    $subtotal += $cart->item->price * $quantity;
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
                'order_status' => 'to_ship'
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
            PaymentTransaction::create([
                'order_id' => $order->order_id,
                'amount' => $total,
                'status' => 'success',
                'reference_no' => 'REF' . strtoupper(uniqid()),
            ]);

            // 4. Cleanup Cart
            if ($request->source === 'cart') {
                Cart::where('customer_id', $userId)
                    ->whereIn('item_id', $request->selected_items)
                    ->delete();
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order successfully placed!');
    }
}
