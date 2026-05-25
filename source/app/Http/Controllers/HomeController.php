<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Discount;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Featured products: 4 random available items with their first image and vendor
        $featuredProducts = Item::where('is_active', true)
            ->where('is_available', true)
            ->with(['images', 'vendor', 'categories', 'discounts' => function ($q) {
                $q->where('is_active', true)
                  ->where('date_start', '<=', now())
                  ->where('date_end', '>=', now());
            }])
            ->inRandomOrder()
            ->limit(4)
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
