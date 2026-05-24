<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $table = 'admin_logs';

    protected $fillable = [
        'admin_id',
        'action',
        'target_user_id',
        'target_username',
        'target_role',
        'notes',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }

    /**
     * Human-readable action label.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'approved_vendor'    => 'Approved Vendor',
            'deleted_vendor'     => 'Deleted Vendor',
            'deleted_customer'   => 'Deleted Customer',
            'suspended_vendor'   => 'Suspended Vendor',
            'suspended_customer' => 'Suspended Customer',
            default              => ucwords(str_replace('_', ' ', $this->action)),
        };
    }

    /**
     * CSS badge color class based on action.
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'approved_vendor'    => 'bg-emerald-100 text-emerald-700',
            'deleted_vendor',
            'deleted_customer'   => 'bg-red-100 text-red-700',
            'suspended_vendor',
            'suspended_customer' => 'bg-amber-100 text-amber-700',
            default              => 'bg-gray-100 text-gray-700',
        };
    }
}
