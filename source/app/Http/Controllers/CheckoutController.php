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
                $q->usable();
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
                $q->usable();
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
                $q->usable();
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

        // Validate stock before showing checkout page
        foreach ($items as $entry) {
            $effectiveStock = $entry->item->effective_stock;
            if ($entry->quantity > $effectiveStock) {
                return redirect()->route('cart.index')->withErrors(['msg' => "Only {$effectiveStock} unit(s) of '{$entry->item->name}' are available. Please adjust your cart."]);
            }
        }

        $deliveryFee = 50.00;
        $total = $subtotal + $deliveryFee;
        
        $customer = \App\Models\Customer::where('customer_id', $userId)->first();
        $cards = $customer ? \App\Models\CusBankingInfo::where('customer_id', $customer->customer_id)->get() : collect();
        $defaultCardId = $customer ? $customer->primary_banking_info : null;

        // Fetch user addresses for the view, sorted with primary address first
        $user = auth()->user();
        $primaryAddressId = null;
        if ($user->role === 'vendor') {
            $primaryAddressId = $user->vendor->address_id ?? null;
        } else {
            $primaryAddressId = $user->customer->primary_address_id ?? null;
        }

        $addresses = Address::where('user_id', $userId)->get();
        if ($primaryAddressId) {
            $addresses = $addresses->sortByDesc(function ($address) use ($primaryAddressId) {
                return $address->address_id == $primaryAddressId;
            })->values();
        }

        if ($addresses->isEmpty()) {
            return redirect('/profile/edit')->withErrors(['msg' => 'You need to add a delivery address before checking out.']);
        }

        return view('pages.checkout', compact('items', 'subtotal', 'deliveryFee', 'total', 'addresses', 'sourceType', 'sourceData', 'cards', 'defaultCardId'));
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
                $q->usable();
            }])->where('customer_id', $userId)->findOrFail($request->reorder_id);
            foreach ($order->items as $item) {
                $quantity = isset($updatedQuantities[$item->item_id]) ? (int)$updatedQuantities[$item->item_id] : $item->pivot->quantity;
                if ($quantity > 0) {
                    $items->push((object)[
                        'item_id' => $item->item_id,
                        'quantity' => $quantity,
                        'price' => $item->discounted_price,
                        'original_price' => (float)$item->price,
                    ]);
                }
            }
        } elseif ($request->source === 'buy_now') {
            if (!$request->filled('buy_item') || !is_numeric($request->buy_item) || !$request->filled('qty') || !is_numeric($request->qty)) {
                return redirect()->route('cart.index')->withErrors(['msg' => 'Invalid item reference.']);
            }
            $item = Item::with(['discounts' => function ($q) {
                $q->usable();
            }])->findOrFail($request->buy_item);
            $quantity = isset($updatedQuantities[$item->item_id]) ? (int)$updatedQuantities[$item->item_id] : (int)$request->qty;
            if ($quantity > 0) {
                $items->push((object)[
                    'item_id' => $item->item_id,
                    'quantity' => $quantity,
                    'price' => $item->discounted_price,
                    'original_price' => (float)$item->price,
                ]);
            }
        } elseif ($request->source === 'cart') {
            $selectedItems = $request->input('selected_items', []);
            if (!is_array($selectedItems) || empty($selectedItems)) {
                return redirect()->route('cart.index')->withErrors(['msg' => 'No items selected.']);
            }
            $selectedItems = array_filter($selectedItems, 'is_numeric');
            $cartItems = Cart::with(['item.discounts' => function ($q) {
                $q->usable();
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
                        'original_price' => (float)$cart->item->price,
                    ]);
                }
            }
        }

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['msg' => 'Order could not be processed.']);
        }

        // Validate stock for all items before proceeding
        $itemIds = $items->pluck('item_id')->unique();
        $stockItems = Item::with('bundles')->whereIn('item_id', $itemIds)->get()->keyBy('item_id');
        foreach ($items as $entry) {
            $stockItem = $stockItems[$entry->item_id] ?? null;
            if (!$stockItem) {
                return redirect()->route('cart.index')->withErrors(['msg' => 'An item in your cart no longer exists.']);
            }
            $effectiveStock = $stockItem->effective_stock;
            if ($entry->quantity > $effectiveStock) {
                return redirect()->route('cart.index')->withErrors(['msg' => "Only {$effectiveStock} unit(s) of '{$stockItem->name}' are available. Please adjust your quantity."]);
            }
        }

        // Calculate per-unit discounted quantities & correct subtotal
        $discountedItems = Item::with(['discounts' => function ($q) {
            $q->usable();
        }])->whereIn('item_id', $itemIds)->get()->keyBy('item_id');

        $subtotal = 0.0;
        $usedDiscounts = [];

        foreach ($items as $entry) {
            $itemModel = $discountedItems->get($entry->item_id);
            $discountedQty = $entry->quantity;

            if ($itemModel) {
                $discount = $itemModel->getActiveDiscount();
                if ($discount && $discount->use_limit !== null) {
                    $remaining = max(0, $discount->use_limit - $discount->redemption_count);
                    $discountedQty = min($entry->quantity, $remaining);
                    if ($discountedQty > 0) {
                        $usedDiscounts[$discount->discount_id] = ($usedDiscounts[$discount->discount_id] ?? 0) + $discountedQty;
                    }
                }
            }

            $entry->discounted_qty = $discountedQty;
            if ($discountedQty < $entry->quantity) {
                $fullPriceQty = $entry->quantity - $discountedQty;
                $blendedPrice = ($discountedQty * $entry->price + $fullPriceQty * $entry->original_price) / $entry->quantity;
                $entry->price = $blendedPrice;
            }

            $subtotal += $entry->price * $entry->quantity;
        }

        // Group items by vendor so each vendor gets their own order
        $itemVendorMap = Item::whereIn('item_id', $items->pluck('item_id'))->pluck('vendor_id', 'item_id');
        $grouped = $items->groupBy(fn($entry) => $itemVendorMap[$entry->item_id] ?? 0);

        $deliveryFee = 50.00;

        // Securely prepare tokenized card info if using card payment
        $cardToken = null;
        $cardLast4 = null;
        if ($request->payment_method === 'card') {
            $customer = \App\Models\Customer::where('customer_id', $userId)->firstOrFail();
            if ($request->filled('card_id') && $request->card_id !== 'new') {
                $banking = \App\Models\CusBankingInfo::where('customer_id', $customer->customer_id)
                    ->where('banking_id', $request->card_id)
                    ->firstOrFail();
                $cardToken = $banking->token;
                $cardLast4 = $banking->acc_last4_no;
            } else {
                $request->validate([
                    'new_card_name' => 'required|string|max:100',
                    'new_card_number' => 'required|string',
                    'new_card_type' => 'required|in:visa,mastercard,amex,discover',
                ]);
                $cleanCard = preg_replace('/\s+/', '', $request->new_card_number);
                $cardLast4 = substr($cleanCard, -4);
                $cardToken = 'TOK_' . strtoupper(uniqid());

                \App\Models\CusBankingInfo::create([
                    'customer_id' => $customer->customer_id,
                    'payment_method' => $request->new_card_type,
                    'provider' => ucfirst($request->new_card_type),
                    'account_name' => strtoupper($request->new_card_name),
                    'acc_last4_no' => $cardLast4,
                    'token' => $cardToken,
                ]);
            }
        }

        DB::transaction(function () use ($userId, $request, $grouped, $deliveryFee, $cardToken, $cardLast4, $usedDiscounts) {
            foreach ($grouped as $vendorId => $vendorItems) {
                $subtotal = $vendorItems->sum(fn($entry) => $entry->price * $entry->quantity);
                $total = $subtotal + $deliveryFee;

                $order = Order::create([
                    'customer_id' => $userId,
                    'address_id' => $request->address_id,
                    'shipping_method' => 'Standard',
                    'order_total' => $total,
                    'order_status' => 'pending'
                ]);

                $syncData = [];
                foreach ($vendorItems as $entry) {
                    $syncData[$entry->item_id] = [
                        'quantity' => $entry->quantity,
                        'price' => $entry->price,
                        'discounted_qty' => $entry->discounted_qty,
                    ];
                }
                $order->items()->attach($syncData);

                $paymentMethod = $request->payment_method;
                $paymentStatus = ($paymentMethod === 'cod') ? 'pending' : 'success';

                PaymentTransaction::create([
                    'order_id'       => $order->order_id,
                    'amount'         => $total,
                    'status'         => $paymentStatus,
                    'payment_method' => $paymentMethod,
                    'reference_no'   => 'REF' . strtoupper(uniqid()),
                    'token'          => $cardToken,
                    'acc_last4_no'   => $cardLast4,
                ]);
            }

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

            // 5. Track discount redemptions (per-unit)
            foreach ($usedDiscounts as $discountId => $count) {
                $discount = \App\Models\Discount::find($discountId);
                if ($discount) {
                    $newCount = $discount->redemption_count + $count;
                    $discount->update([
                        'redemption_count' => $newCount,
                        'is_active' => ($discount->use_limit !== null && $newCount >= $discount->use_limit) ? false : $discount->is_active,
                    ]);
                }
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order successfully placed!');
    }
}
