<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('login')->with('error', 'Access denied. Vendor profile not found.');
        }

        $vendorId = $vendor->vendor_id;

        // 1. Calculate overall card statistics (unfiltered)
        $baseQuery = Order::whereHas('items', function ($query) use ($vendorId) {
            $query->where('items.vendor_id', $vendorId);
        });

        $pendingCount = (clone $baseQuery)->where('order_status', 'pending')->count();
        $confirmedCount = (clone $baseQuery)->whereIn('order_status', ['to_ship', 'shipped', 'delivered'])->count();
        $totalCount = $baseQuery->count();

        // 2. Build filtered orders query for listing
        $search = strip_tags(trim($request->query('search', '')));
        $status = $request->query('status');

        $query = Order::with(['customer.user', 'address', 'items' => function ($q) use ($vendorId) {
                $q->where('items.vendor_id', $vendorId);
            }])
            ->whereHas('items', function ($q) use ($vendorId) {
                $q->where('items.vendor_id', $vendorId);
            });

        // Apply Search Filter
        if ($search) {
            $query->where(function ($q) use ($search, $vendorId) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhere('shipping_method', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhereHas('user', function ($uq) use ($search) {
                             $uq->where('username', 'like', "%{$search}%");
                         });
                  })
                  ->orWhereHas('items', function ($iq) use ($search, $vendorId) {
                      $iq->where('items.vendor_id', $vendorId)
                         ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply Status Filter
        $allowedStatuses = ['pending', 'to_ship', 'shipped', 'delivered', 'completed', 'cancelled'];
        if ($status && $status !== 'all') {
            if ($status === 'pending') {
                $query->where('order_status', 'pending');
            } elseif ($status === 'confirmed') {
                $query->whereIn('order_status', ['to_ship', 'shipped', 'delivered']);
            } elseif (in_array($status, $allowedStatuses)) {
                $query->where('order_status', $status);
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('pages.vendor-orders', compact('orders', 'pendingCount', 'confirmedCount', 'totalCount', 'search', 'status'));
    }

    public function confirm($orderId)
    {
        if (!is_numeric($orderId)) {
            return redirect()->back()->with('error', 'Invalid order ID.');
        }

        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->back()->with('error', 'Vendor profile not found.');
        }

        // Find the order that has this vendor's items and is currently pending
        $order = Order::with('items')->whereHas('items', function ($query) use ($vendor) {
                $query->where('items.vendor_id', $vendor->vendor_id);
            })
            ->where('order_id', $orderId)
            ->where('order_status', 'pending')
            ->firstOrFail();

        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $vendor) {
            // Deduct stock for items belonging to this vendor in this order
            foreach ($order->items as $item) {
                if ($item->vendor_id === $vendor->vendor_id) {
                    $quantityOrdered = $item->pivot->quantity;
                    
                    // 1. Deduct stock for the main item (standard product or the bundle itself)
                    if ($item->stock >= $quantityOrdered) {
                        $item->decrement('stock', $quantityOrdered);
                    } else {
                        // Not enough stock, decrement to 0 at worst
                        $item->stock = max(0, $item->stock - $quantityOrdered);
                        $item->save();
                    }

                    // 2. If the item is a bundle, proportionally deduct stock from its included sub-products
                    if ($item->is_bundle) {
                        foreach ($item->bundles as $childItem) {
                            // Calculate total needed: (quantity of child per bundle) * (number of bundles ordered)
                            $totalChildDeduction = $childItem->pivot->quantity * $quantityOrdered;
                            
                            if ($childItem->stock >= $totalChildDeduction) {
                                $childItem->decrement('stock', $totalChildDeduction);
                            } else {
                                $childItem->stock = max(0, $childItem->stock - $totalChildDeduction);
                                $childItem->save();
                            }
                        }
                    }
                }
            }

            // Transition: pending -> to_ship
            $order->update(['order_status' => 'to_ship']);
        });

        return redirect()->back()->with('success', "Order #{$orderId} has been confirmed successfully!");
    }

    public function ship($orderId)
    {
        if (!is_numeric($orderId)) {
            return redirect()->back()->with('error', 'Invalid order ID.');
        }

        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->back()->with('error', 'Vendor profile not found.');
        }

        // Find the order that has this vendor's items and is ready to ship
        $order = Order::whereHas('items', function ($query) use ($vendor) {
                $query->where('items.vendor_id', $vendor->vendor_id);
            })
            ->where('order_id', $orderId)
            ->where('order_status', 'to_ship')
            ->firstOrFail();

        // Transition: to_ship -> shipped
        $order->update([
            'order_status' => 'shipped',
            'send_date' => now(),
            'tracking_number' => 'TRK-' . date('Ymd') . '-' . str_pad($orderId, 3, '0', STR_PAD_LEFT)
        ]);

        return redirect()->back()->with('success', "Order #{$orderId} has been marked as shipped!");
    }

    public function deliver($orderId)
    {
        if (!is_numeric($orderId)) {
            return redirect()->back()->with('error', 'Invalid order ID.');
        }

        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->back()->with('error', 'Vendor profile not found.');
        }

        // Find the order that has this vendor's items and is currently shipped
        $order = Order::with('paymentTransaction')->whereHas('items', function ($query) use ($vendor) {
                $query->where('items.vendor_id', $vendor->vendor_id);
            })
            ->where('order_id', $orderId)
            ->where('order_status', 'shipped')
            ->firstOrFail();

        // Transition: shipped -> delivered
        $order->update([
            'order_status' => 'delivered',
            'receive_date' => now()
        ]);

        // FR-19: For COD orders, cash is collected on delivery → mark payment as 'paid'
        if ($order->paymentTransaction && $order->paymentTransaction->payment_method === 'cod') {
            $order->paymentTransaction->update(['status' => 'paid']);
        }

        return redirect()->back()->with('success', "Order #{$orderId} has been delivered successfully!");
    }
}
