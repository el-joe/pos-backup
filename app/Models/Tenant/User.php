<?php

namespace App\Models\Tenant;

use App\Enums\UserTypeEnum;
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
        'type' => UserTypeEnum::class,
    ];
}
