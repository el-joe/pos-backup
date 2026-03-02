<?php

namespace App\Livewire\Central\Site;

use App\Models\Feature;
use App\Models\Plan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.central.gemini.layout')]
class PricingPage extends Component
{
    public string $billingPeriod = 'monthly';

    public array $plans = [];

    public ?int $selectedPlanId = null;

    public function mount(): void
    {
        $plans = Plan::query()
            ->active()
            ->with(['plan_features.feature' => function ($query) {
                $query->where('active', true);
            }])
            ->orderBy('sort_order')
            ->orderByDesc('recommended')
            ->orderBy('price_month')
            ->orderBy('id')
            ->limit(3)
            ->get();

        $features = Feature::query()
            ->where('active', true)
            ->orderBy('id')
            ->get();

        $locale = app()->getLocale();

        $this->plans = $plans->map(function (Plan $plan) use ($features, $locale) {
            $rows = $features->map(function (Feature $feature) use ($plan, $locale) {
                $planFeature = $plan->plan_features->firstWhere('feature_id', $feature->id);

                $featureName = $feature->{'name_' . $locale} ?? $feature->name_en ?? $feature->name_ar ?? '';
                $featureType = (string) ($feature->type ?? 'boolean');
                $value = (int) ($planFeature->value ?? 0);

                $content = $locale === 'ar'
                    ? ($planFeature->content_ar ?? $planFeature->content_en ?? null)
                    : ($planFeature->content_en ?? $planFeature->content_ar ?? null);
                $content = is_string($content) ? trim($content) : null;

                $displayValue = null;
                if ($featureType === 'text') {
                    $displayValue = ($content !== null && $content !== '')
                        ? $content
                        : ($value > 0 ? (string) $value : '—');
                }

                return [
                    'name' => $featureName,
                    'type' => $featureType,
                    'enabled' => $value > 0,
                    'display_value' => $displayValue,
                ];
            })->values()->all();

            $tier = strtolower((string) ($plan->tier ?? ''));

            return [
                'id' => $plan->id,
                'slug' => $plan->slug,
                'name' => $plan->name,
                'tier' => $tier,
                'month' => round((float) $plan->price_month, 2),
                'year' => round((float) $plan->price_year, 2),
                'trial_months' => (bool) ($plan->three_months_free ?? false) ? 3 : 0,
                'recommended' => (bool) $plan->recommended,
                'features' => $rows,
            ];
        })->values()->all();

        $defaultPlan = collect($this->plans)->firstWhere('recommended', true) ?? (collect($this->plans)->first() ?? null);
        $this->selectedPlanId = isset($defaultPlan['id']) ? (int) $defaultPlan['id'] : null;
    }

    public function setBilling(string $period): void
    {
        $this->billingPeriod = $period === 'yearly' ? 'yearly' : 'monthly';
    }

    public function setPlan(int $planId): void
    {
        $exists = collect($this->plans)->contains(fn (array $plan) => (int) $plan['id'] === $planId);
        if (!$exists) {
            return;
        }

        $this->selectedPlanId = $planId;
    }

    public function proceedToCheckout()
    {
        $plan = $this->selectedPlan();
        if (!$plan) {
            return redirect()->route('pricing-compare');
        }

        $payload = [
            'period' => $this->isYearly() ? 'year' : 'month',
            'plan_id' => $plan['id'],
            'slug' => $plan['slug'],
            'systems_allowed' => ['pos'],
            'systems' => $this->selectedPlansPayload(),
        ];

        $token = encodedData($payload);
        return redirect()->route('tenant-checkout', ['token' => $token]);
    }

    public function isYearly(): bool
    {
        return $this->billingPeriod === 'yearly';
    }

    public function selectedPlan(): ?array
    {
        $selectedPlanId = (int) $this->selectedPlanId;
        if ($selectedPlanId <= 0) {
            return null;
        }

        return collect($this->plans)->first(fn (array $plan) => (int) $plan['id'] === $selectedPlanId);
    }

    public function selectedCount(): int
    {
        return $this->selectedPlan() ? 1 : 0;
    }

    public function selectedPlansPayload(): array
    {
        $plan = $this->selectedPlan();
        if (!$plan) {
            return [];
        }

        return [[
            'module' => 'pos',
            'plan_id' => $plan['id'],
            'slug' => $plan['slug'],
            'name' => $plan['name'],
            'trial_months' => (int) ($plan['trial_months'] ?? 0),
        ]];
    }

    public function totalPrice(): float
    {
        $plan = $this->selectedPlan();
        if (!$plan) {
            return 0.0;
        }

        return round((float) ($this->isYearly() ? $plan['year'] : $plan['month']), 2);
    }

    public function dueNow(): float
    {
        $plan = $this->selectedPlan();
        if (!$plan) {
            return 0.0;
        }

        if ((int) ($plan['trial_months'] ?? 0) > 0) {
            return 0.0;
        }

        return round((float) ($this->isYearly() ? $plan['year'] : $plan['month']), 2);
    }

    public function hasTrialSelection(): bool
    {
        $plan = $this->selectedPlan();
        return $plan ? ((int) ($plan['trial_months'] ?? 0) > 0) : false;
    }

    public function render()
    {
        return view('livewire.central.site.pricing-page');
    }
}
