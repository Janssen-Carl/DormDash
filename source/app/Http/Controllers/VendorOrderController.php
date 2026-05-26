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
        $confirmedCount = (clone $baseQuery)->where('order_status', 'to_ship')->count();
        $cancelledCount = (clone $baseQuery)->where('order_status', 'cancelled')->count();
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
                $query->where('order_status', 'to_ship');
            } elseif (in_array($status, $allowedStatuses)) {
                $query->where('order_status', $status);
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('pages.vendor-orders', compact('orders', 'pendingCount', 'confirmedCount', 'cancelledCount', 'totalCount', 'search', 'status'));
    }

    public function confirm($orderId)
    {
        if (!is_numeric($orderId)) {
            return redirect()->route('vendor.orders', ['status' => 'pending'])->with('error', 'Invalid order ID.');
        }

        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.orders', ['status' => 'pending'])->with('error', 'Vendor profile not found.');
        }

        $order = Order::with('items')->whereHas('items', function ($query) use ($vendor) {
                $query->where('items.vendor_id', $vendor->vendor_id);
            })
            ->where('order_id', $orderId)
            ->where('order_status', 'pending')
            ->first();

        if (!$order) {
            return redirect()->route('vendor.orders', ['status' => 'pending'])->with('error', "Order #{$orderId} not found or already confirmed.");
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $vendor) {
            foreach ($order->items as $item) {
                if ($item->vendor_id === $vendor->vendor_id) {
                    $quantityOrdered = $item->pivot->quantity;

                    if ($item->stock >= $quantityOrdered) {
                        $item->decrement('stock', $quantityOrdered);
                    } else {
                        $item->stock = max(0, $item->stock - $quantityOrdered);
                        $item->save();
                    }

                    if ($item->is_bundle) {
                        foreach ($item->bundles as $childItem) {
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

            $order->update(['order_status' => 'to_ship']);
        });

        return redirect()->route('vendor.orders', ['status' => 'confirmed'])
            ->with('success', "Order #{$orderId} has been confirmed successfully!");
    }

    public function ship($orderId)
    {
        if (!is_numeric($orderId)) {
            return redirect()->route('vendor.orders', ['status' => 'to_ship'])->with('error', 'Invalid order ID.');
        }

        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.orders', ['status' => 'to_ship'])->with('error', 'Vendor profile not found.');
        }

        $order = Order::whereHas('items', function ($query) use ($vendor) {
                $query->where('items.vendor_id', $vendor->vendor_id);
            })
            ->where('order_id', $orderId)
            ->where('order_status', 'to_ship')
            ->first();

        if (!$order) {
            return redirect()->route('vendor.orders', ['status' => 'to_ship'])->with('error', "Order #{$orderId} not found or already shipped.");
        }

        $order->update([
            'order_status' => 'shipped',
            'send_date' => now(),
            'tracking_number' => 'TRK-' . date('Ymd') . '-' . str_pad($orderId, 3, '0', STR_PAD_LEFT)
        ]);

        return redirect()->route('vendor.orders', ['status' => 'shipped'])->with('success', "Order #{$orderId} has been marked as shipped!");
    }

    public function deliver($orderId)
    {
        if (!is_numeric($orderId)) {
            return redirect()->route('vendor.orders', ['status' => 'shipped'])->with('error', 'Invalid order ID.');
        }

        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.orders', ['status' => 'shipped'])->with('error', 'Vendor profile not found.');
        }

        $order = Order::with('paymentTransaction')->whereHas('items', function ($query) use ($vendor) {
                $query->where('items.vendor_id', $vendor->vendor_id);
            })
            ->where('order_id', $orderId)
            ->where('order_status', 'shipped')
            ->first();

        if (!$order) {
            return redirect()->route('vendor.orders', ['status' => 'shipped'])->with('error', "Order #{$orderId} not found or already delivered.");
        }

        $order->update([
            'order_status' => 'delivered',
            'receive_date' => now()
        ]);

        if ($order->paymentTransaction && $order->paymentTransaction->payment_method === 'cod') {
            $order->paymentTransaction->update(['status' => 'paid']);
        }

        return redirect()->route('vendor.orders', ['status' => 'delivered'])->with('success', "Order #{$orderId} has been delivered successfully!");
    }
}
