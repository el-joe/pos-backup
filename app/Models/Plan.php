<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plan extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'name',
        'price_month',
        'price_year',
        'features',
        'slug',
        'active',
        'recommended'
    ];

    protected $casts = [
        'features' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($plan) {
            if (empty($plan->slug)) {
                $plan->slug = Str::slug($plan->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    function encodedSlug($period = null)
    {
        $newSlug = encodedData([
            'slug' => $this->slug,
            'period' => $period ?? 'month',
        ]);

        return $newSlug;
    }

    static function decodedSlug($encoded)
    {
        return decodedData($encoded);
    }
}
