<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterRequest extends Model
{
    protected $fillable = [
        'data',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
