<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CusBankingInfo extends Model
{
    protected $table = 'cus_banking_info';
    protected $primaryKey = 'banking_id';

    protected $fillable = [
        'customer_id',
        'payment_method',
        'provider',
        'account_name',
        'phone_number',
        'email',
        'token',
        'acc_last4_no',
        'account_type',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
