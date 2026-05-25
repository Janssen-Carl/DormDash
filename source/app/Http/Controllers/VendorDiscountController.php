<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Item;
use Illuminate\Http\Request;

class VendorDiscountController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $vendorId = auth()->user()->vendor->vendor_id ?? null;

        if (!$vendorId) {
            return redirect()->route('vendor.home')->withErrors(['msg' => 'Vendor profile not found.']);
        }

        // Fetch discounts for items belonging to this vendor
        $discounts = Discount::whereHas('item', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->with('item')->get();

        return view('pages.vendor-discounts', compact('discounts'));
    }

    public function create(Request $request)
    {
        $vendorId = auth()->user()->vendor->vendor_id ?? null;

        if (!$vendorId) {
            return redirect()->route('vendor.home')->withErrors(['msg' => 'Vendor profile not found.']);
        }

        // Only fetch available, active items belonging to this vendor
        $items = Item::where('vendor_id', $vendorId)
            ->where('is_active', true)
            ->get();

        $selectedItemId = $request->query('item_id');

        return view('pages.vendor-discounts-create', compact('items', 'selectedItemId'));
    }

    public function store(Request $request)
    {
        $vendorId = auth()->user()->vendor->vendor_id ?? null;

        if (!$vendorId) {
            return redirect()->route('vendor.home')->withErrors(['msg' => 'Vendor profile not found.']);
        }

        $request->validate([
            'item_id' => 'required|exists:items,item_id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'percentage' && $value > 100) {
                        $fail('Percentage discount cannot exceed 100%.');
                    }
                }
            ],
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'use_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        // Secure that item belongs to this vendor
        $item = Item::where('vendor_id', $vendorId)->findOrFail($request->item_id);

        Discount::create([
            'item_id' => $item->item_id,
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'use_limit' => $request->use_limit,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('vendor.discounts')->with('success', 'Promotion created successfully!');
    }

    public function edit($discountId)
    {
        $vendorId = auth()->user()->vendor->vendor_id ?? null;

        if (!$vendorId) {
            return redirect()->route('vendor.home')->withErrors(['msg' => 'Vendor profile not found.']);
        }

        $discount = Discount::whereHas('item', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->findOrFail($discountId);

        $items = Item::where('vendor_id', $vendorId)
            ->where('is_active', true)
            ->get();

        return view('pages.vendor-discounts-edit', compact('discount', 'items'));
    }

    public function update(Request $request, $discountId)
    {
        $vendorId = auth()->user()->vendor->vendor_id ?? null;

        if (!$vendorId) {
            return redirect()->route('vendor.home')->withErrors(['msg' => 'Vendor profile not found.']);
        }

        $discount = Discount::whereHas('item', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->findOrFail($discountId);

        $request->validate([
            'item_id' => 'required|exists:items,item_id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'percentage' && $value > 100) {
                        $fail('Percentage discount cannot exceed 100%.');
                    }
                }
            ],
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'use_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        // Secure item ownership
        $item = Item::where('vendor_id', $vendorId)->findOrFail($request->item_id);

        $discount->update([
            'item_id' => $item->item_id,
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'use_limit' => $request->use_limit,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        return redirect()->route('vendor.discounts')->with('success', 'Promotion updated successfully!');
    }

    public function destroy($discountId)
    {
        $vendorId = auth()->user()->vendor->vendor_id ?? null;

        if (!$vendorId) {
            return redirect()->route('vendor.home')->withErrors(['msg' => 'Vendor profile not found.']);
        }

        $discount = Discount::whereHas('item', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->findOrFail($discountId);

        $discount->delete();

        return redirect()->route('vendor.discounts')->with('success', 'Promotion deleted successfully!');
    }
}
