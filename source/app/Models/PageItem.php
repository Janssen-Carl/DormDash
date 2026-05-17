<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageItem extends Model
{
    protected $table = 'page_items';

    protected $fillable = [
        'item_id',
        'page_id',
        'display_order',
    ];
}
