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
        'gateway_type',
        'is_active',
        'manual',
        'currency_id',
        'credentials',
        'required_fields',
        'details',
        'supported_countries',
        'fee_percentage',
        'fixed_fee',
        'active',
    ];

    protected $casts = [
        'manual' => 'boolean',
        'credentials' => 'array',
        'required_fields' => 'array',
        'details' => 'array',
        'supported_countries' => 'array',
        'active' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'credentials',
    ];

    // Scopes
    function scopeActive($query)
    {
        return $query->where('active', true);
    }

    function scopeForCountry($query, $countryId)
    {
        return $query->where(function ($q) use ($countryId) {
            $q->whereNull('supported_countries')
                ->orWhereJsonContains('supported_countries', $countryId)
                ->orWhereJsonContains('supported_countries', (string) $countryId);
        });
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
