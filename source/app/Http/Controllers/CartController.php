<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        $cartItems = Cart::where('customer_id', $userId)
            ->with(['item.images', 'item.vendor', 'item.discounts' => function ($q) {
                $q->usable();
            }])
            ->latest()
            ->get();
            
        // Group items by vendor
        $groupedCartItems = $cartItems->groupBy(function ($cart) {
            return $cart->item->vendor_id ?? 'unknown';
        });

        // Create JSON data for Alpine.js dynamic pricing
        $cartData = $cartItems->map(function ($cart) {
            return [
                'item_id' => $cart->item_id,
                'vendor_id' => $cart->item->vendor_id ?? 'unknown',
                'price' => (float)$cart->item->discounted_price,
                'original_price' => (float)$cart->item->price,
                'quantity' => $cart->quantity
            ];
        });

        // Calculate initial subtotal assuming all items are selected by default
        $subtotal = $cartItems->sum(function($cart) {
            return $cart->item->discounted_price * $cart->quantity;
        });

        $deliveryFee = $cartItems->count() > 0 ? 50.00 : 0.00;
        $total = $subtotal + $deliveryFee;

        return view('pages.cart', compact('groupedCartItems', 'cartData', 'subtotal', 'deliveryFee', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,item_id',
            'quantity' => 'required|integer|min:1'
        ]);

        $addresses = \App\Models\Address::where('user_id', auth()->id())->count();
        if ($addresses === 0) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Please add a delivery address before adding items to your cart.'], 422);
            }
            return redirect('/profile/edit')->withErrors(['msg' => 'Please add a delivery address before adding items to your cart.']);
        }

        $item = \App\Models\Item::findOrFail($request->item_id);

        $userId = auth()->id();
        
        $cart = Cart::where('customer_id', $userId)
            ->where('item_id', $request->item_id)
            ->first();

        $effectiveStock = $item->effective_stock;
        $newQty = $request->quantity + ($cart ? $cart->quantity : 0);
        if ($newQty > $effectiveStock) {
            if ($request->ajax()) {
                return response()->json(['error' => "Only {$effectiveStock} unit(s) of '{$item->name}' are available."], 422);
            }
            return redirect()->back()->withErrors(['msg' => "Only {$effectiveStock} unit(s) of '{$item->name}' are available."]);
        }

        if ($cart) {
            Cart::where('customer_id', $userId)
                ->where('item_id', $request->item_id)
                ->update([
                    'quantity' => $newQty,
                    'updated_at' => now()
                ]);
        } else {
            Cart::create([
                'customer_id' => $userId,
                'item_id' => $request->item_id,
                'quantity' => $request->quantity
            ]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => 'Added to cart successfully.']);
        }

        return redirect()->back()->with('success', 'Added to cart successfully.');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'action' => 'required|in:increment,decrement,set'
        ]);

        $userId = auth()->id();
        
        $cart = Cart::where('customer_id', $userId)
            ->where('item_id', $itemId)
            ->firstOrFail();

        $item = \App\Models\Item::findOrFail($itemId);

        $effectiveStock = $item->effective_stock;

        if ($request->action === 'set') {
            $qty = (int) $request->input('quantity', 1);
            $qty = max(1, min($qty, $effectiveStock));
            Cart::where('customer_id', $userId)
                ->where('item_id', $itemId)
                ->update([
                    'quantity' => $qty,
                    'updated_at' => now()
                ]);
            return redirect()->back()->with('success', 'Cart updated.');
        } elseif ($request->action === 'increment') {
            if ($cart->quantity + 1 > $effectiveStock) {
                return redirect()->back()->withErrors(['msg' => "Only {$effectiveStock} unit(s) of '{$item->name}' are available."]);
            }
            Cart::where('customer_id', $userId)
                ->where('item_id', $itemId)
                ->update([
                    'quantity' => DB::raw('quantity + 1'),
                    'updated_at' => now()
                ]);
            return redirect()->back();
        } elseif ($request->action === 'decrement') {
            if ($cart->quantity > 1) {
                Cart::where('customer_id', $userId)
                    ->where('item_id', $itemId)
                    ->update([
                        'quantity' => DB::raw('quantity - 1'),
                        'updated_at' => now()
                    ]);
                return redirect()->back();
            } else {
                Cart::where('customer_id', $userId)
                    ->where('item_id', $itemId)
                    ->delete();
                return redirect()->back()->with('success', 'Item removed from cart.');
            }
        }

        return redirect()->back();
    }

    public function destroySelected(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'required|numeric'
        ]);

        $itemIds = array_map('intval', $request->item_ids);

        Cart::where('customer_id', auth()->id())
            ->whereIn('item_id', $itemIds)
            ->delete();

        $count = count($request->item_ids);
        return redirect()->back()->with('success', "$count item(s) removed from cart.");
    }

    public function destroy($itemId, Request $request)
    {
        if (!is_numeric($itemId)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Invalid item.'], 422);
            }
            return redirect()->back()->with('error', 'Invalid item.');
        }

        Cart::where('customer_id', auth()->id())
            ->where('item_id', $itemId)
            ->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Item removed from cart.']);
        }

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}
