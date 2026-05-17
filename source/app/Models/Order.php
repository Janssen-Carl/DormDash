<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'customer_id',
        'address_id',
        'shipping_method',
        'order_total',
        'send_date',
        'receive_date',
        'order_status',
        'tracking_number',
    ];

    protected $casts = [
        'order_total' => 'decimal:2',
        'send_date' => 'date',
        'receive_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function items()
    {
        return $this->belongsToMany(
            Item::class,
            'order_items',
            'order_id',
            'item_id'
        )->withPivot('quantity', 'price');
    }

    public function paymentTransaction()
    {
        return $this->hasOne(PaymentTransaction::class, 'order_id');
    }
}
