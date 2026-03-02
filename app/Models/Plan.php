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
        'tier',
        'module_name',
        'price_month',
        'price_year',
        'three_months_free',
        'slug',
        'active',
        'recommended',
        'sort_order',
    ];

    protected $casts = [
        'module_name' => ModulesEnum::class,
        'three_months_free' => 'boolean',
        'active' => 'boolean',
        'recommended' => 'boolean',
        'sort_order' => 'integer',
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

    public function planFeatures()
    {
        return $this->hasMany(PlanFeature::class, 'plan_id')->with('feature');
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

    public function featureContent(string $code, string $default = '', ?string $locale = null): string
    {
        $featureId = $this->resolveFeatureId($code);
        if (!$featureId) {
            return $default;
        }

        $locale = $locale ?: app()->getLocale();
        $column = $locale === 'ar' ? 'content_ar' : 'content_en';
        $content = $this->plan_features()->where('feature_id', $featureId)->value($column);
        return is_string($content) ? $content : $default;
    }

    public function getFeaturesAttribute(): array
    {
        if ($this->relationLoaded('planFeatures')) {
            $rows = $this->getRelation('planFeatures');
        } elseif ($this->relationLoaded('plan_features')) {
            $rows = $this->getRelation('plan_features');
        } else {
            $rows = $this->planFeatures()->get();
        }

        $features = [];
        foreach ($rows as $row) {
            $feature = $row->feature;
            if (!$feature) {
                continue;
            }

            $code = (string) $feature->code;
            $content = app()->getLocale() === 'ar'
                ? ($row->content_ar ?? $row->content_en)
                : ($row->content_en ?? $row->content_ar);

            $features[$code] = [
                'status' => ((int) $row->value) > 0,
                'description' => is_string($content) && trim($content) !== '' ? trim($content) : null,
                'limit' => (int) $row->value,
            ];
        }

        return $features;
    }

    private function resolveFeatureId(string $code): ?int
    {
        $code = $this->normalizeFeatureCode($code);
        if ($code === '') {
            return null;
        }

        return Cache::remember('features.id_by_code.' . $code, 3600, function () use ($code) {
            return Feature::query()->where('code', $code)->value('id');
        });
    }

    private function normalizeFeatureCode(string $code): string
    {
        $code = trim($code);

        $aliases = [
            'branches' => 'erp_branches',
            'products' => 'erp_products',
            'admins' => 'erp_admins',
            'inventory' => 'erp_inventory',
            'sales' => 'erp_sales',
            'double_entry_accounting' => 'erp_accounting',
            'advanced_reports' => 'erp_accounting',
            'basic_reports' => 'erp_sales',
            'discounts' => 'erp_sales',
            'taxes' => 'erp_accounting',
        ];

        return $aliases[$code] ?? $code;
    }
}
