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
        $search = trim((string) $request->input('q', ''));
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

        $isBundle = $request->input('is_bundle') == '1';

        // Group items by parent category
        $categoryItems = [];
        
        // 1. Fetch Bundles
        if ($isBundle || (empty($selectedCategories) && !$request->has('is_bundle'))) {
            $bundleQuery = Item::where('is_active', true)
                ->where('is_available', true)
                ->where('is_bundle', true)
                ->with(['images', 'vendor']);
                
            if (!empty($selectedVendors)) {
                $bundleQuery->whereIn('vendor_id', $selectedVendors);
            }

            if ($search !== '') {
                $bundleQuery->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhereHas('vendor', function ($vendorQuery) use ($search) {
                            $vendorQuery->where('name', 'like', "%{$search}%");
                        });
                });
            }
            
            $bundles = $bundleQuery->limit(20)->get();
            if ($bundles->isNotEmpty()) {
                $categoryItems[] = [
                    'category' => (object) ['name' => 'Featured Bundles', 'category_id' => 'bundles'],
                    'items' => $bundles,
                ];
            }
        }

        // 2. Fetch standard Categories
        $shouldFetchCategories = true;
        if ($isBundle && empty($selectedCategories)) {
            $shouldFetchCategories = false;
        }

        if ($shouldFetchCategories) {
            $categoriesToDisplay = empty($selectedCategories) 
                ? $parentCategories 
                : $parentCategories->whereIn('category_id', $selectedCategories);

            foreach ($categoriesToDisplay as $category) {
                // Collect this category + its children IDs
                $categoryIds = $category->children->pluck('category_id')->push($category->category_id);
                $categoryMatchesSearch = $search !== '' && collect([$category])
                    ->merge($category->children)
                    ->contains(function ($category) use ($search) {
                        return str_contains(strtolower($category->name ?? ''), strtolower($search))
                            || str_contains(strtolower($category->description ?? ''), strtolower($search));
                    });

                $itemsQuery = Item::where('is_active', true)
                    ->where('is_available', true)
                    ->where('is_bundle', false) // Exclude bundles from standard categories
                    ->whereHas('categories', function ($q) use ($categoryIds) {
                        $q->whereIn('categories.category_id', $categoryIds);
                    })
                    ->with(['images', 'vendor']);
                    
                if (!empty($selectedVendors)) {
                    $itemsQuery->whereIn('vendor_id', $selectedVendors);
                }

                if ($search !== '' && ! $categoryMatchesSearch) {
                    $itemsQuery->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%")
                            ->orWhere('barcode', 'like', "%{$search}%")
                            ->orWhereHas('vendor', function ($vendorQuery) use ($search) {
                                $vendorQuery->where('name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('categories', function ($categoryQuery) use ($search) {
                                $categoryQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('description', 'like', "%{$search}%");
                            });
                    });
                }

                $items = $itemsQuery->limit(10)->get();

                if ($items->isNotEmpty()) {
                    $categoryItems[] = [
                        'category' => $category,
                        'items' => $items,
                    ];
                }
            }
        }

        return view('pages.products', compact('categoryItems', 'parentCategories', 'vendors', 'selectedVendors', 'selectedCategories', 'search'));
    }

    public function show($id)
    {
        $item = Item::with(['images', 'vendor', 'categories', 'discounts' => function ($q) {
                $q->where('is_active', true)
                  ->where('date_start', '<=', now())
                  ->where('date_end', '>=', now());
            }])
            ->where('item_id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProductIds = collect();

        if ($item->categories->isNotEmpty()) {
            $categoryIds = $item->categories->pluck('category_id');
            $relatedProductIds = Item::where('is_active', true)
                ->where('is_available', true)
                ->where('item_id', '!=', $id)
                ->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.category_id', $categoryIds);
                })
                ->inRandomOrder()
                ->limit(6)
                ->pluck('item_id');
        }

        $vendorRelatedIds = Item::where('is_active', true)
            ->where('is_available', true)
            ->where('item_id', '!=', $id)
            ->where('vendor_id', $item->vendor_id)
            ->whereNotIn('item_id', $relatedProductIds)
            ->inRandomOrder()
            ->limit(6)
            ->pluck('item_id');

        $allRelatedIds = $relatedProductIds->merge($vendorRelatedIds)->unique();

        if ($allRelatedIds->isNotEmpty()) {
            $relatedProducts = Item::whereIn('item_id', $allRelatedIds)
                ->with(['images', 'vendor'])
                ->inRandomOrder()
                ->limit(4)
                ->get();
        } else {
            $relatedProducts = Item::where('is_active', true)
                ->where('is_available', true)
                ->where('item_id', '!=', $id)
                ->with(['images', 'vendor'])
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        return view('pages.product-detail', compact('item', 'relatedProducts'));
    }
}
