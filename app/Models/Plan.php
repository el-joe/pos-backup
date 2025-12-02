<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    function encodedSlug($period = null)
    {
        $newSlug = encodedSlug([
            'slug'=>$this->slug,
            'period'=>$period ?? 'month',
        ]);

        return $newSlug;
    }

    static function decodedSlug($encoded)
    {
        return decodedSlug($encoded);
    }
}
