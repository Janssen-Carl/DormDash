<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $table = 'stock_logs';
    protected $primaryKey = 'log_id';

    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'old_stock',
        'new_stock',
        'quantity_changed',
        'remarks',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
