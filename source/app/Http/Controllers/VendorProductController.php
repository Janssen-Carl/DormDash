<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorProductController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;
        
        $products = collect();
        if ($vendor) {
            $query = Item::where('vendor_id', $vendor->vendor_id)
                ->with(['images']);

            // Handle Search
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('sku', 'like', '%' . $search . '%')
                      ->orWhere('brand', 'like', '%' . $search . '%');
                });
            }

            // Handle Filter — default to active only (so "deleted" items vanish)
            $status = $request->input('status', 'active');
            if ($status === 'active') {
                $query->where('is_active', 1);
            } elseif ($status === 'inactive') {
                $query->where('is_active', 0);
            }
            // 'all' shows everything

            // Handle Sorting
            $sortBy = $request->input('sort', 'created_at');
            $sortDir = $request->input('dir', 'desc');

            $allowedSorts = ['stock', 'price', 'is_available', 'created_at'];
            if (!in_array($sortBy, $allowedSorts)) {
                $sortBy = 'created_at';
            }
            $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

            $products = $query->orderBy($sortBy, $sortDir)->get();
        }

        return view('pages.vendor-products', compact('products', 'sortBy', 'sortDir', 'status'));
    }

    public function restock(Request $request, Item $item)
    {
        // Ensure the item belongs to the authenticated vendor
        if ($item->vendor_id !== Auth::user()->vendor->vendor_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:10000',
        ]);

        $item->increment('stock', $request->quantity);

        // Auto-reactivate if it was marked unavailable due to low stock
        if (!$item->is_available) {
            $item->update(['is_available' => 1]);
        }

        return redirect()
            ->route('vendor.products', $request->only(['search', 'status', 'sort', 'dir']))
            ->with('success', "Restocked '{$item->name}' with +{$request->quantity} units.");
    }

    public function edit(Item $item)
    {
        // Ensure the item belongs to the authenticated vendor
        if ($item->vendor_id !== Auth::user()->vendor->vendor_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($item->is_bundle) {
            $products = Item::where('vendor_id', Auth::user()->vendor->vendor_id)
                ->where('is_bundle', 0)
                ->where('is_active', 1)
                ->get();
                
            $bundleItems = $item->bundles->keyBy('item_id');
            return view('pages.vendor-product-edit-bundle', compact('item', 'products', 'bundleItems'));
        }

        return view('pages.vendor-product-edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        // Ensure the item belongs to the authenticated vendor
        if ($item->vendor_id !== Auth::user()->vendor->vendor_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'unit_type'    => 'nullable|string|max:50',
            'unit_value'   => 'nullable|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'price'        => 'required|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        $validated['is_available'] = $request->has('is_available') ? 1 : 0;
        $validated['unit_value'] = $validated['unit_value'] ?? null;

        $item->update($validated);

        return redirect()
            ->route('vendor.products')
            ->with('success', 'Product updated successfully!');
    }

    public function updateBundle(Request $request, Item $item)
    {
        if ($item->vendor_id !== Auth::user()->vendor->vendor_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'bundle_name'   => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'sku'           => 'nullable|string|max:100',
            'is_active'     => 'nullable|boolean',
            'selected_products' => 'required|array|min:1',
        ]);

        $isActive = $request->has('is_active') ? 1 : 0;

        try {
            DB::beginTransaction();

            $item->update([
                'name' => $validated['bundle_name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'sku' => $validated['sku'] ?? null,
                'is_active' => $isActive,
            ]);

            DB::table('bundle_items')->where('bundle_id', $item->item_id)->delete();

            $selectedProducts = $request->input('selected_products');
            $bundleItemsData = [];
            
            foreach ($selectedProducts as $productId => $data) {
                if (isset($data['selected']) && $data['selected'] == '1') {
                    $quantity = isset($data['quantity']) && $data['quantity'] > 0 ? $data['quantity'] : 1;
                    $bundleItemsData[] = [
                        'item_id' => $productId,
                        'bundle_id' => $item->item_id,
                        'quantity' => $quantity,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (empty($bundleItemsData)) {
                throw new \Exception('You must select at least one product for the bundle.');
            }

            DB::table('bundle_items')->insert($bundleItemsData);
            
            DB::commit();

            return redirect()
                ->route('vendor.products')
                ->with('success', 'Bundle updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update bundle: ' . $e->getMessage()])->withInput();
        }
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('pages.vendor-product-add', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'sku'           => 'nullable|string|max:100',
            'brand'         => 'nullable|string|max:100',
            'barcode'       => 'nullable|string|max:100',
            'unit_type'     => 'nullable|string|max:50',
            'unit_value'    => 'nullable|numeric|min:0',
            'is_bundle'     => 'nullable|boolean',
            'is_perishable' => 'nullable|boolean',
            'is_available'  => 'nullable|boolean',
            'is_active'     => 'nullable|boolean',
            'has_expiry'    => 'nullable|boolean',
            'categories'    => 'nullable|array',
            'categories.*'  => 'nullable|integer|exists:categories,category_id',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Convert checkboxes to boolean (1 or 0)
        $validated['is_active']     = $request->has('is_active') ? 1 : 0;
        $validated['is_available']  = $request->has('is_available') ? 1 : 0;
        $validated['is_bundle']     = $request->has('is_bundle') ? 1 : 0;
        $validated['is_perishable'] = $request->has('is_perishable') ? 1 : 0;
        $validated['has_expiry']    = $request->has('has_expiry') ? 1 : 0;

        // Convert empty numeric fields to null
        $validated['unit_value'] = $validated['unit_value'] ?? null;

        // Create the item
        try {
            $item = Item::create(array_merge($validated, [
                'vendor_id' => Auth::user()->vendor->vendor_id,
            ]));
        } catch (\Exception $e) {
            dd('Insert failed', $e->getMessage());
        }

        // Handle images
        $slug = \Illuminate\Support\Str::slug($item->name);
        
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $index => $image) {
                $ext = $image->getClientOriginalExtension();
                $imageName = $slug . ($index > 0 ? '-' . ($index + 1) : '') . '.' . $ext;
                $destination = storage_path('app/public/items/' . $imageName);
                $image->move(dirname($destination), basename($destination));

                ItemImage::create([
                    'item_id' => $item->item_id,
                    'image'   => '/images/items/' . $imageName,
                ]);
            }
        } else {
            ItemImage::create([
                'item_id' => $item->item_id,
                'image'   => '/images/items/' . $slug . '.jpg',
            ]);
        }

        // Attach categories if provided
        $categories = $request->input('categories', []);
        if (!empty($categories)) {
            $item->categories()->sync($categories);
        }

        return redirect()
            ->route('vendor.products')
            ->with('success', 'Item and images uploaded successfully!');
    }

    public function destroy(Item $item)
    {
        // Ensure the item belongs to the authenticated vendor
        if ($item->vendor_id !== Auth::user()->vendor->vendor_id) {
            abort(403, 'Unauthorized action.');
        }

        // Soft-deactivate instead of hard delete (order_items FK references items)
        $item->update([
            'is_active' => 0,
            'is_available' => 0,
        ]);

        return redirect()
            ->route('vendor.products')
            ->with('success', 'Product has been removed successfully.');
    }

    public function createBundle()
    {
        $products = Item::where('vendor_id', Auth::user()->vendor->vendor_id)
            ->where('is_bundle', 0)
            ->where('is_active', 1)
            ->get();
            
        return view('pages.vendor-product-add-bundle', compact('products'));
    }

    public function storeBundle(Request $request)
    {
        $validated = $request->validate([
            'bundle_name'   => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'sku'           => 'nullable|string|max:100',
            'is_active'     => 'nullable|boolean',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'selected_products' => 'required|array|min:1',
        ]);

        $isActive = $request->has('is_active') ? 1 : 0;

        try {
            DB::beginTransaction();

            $bundleItem = Item::create([
                'vendor_id' => Auth::user()->vendor->vendor_id,
                'name' => $validated['bundle_name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'sku' => $validated['sku'] ?? null,
                'is_bundle' => 1,
                'is_active' => $isActive,
                'is_available' => 1,
                'is_perishable' => 0,
                'has_expiry' => 0,
            ]);

            $slug = \Illuminate\Support\Str::slug($bundleItem->name);
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $index => $image) {
                    $ext = $image->getClientOriginalExtension();
                    $imageName = 'bundle-' . $slug . ($index > 0 ? '-' . ($index + 1) : '') . '-' . time() . '.' . $ext;
                    $destination = storage_path('app/public/items/' . $imageName);
                    $image->move(dirname($destination), basename($destination));

                    ItemImage::create([
                        'item_id' => $bundleItem->item_id,
                        'image'   => '/images/items/' . $imageName,
                    ]);
                }
            } else {
                ItemImage::create([
                    'item_id' => $bundleItem->item_id,
                    'image'   => '/images/items/' . $slug . '.jpg',
                ]);
            }

            $selectedProducts = $request->input('selected_products');
            $bundleItemsData = [];
            
            foreach ($selectedProducts as $productId => $data) {
                if (isset($data['selected']) && $data['selected'] == '1') {
                    $quantity = isset($data['quantity']) && $data['quantity'] > 0 ? $data['quantity'] : 1;
                    $bundleItemsData[] = [
                        'item_id' => $productId,
                        'bundle_id' => $bundleItem->item_id,
                        'quantity' => $quantity,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (empty($bundleItemsData)) {
                throw new \Exception('You must select at least one product for the bundle.');
            }

            DB::table('bundle_items')->insert($bundleItemsData);
            
            DB::commit();

            return redirect()
                ->route('vendor.products')
                ->with('success', 'Bundle created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create bundle: ' . $e->getMessage()])->withInput();
        }
    }
}