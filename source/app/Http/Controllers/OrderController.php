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
        $totalSpent = $orders->whereIn('order_status', ['delivered', 'completed'])->sum('order_total');
        
        $pendingCount = $orders->whereIn('order_status', ['pending', 'processing', 'in transit'])->count();
        $deliveredCount = $orders->whereIn('order_status', ['delivered', 'completed'])->count();
        
        $recentOrders = Order::where('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('pages.orders-overview', compact(
            'totalOrders', 
            'totalSpent', 
            'pendingCount', 
            'deliveredCount', 
            'recentOrders'
        ));
    }

    public function index()
    {
        $userId = auth()->id();
        
        $orders = Order::where('customer_id', $userId)
            ->with(['items.images']) // Eager load items and their images
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pages.orders', compact('orders'));
    }

}
