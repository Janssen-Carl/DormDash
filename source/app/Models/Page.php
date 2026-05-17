<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'page_id';

    protected $fillable = [
        'vendor_id',
        'title',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function items()
    {
        return $this->belongsToMany(
            Item::class,
            'page_items',
            'page_id',
            'item_id'
        )->withPivot('display_order');
    }
}
