<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes,HasFactory;

    protected $connection = 'central';

    protected $fillable = [
        'name',
        'icon_path',
        'provider',
        'manual',
        'credentials',
        'required_fields',
        'details',
        'fee_percentage',
        'fixed_fee',
        'active',
    ];

    protected $casts = [
        'manual' => 'boolean',
        'credentials' => 'array',
        'required_fields' => 'array',
        'details' => 'array',
        'active' => 'boolean',
    ];

    // Scopes
    function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
