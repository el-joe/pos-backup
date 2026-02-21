<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingCompany extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'active',
        'type',
        'website',
        'deleted_at'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
