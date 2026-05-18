<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'vendor_id',
        'price',
        'name',
        'description',
        'is_active',
        'stock',
        'sku',
        'is_bundle',
        'unit_type',
        'unit_value',
        'brand',
        'barcode',
        'is_perishable',
        'is_available',
        'has_expiry',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'unit_value' => 'decimal:2',
        'is_active' => 'boolean',
        'is_bundle' => 'boolean',
        'is_perishable' => 'boolean',
        'is_available' => 'boolean',
        'has_expiry' => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    // Return all images for this item
    public function images()
    {
        return $this->hasMany(ItemImage::class, 'item_id');
    }

    // Shortcut to get the first image (with fallback)
    public function firstImage()
    {
        return $this->images()->first()?->image ?? '/images/items/1/1.jpg';
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class, 'item_id');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class, 'item_id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_items',
            'item_id',
            'category_id'
        );
    }

    public function pages()
    {
        return $this->belongsToMany(
            Page::class,
            'page_items',
            'item_id',
            'page_id'
        )->withPivot('display_order');
    }

    public function bundles()
    {
        return $this->belongsToMany(
            Item::class,
            'bundle_items',
            'bundle_id',
            'item_id'
        )->withPivot('quantity');
    }
}
