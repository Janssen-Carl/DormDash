<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Vendor;

class ProductController extends Controller
{
    public function index()
    {
        // Get all parent categories (self-referencing = top-level)
        $parentCategories = Category::whereColumn('parent_id', 'category_id')
            ->where('is_active', true)
            ->with(['children' => function ($q) {
                $q->where('is_active', true);
            }])
            ->get();

        // Get all vendors for sidebar filter
        $vendors = Vendor::where('active', true)->get();

        // Group items by parent category
        $categoryItems = [];
        foreach ($parentCategories as $category) {
            // Collect this category + its children IDs
            $categoryIds = $category->children->pluck('category_id')->push($category->category_id);

            $items = Item::where('is_active', true)
                ->where('is_available', true)
                ->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.category_id', $categoryIds);
                })
                ->with(['images', 'vendor'])
                ->limit(10)
                ->get();

            if ($items->isNotEmpty()) {
                $categoryItems[] = [
                    'category' => $category,
                    'items' => $items,
                ];
            }
        }

        return view('pages.products', compact('categoryItems', 'parentCategories', 'vendors'));
    }
}
