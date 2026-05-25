<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function overview()
    {
        $userId = auth()->id();
        
        $orders = Order::where('customer_id', $userId)->get();
        
        $totalOrders = $orders->count();
        $totalSpent = $orders->where('order_status', 'completed')->sum('order_total');
        
        $toShipCount = $orders->where('order_status', 'to_ship')->count();
        $shippedCount = $orders->where('order_status', 'shipped')->count();
        $deliveredCount = $orders->where('order_status', 'delivered')->count();
        $completedCount = $orders->where('order_status', 'completed')->count();
        
        $recentOrders = Order::where('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('pages.orders-overview', compact(
            'totalOrders', 
            'totalSpent', 
            'toShipCount', 
            'shippedCount', 
            'deliveredCount',
            'completedCount',
            'recentOrders'
        ));
    }

    public function index(Request $request)
    {
        $userId = auth()->id();
        
        $query = Order::where('customer_id', $userId)
            ->with(['items.images', 'address', 'paymentTransaction'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && in_array($request->status, ['pending', 'to_ship', 'shipped', 'delivered', 'completed', 'cancelled'])) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->get();
            
        return view('pages.orders', compact('orders'));
    }

    public function complete($orderId)
    {
        if (!is_numeric($orderId)) {
            return redirect()->back()->with('error', 'Invalid order ID.');
        }

        $userId = auth()->id();
        
        $order = Order::where('customer_id', $userId)
            ->where('order_id', $orderId)
            ->where('order_status', 'delivered')
            ->firstOrFail();

        $order->update(['order_status' => 'completed']);

        return redirect()->back()->with('success', 'Order confirmed! You can now reorder these items.');
    }

    public function analytics()
    {
        $userId = auth()->id();
        
        // Get completed orders from the last 6 months
        $orders = Order::where('customer_id', $userId)
            ->where('order_status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->orderBy('created_at')
            ->get();

        // Group by month
        $monthlyData = $orders->groupBy(function($order) {
            return $order->created_at->format('M Y');
        })->map(function($month) {
            return $month->sum('order_total');
        });

        // Ensure last 6 months exist in array even if 0
        $labels = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStr = now()->subMonths($i)->format('M Y');
            $labels[] = $monthStr;
            $data[] = $monthlyData->get($monthStr, 0);
        }

        return view('pages.analytics', compact('labels', 'data'));
    }

    public function cancel($orderId)
    {
        if (!is_numeric($orderId)) {
            return redirect()->back()->with('error', 'Invalid order ID.');
        }

        $userId = auth()->id();
        
        $order = Order::where('customer_id', $userId)
            ->where('order_id', $orderId)
            ->where('order_status', 'pending')
            ->firstOrFail();

        $order->update(['order_status' => 'cancelled']);

        return redirect()->back()->with('success', 'Order has been cancelled successfully.');
    }

    public function track($tracking)
    {
        $tracking = strip_tags(trim($tracking));

        if (empty($tracking)) {
            return redirect()->back()->with('error', 'Invalid tracking reference.');
        }

        $userId = auth()->id();
        
        $order = Order::where('customer_id', $userId)
            ->where(function($q) use ($tracking) {
                $q->where('tracking_number', $tracking)
                  ->orWhere('order_id', is_numeric($tracking) ? $tracking : 0);
            })
            ->with(['items.images', 'address', 'paymentTransaction'])
            ->firstOrFail();
            
        return view('pages.track', compact('order'));
    }
}
