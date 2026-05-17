<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    protected $table = 'category_items';

    protected $fillable = [
        'item_id',
        'category_id',
    ];
}
