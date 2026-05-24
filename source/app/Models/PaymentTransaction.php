<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $table = 'payment_transactions';
    protected $primaryKey = 'order_id';
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'amount',
        'status',
        'payment_method',
        'reference_no',
        'token',
        'acc_last4_no',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
