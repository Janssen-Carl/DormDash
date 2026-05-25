<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Discount;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Featured products: Top selling available items based on order_items quantity sold (general analytics), fallback to default order
        $featuredProducts = Item::where('is_active', true)
            ->where('is_available', true)
            ->with(['images', 'vendor', 'categories', 'discounts' => function ($q) {
                $q->where('is_active', true)
                  ->where('date_start', '<=', now())
                  ->where('date_end', '>=', now());
            }])
            ->orderByDesc(
                DB::table('order_items')
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('order_items.item_id', 'items.item_id')
            )
            ->orderBy('name', 'asc')
            ->limit(8)
            ->get();

        // Active discount offers with their items
        $offers = Discount::where('is_active', true)
            ->where('date_start', '<=', now())
            ->where('date_end', '>=', now())
            ->with(['item', 'item.images', 'item.vendor'])
            ->limit(6)
            ->get();

        // Categories for browsing (child categories only — parent rows self-reference)
        $categories = Category::whereColumn('parent_id', '!=', 'category_id')
            ->where('is_active', true)
            ->limit(10)
            ->get();

        return view('home', compact('featuredProducts', 'offers', 'categories'));
    }
}
