<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plan extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'name',
        'name_en',
        'name_ar',
        'price_month',
        'price_year',
        'slug',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
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
        return encodedData([
            'slug' => $this->slug,
            'period' => $period ?? 'month',
        ]);
    }

    static function decodedSlug($encoded)
    {
        return decodedData($encoded);
    }

    public function localizedName(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return $locale === 'ar'
            ? ($this->name_ar ?: $this->name)
            : ($this->name_en ?: $this->name);
    }
}
