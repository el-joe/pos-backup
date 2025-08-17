<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'active',
        'type',
        'website',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
