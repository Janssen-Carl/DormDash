<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        $products = collect();
        if ($vendor) {
            $query = Item::where('vendor_id', $vendor->vendor_id)->with(['images']);

            // Status filter
            $status = request('status', 'active');
            if ($status === 'active') {
                $query->where('is_active', 1);
            } elseif ($status === 'inactive') {
                $query->where('is_active', 0);
            }
            // 'all' shows everything

            // Search
            $search = request('search');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            }

            // Sorting
            $sortBy = request('sort', 'created_at');
            $sortDir = request('dir', 'desc');
            $allowed = ['created_at', 'stock', 'price', 'is_available'];
            if (!in_array($sortBy, $allowed)) {
                $sortBy = 'created_at';
            }

            $products = $query->orderBy($sortBy, $sortDir)->get();
        }

        return view('pages.vendor-products', compact('products', 'sortBy', 'sortDir', 'status', 'search'));
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
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $destination = storage_path('app/public/items/' . $imageName);
                $image->move(dirname($destination), basename($destination));

                ItemImage::create([
                    'item_id' => $item->item_id,
                    'image'   => 'items/' . $imageName,
                ]);
            }
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

    public function edit($id)
    {
        $vendor = Auth::user()->vendor;
        $item = Item::where('vendor_id', $vendor->vendor_id)->with('images')->findOrFail($id);
        return view('pages.vendor-product-edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Auth::user()->vendor;
        $item = Item::where('vendor_id', $vendor->vendor_id)->findOrFail($id);

        $validated = $request->validate([
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'unit_value'  => 'nullable|numeric|min:0',
            'unit_type'   => 'nullable|string|max:50',
            'is_available'=> 'nullable|boolean',
        ]);

        $item->price = $validated['price'];
        $item->stock = $validated['stock'];
        $item->unit_value = $validated['unit_value'] ?? $item->unit_value;
        $item->unit_type = $validated['unit_type'] ?? $item->unit_type;
        $item->is_available = $request->has('is_available') ? 1 : 0;
        $item->save();

        return redirect()->route('vendor.products')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $vendor = Auth::user()->vendor;
        $item = Item::where('vendor_id', $vendor->vendor_id)->findOrFail($id);
        $item->delete();

        return redirect()->route('vendor.products')->with('success', 'Product deleted successfully.');
    }

    public function restock(Request $request, $id)
    {
        $vendor = Auth::user()->vendor;
        $item = Item::where('vendor_id', $vendor->vendor_id)->findOrFail($id);

        $request->validate(['quantity' => 'required|integer|min:1']);
        $item->stock += $request->input('quantity');
        $item->save();

        return redirect()->route('vendor.products')->with('success', 'Stock updated successfully.');
    }

    public function createBundle()
    {
        $categories = Category::where('is_active', true)->get();
        return view('pages.vendor-product-add-bundle', compact('categories'));
    }

    public function storeBundle(Request $request)
    {
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
            'categories'    => 'nullable|array',
            'categories.*'  => 'nullable|integer|exists:categories,category_id',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_bundle'] = 1;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['is_available'] = $request->has('is_available') ? 1 : 0;
        $validated['is_perishable'] = $request->has('is_perishable') ? 1 : 0;
        $validated['has_expiry'] = $request->has('has_expiry') ? 1 : 0;
        $validated['unit_value'] = $validated['unit_value'] ?? null;

        $item = Item::create(array_merge($validated, [
            'vendor_id' => Auth::user()->vendor->vendor_id,
        ]));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $destination = storage_path('app/public/items/' . $imageName);
                $image->move(dirname($destination), basename($destination));

                ItemImage::create([
                    'item_id' => $item->item_id,
                    'image'   => 'items/' . $imageName,
                ]);
            }
        }

        $categories = $request->input('categories', []);
        if (!empty($categories)) {
            $item->categories()->sync($categories);
        }

        return redirect()->route('vendor.products')->with('success', 'Bundle created successfully.');
    }

    public function updateBundle(Request $request, $id)
    {
        $vendor = Auth::user()->vendor;
        $item = Item::where('vendor_id', $vendor->vendor_id)->findOrFail($id);

        $validated = $request->validate([
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'unit_value'  => 'nullable|numeric|min:0',
            'unit_type'   => 'nullable|string|max:50',
            'is_available'=> 'nullable|boolean',
        ]);

        $item->price = $validated['price'];
        $item->stock = $validated['stock'];
        $item->unit_value = $validated['unit_value'] ?? $item->unit_value;
        $item->unit_type = $validated['unit_type'] ?? $item->unit_type;
        $item->is_available = $request->has('is_available') ? 1 : 0;
        $item->save();

        return redirect()->route('vendor.products')->with('success', 'Bundle updated successfully.');
    }
}
