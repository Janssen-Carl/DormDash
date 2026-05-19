<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;
        
        $products = collect();
        if ($vendor) {
            $products = Item::where('vendor_id', $vendor->vendor_id)
                ->with(['images'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('pages.vendor-products', compact('products'));
    }

    public function create()
    {
        return view('pages.vendor.add-item');
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

        return redirect() // add popup or whatever

            ->route('vendor.products')
            ->with('success', 'Item and images uploaded successfully!');
    }
}
