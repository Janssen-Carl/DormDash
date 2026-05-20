<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorHomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;
        
        $totalProducts = 0;
        $activeOrders = 0;
        $totalRevenue = 0;
        $activities = collect();

        if ($vendor) {
            $vendorId = $vendor->vendor_id;
            
            // Get all item IDs belonging to this vendor
            $vendorItems = Item::where('vendor_id', $vendorId)->pluck('item_id');

            // 1. Total Products
            $totalProducts = $vendorItems->count();

            // 2. Active Orders (orders that are to_ship, shipped, or delivered AND have vendor's items)
            $activeOrders = Order::whereIn('order_status', ['to_ship', 'shipped', 'delivered'])
                ->whereHas('items', function ($query) use ($vendorId) {
                    $query->where('items.vendor_id', $vendorId);
                })
                ->count();

            // 3. Total Revenue (sum of quantity * price for completed orders of this vendor's items)
            $totalRevenue = DB::table('order_items')
                ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
                ->join('items', 'items.item_id', '=', 'order_items.item_id')
                ->where('items.vendor_id', $vendorId)
                ->where('orders.order_status', 'completed')
                ->sum(DB::raw('order_items.quantity * order_items.price'));

            // 4. Gather Activities
            // A. New Orders (up to 5 recent orders)
            $recentOrders = Order::with(['customer.user', 'items' => function ($query) use ($vendorId) {
                    $query->where('items.vendor_id', $vendorId);
                }])
                ->whereHas('items', function ($query) use ($vendorId) {
                    $query->where('items.vendor_id', $vendorId);
                })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            foreach ($recentOrders as $order) {
                $itemsCount = $order->items->sum('pivot.quantity');
                $itemsTotal = $order->items->sum(function ($item) {
                    return $item->pivot->quantity * $item->pivot->price;
                });

                $activities->push((object)[
                    'type' => 'order',
                    'title' => 'New Order Received',
                    'description' => "Order #{$order->order_id} placed by " . ($order->customer->user->username ?? 'Customer') . " ({$itemsCount} item" . ($itemsCount > 1 ? 's' : '') . ")",
                    'time' => $order->created_at,
                    'amount' => '₱' . number_format($itemsTotal, 2),
                    'status' => $order->order_status,
                    'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>',
                    'color' => 'bg-indigo-50 text-indigo-600 border border-indigo-100',
                    'url' => route('vendor.orders')
                ]);
            }

            // B. Low/Out of Stock Alerts
            $lowStockItems = Item::where('vendor_id', $vendorId)
                ->where('stock', '<=', 5)
                ->orderBy('stock', 'asc')
                ->take(3)
                ->get();

            foreach ($lowStockItems as $item) {
                $activities->push((object)[
                    'type' => 'stock_alert',
                    'title' => $item->stock == 0 ? 'Out of Stock' : 'Low Stock Warning',
                    'description' => "Product '{$item->name}' is low on stock ({$item->stock} remaining)",
                    'time' => $item->updated_at ?? now(),
                    'amount' => null,
                    'status' => $item->stock == 0 ? 'out_of_stock' : 'low_stock',
                    'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
                    'color' => $item->stock == 0 ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-amber-50 text-amber-600 border border-amber-100',
                    'url' => route('vendor.products')
                ]);
            }

            // C. Newly Added Products
            $newProducts = Item::where('vendor_id', $vendorId)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            foreach ($newProducts as $item) {
                $activities->push((object)[
                    'type' => 'new_product',
                    'title' => 'New Product Added',
                    'description' => "'{$item->name}' was added to your products catalog.",
                    'time' => $item->created_at,
                    'amount' => '₱' . number_format($item->price, 2),
                    'status' => 'active',
                    'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>',
                    'color' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                    'url' => route('vendor.products')
                ]);
            }

            // Sort activities by time descending, and take the top 5
            $activities = $activities->sortByDesc('time')->values()->take(5);
        }

        return view('vendor-home', compact('vendor', 'totalProducts', 'activeOrders', 'totalRevenue', 'activities'));
    }
}
