<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    protected $table = 'item_images';
    protected $primaryKey = 'item_image_id';

    protected $fillable = [
        'item_id',
        'image',
    ];

    /**
     * Fallback to the default image if the specific product image doesn't exist on disk.
     */
    public function getImageAttribute($value)
    {
        if ($value && file_exists(public_path($value))) {
            return $value;
        }
        return '/images/items/1/1.jpg';
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
