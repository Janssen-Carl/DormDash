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
        $search = strip_tags(trim((string) $request->input('q', '')));
        $selectedVendors = $request->input('vendors', []);
        $selectedCategories = $request->input('categories', []);
        $sort = $request->input('sort', '');

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

        // Filter vendor/category IDs to only valid numeric values
        $selectedVendors = array_filter((array) $selectedVendors, 'is_numeric');
        $selectedCategories = array_filter((array) $selectedCategories, 'is_numeric');

        // Check if we should render a unified single grid
        $isSingleGrid = ($search !== '') 
            || ($sort !== '' && $sort !== 'default') 
            || !empty($selectedCategories) 
            || !empty($selectedVendors)
            || $request->input('is_bundle') == '1'
            || $request->input('has_discount') == '1';

        // Sorting closure to reuse sorting logic on both bundles and items
        $applySorting = function ($query) use ($sort) {
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'alpha_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'alpha_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'popularity':
                    $query->select('items.*')
                        ->selectSub(function ($subQuery) {
                            $subQuery->selectRaw('COALESCE(SUM(order_items.quantity), 0)')
                                ->from('order_items')
                                ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                                ->where('orders.order_status', '!=', 'cancelled')
                                ->whereColumn('order_items.item_id', 'items.item_id');
                        }, 'total_sold')
                        ->orderBy('total_sold', 'desc');
                    break;
                default:
                    // Default fallback is alphabetical A-Z
                    $query->orderBy('name', 'asc');
                    break;
            }
            return $query;
        };

        $categoryItems = [];
        $items = null;

        if ($isSingleGrid) {
            $itemsQuery = Item::where('is_active', true)
                ->where('is_available', true)
                ->with(['images', 'vendor', 'discounts' => function ($q) {
                    $q->where('is_active', true)
                      ->where('date_start', '<=', now())
                      ->where('date_end', '>=', now());
                }]);

            if ($request->input('is_bundle') == '1') {
                $itemsQuery->where('is_bundle', true);
            } elseif ($request->has('is_bundle') && $request->input('is_bundle') == '0') {
                $itemsQuery->where('is_bundle', false);
            }

            if ($request->input('has_discount') == '1') {
                $itemsQuery->whereHas('discounts', function ($q) {
                    $q->where('is_active', true)
                      ->where('date_start', '<=', now())
                      ->where('date_end', '>=', now());
                });
            }

            if (!empty($selectedVendors)) {
                $itemsQuery->whereIn('vendor_id', $selectedVendors);
            }

            if (!empty($selectedCategories)) {
                // Collect children categories recursively
                $categoryIds = Category::whereIn('category_id', $selectedCategories)
                    ->get()
                    ->flatMap(function($c) {
                        return $c->children->pluck('category_id')->push($c->category_id);
                    })
                    ->unique()
                    ->toArray();

                $itemsQuery->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.category_id', $categoryIds);
                });
            }

            if ($search !== '') {
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

            // Apply sorting
            $itemsQuery = $applySorting($itemsQuery);

            // Paginate results (24 items per page for balanced grid layout)
            $items = $itemsQuery->paginate(24)->withQueryString();
        } else {
            // 1. Fetch Bundles
            if ($isBundle || (empty($selectedCategories) && !$request->has('is_bundle'))) {
                $bundleQuery = Item::where('is_active', true)
                    ->where('is_available', true)
                    ->where('is_bundle', true)
                    ->with(['images', 'vendor', 'discounts' => function ($q) {
                        $q->where('is_active', true)
                          ->where('date_start', '<=', now())
                          ->where('date_end', '>=', now());
                    }]);
                    
                if ($request->input('has_discount') == '1') {
                    $bundleQuery->whereHas('discounts', function ($q) {
                        $q->where('is_active', true)
                          ->where('date_start', '<=', now())
                          ->where('date_end', '>=', now());
                    });
                }
                    
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

                // Apply dynamic sorting to bundles
                $bundleQuery = $applySorting($bundleQuery);
                
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
                        ->with(['images', 'vendor', 'discounts' => function ($q) {
                            $q->where('is_active', true)
                              ->where('date_start', '<=', now())
                              ->where('date_end', '>=', now());
                        }]);
                        
                    if ($request->input('has_discount') == '1') {
                        $itemsQuery->whereHas('discounts', function ($q) {
                            $q->where('is_active', true)
                              ->where('date_start', '<=', now())
                              ->where('date_end', '>=', now());
                        });
                    }
                        
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

                    // Apply dynamic sorting to category items
                    $itemsQuery = $applySorting($itemsQuery);

                    $itemsList = $itemsQuery->limit(10)->get();

                    if ($itemsList->isNotEmpty()) {
                        $categoryItems[] = [
                            'category' => $category,
                            'items' => $itemsList,
                        ];
                    }
                }
            }
        }

        return view('pages.products', compact(
            'categoryItems', 
            'parentCategories', 
            'vendors', 
            'selectedVendors', 
            'selectedCategories', 
            'search', 
            'sort',
            'isSingleGrid',
            'items'
        ));
    }

    public function show($id)
    {
        if (!is_numeric($id)) {
            abort(404);
        }

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
