<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $selectedVendors = $request->input('vendors', []);
        $selectedCategories = $request->input('categories', []);

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
        
        // Filter which categories to display based on selection
        $categoriesToDisplay = empty($selectedCategories) 
            ? $parentCategories 
            : $parentCategories->whereIn('category_id', $selectedCategories);

        foreach ($categoriesToDisplay as $category) {
            // Collect this category + its children IDs
            $categoryIds = $category->children->pluck('category_id')->push($category->category_id);

            $itemsQuery = Item::where('is_active', true)
                ->where('is_available', true)
                ->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.category_id', $categoryIds);
                })
                ->with(['images', 'vendor']);
                
            // Apply vendor filter if any are selected
            if (!empty($selectedVendors)) {
                $itemsQuery->whereIn('vendor_id', $selectedVendors);
            }

            $items = $itemsQuery->limit(10)->get();

            if ($items->isNotEmpty()) {
                $categoryItems[] = [
                    'category' => $category,
                    'items' => $items,
                ];
            }
        }

        return view('pages.products', compact('categoryItems', 'parentCategories', 'vendors', 'selectedVendors', 'selectedCategories'));
    }
}
