<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        $cartItems = Cart::where('customer_id', $userId)
            ->with(['item.images', 'item.vendor'])
            ->get();
            
        $subtotal = $cartItems->sum(function($cart) {
            return $cart->item->price * $cart->quantity;
        });

        // Hardcoding standard fees for now (or make them dynamic if needed)
        $deliveryFee = $cartItems->count() > 0 ? 50.00 : 0.00;
        $total = $subtotal + $deliveryFee;

        return view('pages.cart', compact('cartItems', 'subtotal', 'deliveryFee', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,item_id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = auth()->id();
        
        $cart = Cart::where('customer_id', $userId)
            ->where('item_id', $request->item_id)
            ->first();

        if ($cart) {
            Cart::where('customer_id', $userId)
                ->where('item_id', $request->item_id)
                ->update([
                    'quantity' => $cart->quantity + $request->quantity
                ]);
        } else {
            Cart::create([
                'customer_id' => $userId,
                'item_id' => $request->item_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()->with('success', 'Item added to cart.');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'action' => 'required|in:increment,decrement'
        ]);

        $userId = auth()->id();
        
        $cart = Cart::where('customer_id', $userId)
            ->where('item_id', $itemId)
            ->firstOrFail();

        if ($request->action === 'increment') {
            Cart::where('customer_id', $userId)
                ->where('item_id', $itemId)
                ->increment('quantity');
        } elseif ($request->action === 'decrement') {
            if ($cart->quantity > 1) {
                Cart::where('customer_id', $userId)
                    ->where('item_id', $itemId)
                    ->decrement('quantity');
            } else {
                Cart::where('customer_id', $userId)
                    ->where('item_id', $itemId)
                    ->delete();
            }
        }

        return redirect()->back();
    }

    public function destroy($itemId)
    {
        Cart::where('customer_id', auth()->id())
            ->where('item_id', $itemId)
            ->delete();

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}
