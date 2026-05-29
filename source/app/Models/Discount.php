<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    protected $primaryKey = 'discount_id';

    protected $fillable = [
        'item_id',
        'date_end',
        'date_start',
        'type',
        'value',
        'use_limit',
        'redemption_count',
        'description',
        'name',
        'is_active',
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime',
        'value' => 'decimal:2',
        'is_active' => 'boolean',
        'redemption_count' => 'integer',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function scopeUsable($query)
    {
        return $query->where('is_active', true)
            ->where('date_start', '<=', now())
            ->where('date_end', '>=', now())
            ->where(function ($q) {
                $q->whereNull('use_limit')
                  ->orWhereColumn('redemption_count', '<', 'use_limit');
            });
    }
}
