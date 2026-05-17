<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    public $incrementing = false;

    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'phone',
        'birthdate',
        'gender',
        'profile_img',
        'primary_address_id',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function primaryAddress()
    {
        return $this->belongsTo(Address::class, 'primary_address_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
