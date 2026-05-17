<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'vendor_id';
    public $incrementing = false;

    protected $fillable = [
        'vendor_id',
        'name',
        'email',
        'phone',
        'website',
        'address_id',
        'active',
        'cover_img',
        'profile_img',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'vendor_id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'vendor_id');
    }
}
