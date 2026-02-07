<?php

namespace App\Models;

use App\Enums\ModulesEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Plan extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'name',
        'module_name',
        'price_month',
        'price_year',
        'slug',
        'active',
        'recommended'
    ];

    protected $casts = [
        'module_name' => ModulesEnum::class,
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

    public function plan_features()
    {
        return $this->hasMany(PlanFeature::class, 'plan_id');
    }

    public function featureValue(string $code, int $default = 0): int
    {
        $featureId = $this->resolveFeatureId($code);
        if (!$featureId) {
            return $default;
        }

        $value = $this->plan_features()->where('feature_id', $featureId)->value('value');
        return is_numeric($value) ? (int) $value : $default;
    }

    public function featureContent(string $code, string $default = ''): string
    {
        $featureId = $this->resolveFeatureId($code);
        if (!$featureId) {
            return $default;
        }

        $content = $this->plan_features()->where('feature_id', $featureId)->value('content');
        return is_string($content) ? $content : $default;
    }

    private function resolveFeatureId(string $code): ?int
    {
        $code = trim($code);
        if ($code === '') {
            return null;
        }

        return Cache::remember('features.id_by_code.' . $code, 3600, function () use ($code) {
            return Feature::query()->where('code', $code)->value('id');
        });
    }
}
