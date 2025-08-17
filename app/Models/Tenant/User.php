<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'active',
        'type',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
