<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    /**
     * Show create form
     */
    public function create()
    {
        return view('pages.vendor.add-item');
    }

    /**
     * Store item
     */
    public function store(Request $request)
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

            'is_bundle'     => 'required|boolean',
            'is_perishable' => 'nullable|boolean',
            'is_available'  => 'nullable|boolean',
            'has_expiry'    => 'nullable|boolean',
        ]);

        $item = Item::create([
            'vendor_id'     => Auth::id(),
            'name'          => $validated['name'],
            'description'   => $validated['description'] ?? null,
            'price'         => $validated['price'],
            'stock'         => $validated['stock'],
            'sku'           => $validated['sku'] ?? null,
            'brand'         => $validated['brand'] ?? null,
            'barcode'       => $validated['barcode'] ?? null,
            'unit_type'     => $validated['unit_type'] ?? null,
            'unit_value'    => $validated['unit_value'] ?? null,

            'is_bundle'     => $validated['is_bundle'],
            'is_perishable' => $validated['is_perishable'] ?? false,
            'is_available'  => $validated['is_available'] ?? true,
            'has_expiry'    => $validated['has_expiry'] ?? false,

            'is_active'     => true,
        ]);

        return redirect()
            ->route('vendor.items.create')
            ->with('success', 'Item created successfully!');
    }
}
