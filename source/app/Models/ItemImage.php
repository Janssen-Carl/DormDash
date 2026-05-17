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

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
