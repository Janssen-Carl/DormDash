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

    /**
     * Fallback to the default image if the specific cover image doesn't exist on disk.
     */
    public function getCoverImgAttribute($value)
    {
        if ($value && file_exists(public_path($value))) {
            return $value;
        }
        return '/images/items/1/1.jpg';
    }

    /**
     * Fallback to the default image if the specific profile image doesn't exist on disk.
     */
    public function getProfileImgAttribute($value)
    {
        if ($value && file_exists(public_path($value))) {
            return $value;
        }
        return '/images/items/1/1.jpg';
    }

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
