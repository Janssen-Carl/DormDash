<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'address_id';

    protected $fillable = [
        'user_id',
        'street',
        'city',
        'province_state',
        'postal_code',
        'phone',
        'email',
        'country',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
