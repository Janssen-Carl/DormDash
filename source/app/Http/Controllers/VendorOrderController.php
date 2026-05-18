<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('login')->with('error', 'Access denied. Vendor profile not found.');
        }

        $vendorId = $vendor->vendor_id;

        // Get all orders containing this vendor's items, ordered by creation date descending
        $orders = Order::with(['customer.user', 'address', 'items' => function ($query) use ($vendorId) {
                $query->where('items.vendor_id', $vendorId);
            }])
            ->whereHas('items', function ($query) use ($vendorId) {
                $query->where('items.vendor_id', $vendorId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate card statistics:
        // "Not Confirmed Yet" = 'pending' status
        $pendingCount = $orders->where('order_status', 'pending')->count();
        
        // "Confirmed Orders" = active states beyond pending ('to_ship', 'shipped', 'delivered')
        $confirmedCount = $orders->whereIn('order_status', ['to_ship', 'shipped', 'delivered'])->count();

        // Total orders managed by this vendor
        $totalCount = $orders->count();

        return view('pages.vendor-orders', compact('orders', 'pendingCount', 'confirmedCount', 'totalCount'));
    }

    public function confirm($orderId)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->back()->with('error', 'Vendor profile not found.');
        }

        // Find the order that has this vendor's items and is currently pending
        $order = Order::whereHas('items', function ($query) use ($vendor) {
                $query->where('items.vendor_id', $vendor->vendor_id);
            })
            ->where('order_id', $orderId)
            ->where('order_status', 'pending')
            ->firstOrFail();

        // Transition: pending -> to_ship
        $order->update(['order_status' => 'to_ship']);

        return redirect()->back()->with('success', "Order #{$orderId} has been confirmed successfully!");
    }

    public function ship($orderId)
    {
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
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->back()->with('error', 'Vendor profile not found.');
        }

        // Find the order that has this vendor's items and is currently shipped
        $order = Order::whereHas('items', function ($query) use ($vendor) {
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

        return redirect()->back()->with('success', "Order #{$orderId} has been delivered successfully!");
    }
}
