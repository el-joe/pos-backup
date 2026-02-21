<?php

namespace App\Services;

use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Support\Collection;

class PlanFeaturePresentationService
{
    public function resolveDisplayPlanFeatureNames(Plan $plan, Collection $moduleFeatures, string $locale, int $take = 3): array
    {
        $featureNames = $this->resolvePlanFeatureNames($plan, $locale, $take);

        if (count($featureNames) === 0) {
            return $this->resolveModuleFallbackFeatureNames($moduleFeatures, $locale, $take);
        }

        return $featureNames;
    }

    public function resolvePlanFeatureNames(Plan $plan, string $locale, int $take = 3): array
    {
        return $plan->plan_features
            ->filter(function ($planFeature) {
                if (!$planFeature->feature) {
                    return false;
                }

                if ($planFeature->feature->type === 'boolean') {
                    return (int) $planFeature->value === 1;
                }

                return ((int) $planFeature->value > 0)
                    || (is_string($planFeature->content_en) && trim($planFeature->content_en) !== '')
                    || (is_string($planFeature->content_ar) && trim($planFeature->content_ar) !== '');
            })
            ->sortBy('feature_id')
            ->map(function ($planFeature) use ($locale) {
                $feature = $planFeature->feature;
                $name = $locale === 'ar' ? ($feature->name_ar ?? null) : ($feature->name_en ?? null);
                return $name ?: ($feature->name_en ?: $feature->code);
            })
            ->unique()
            ->values()
            ->take($take)
            ->all();
    }

    public function resolveModuleFallbackFeatureNames(Collection $moduleFeatures, string $locale, int $take = 3): array
    {
        return $moduleFeatures
            ->take($take)
            ->map(function (Feature $feature) use ($locale) {
                $name = $locale === 'ar' ? ($feature->name_ar ?? null) : ($feature->name_en ?? null);
                return $name ?: ($feature->name_en ?: $feature->code);
            })
            ->values()
            ->all();
    }
}
