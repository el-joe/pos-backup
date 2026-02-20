<?php

namespace App\Livewire\Central\Site;

use App\Models\Feature;
use App\Models\Plan;
use App\Services\PlanFeaturePresentationService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.central.gemini.layout')]
class PricingPage extends Component
{
    public string $billingPeriod = 'monthly';

    public array $moduleOrder = ['pos', 'hrm', 'booking'];

    public array $moduleUi = [
        'pos' => [
            'title' => 'POS & ERP System',
            'description' => 'Inventory, sales, and comprehensive accounting.',
            'icon' => 'fa-solid fa-boxes-stacked',
            'headerColor' => 'indigo',
            'featureColor' => 'indigo',
        ],
        'hrm' => [
            'title' => 'HRM System',
            'description' => 'Payroll, attendance, and team management.',
            'icon' => 'fa-solid fa-users',
            'headerColor' => 'emerald',
            'featureColor' => 'emerald',
        ],
        'booking' => [
            'title' => 'Booking & Reservations',
            'description' => 'Smart scheduling, calendars, and reminders.',
            'icon' => 'fa-solid fa-calendar-check',
            'headerColor' => 'rose',
            'featureColor' => 'rose',
        ],
    ];

    public array $plansByModule = [];
    public array $selectedSystems = [];
    public array $selectedPlans = [];

    public function mount(): void
    {
        $plans = Plan::query()
            ->active()
            ->with(['plan_features.feature' => function ($query) {
                $query->where('active', true);
            }])
            ->orderBy('price_month')
            ->orderBy('id')
            ->get();

        $features = Feature::query()
            ->where('active', true)
            ->orderBy('id')
            ->get()
            ->groupBy('module_name');

        $locale = app()->getLocale();

        foreach ($this->moduleOrder as $module) {
            $modulePlans = $plans
                ->filter(fn (Plan $plan) => (is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name) === $module)
                ->values();

            $plansPayload = $modulePlans->map(function (Plan $plan) use ($features, $module, $locale) {
                // $featureNames = app(PlanFeaturePresentationService::class)
                //     ->resolveDisplayPlanFeatureNames($plan, ($features[$module] ?? collect()), $locale, 3);

                $features = collect($features[$module] ?? collect())->map(function($feature) use ($plan, $locale){
                    $planFeature = $plan->plan_features->firstWhere('feature_id', $feature->id);

                    $title = $feature->{'name_'. $locale} ?? $feature->name_en ?? $feature->name_ar ?? '';
                    if($feature->type == 'text'){
                        $content = $planFeature->{'content_'. $locale} ?? $planFeature->content_en ?? '';
                        return "$title: $content";
                    }else{
                        $icon = $planFeature->value == 1 ? 'fa-solid fa-check text-green-500' : 'fa-solid fa-xmark text-red-500';
                        return "<i class='$icon'></i> $title";
                    }
                });

                return [
                    'id' => $plan->id,
                    'slug' => $plan->slug,
                    'name' => $plan->name,
                    'month' => round((float) $plan->price_month, 2),
                    'year' => round((float) $plan->price_year, 2),
                    'trial_months' => (bool) ($plan->three_months_free ?? false) ? 3 : 0,
                    'recommended' => (bool) $plan->recommended,
                    'features' => $features
                ];
            })->values()->all();

            $this->plansByModule[$module] = $plansPayload;
            $defaultPlan = collect($plansPayload)->firstWhere('recommended', true) ?? (collect($plansPayload)->first() ?? null);
            $this->selectedPlans[$module] = $defaultPlan['id'] ?? null;
            $this->selectedSystems[$module] = false;
        }
    }

    public function setBilling(string $period): void
    {
        $this->billingPeriod = $period === 'yearly' ? 'yearly' : 'monthly';
    }

    public function toggleSystem(string $module): void
    {
        if (!array_key_exists($module, $this->selectedSystems)) {
            return;
        }

        $this->selectedSystems[$module] = !$this->selectedSystems[$module];
    }

    public function setTier(string $module, int $planId): void
    {
        if (!array_key_exists($module, $this->selectedPlans)) {
            return;
        }

        $exists = collect($this->plansByModule[$module] ?? [])->contains(fn (array $plan) => (int) $plan['id'] === $planId);
        if (!$exists) {
            return;
        }

        $this->selectedPlans[$module] = $planId;
    }

    public function proceedToCheckout()
    {
        if ($this->selectedCount() === 0) {
            return redirect()->route('pricing-compare');
        }

        $payload = [
            'period' => $this->isYearly() ? 'year' : 'month',
            'systems' => $this->selectedPlansPayload(),
        ];

        $token = encodedData($payload);
        return redirect()->route('tenant-checkout', ['token' => $token]);
    }

    public function isYearly(): bool
    {
        return $this->billingPeriod === 'yearly';
    }

    public function selectedPlan(string $module): ?array
    {
        $selectedPlanId = (int) ($this->selectedPlans[$module] ?? 0);
        if ($selectedPlanId <= 0) {
            return null;
        }

        return collect($this->plansByModule[$module] ?? [])->first(fn (array $plan) => (int) $plan['id'] === $selectedPlanId);
    }

    public function selectedCount(): int
    {
        return count(array_filter($this->selectedSystems, fn ($selected) => (bool) $selected));
    }

    public function selectedPlansPayload(): array
    {
        $payload = [];
        foreach ($this->moduleOrder as $module) {
            if (empty($this->selectedSystems[$module])) {
                continue;
            }

            $plan = $this->selectedPlan($module);
            if (!$plan) {
                continue;
            }

            $payload[] = [
                'module' => $module,
                'plan_id' => $plan['id'],
                'slug' => $plan['slug'],
                'name' => $plan['name'],
                'trial_months' => (int) ($plan['trial_months'] ?? 0),
            ];
        }

        return $payload;
    }

    public function totalPrice(): float
    {
        $total = 0.0;
        foreach ($this->moduleOrder as $module) {
            if (empty($this->selectedSystems[$module])) {
                continue;
            }

            $plan = $this->selectedPlan($module);
            if (!$plan) {
                continue;
            }

            $total += (float) ($this->isYearly() ? $plan['year'] : $plan['month']);
        }

        return round($total, 2);
    }

    public function dueNow(): float
    {
        $total = 0.0;
        foreach ($this->moduleOrder as $module) {
            if (empty($this->selectedSystems[$module])) {
                continue;
            }

            $plan = $this->selectedPlan($module);
            if (!$plan) {
                continue;
            }

            if ((int) ($plan['trial_months'] ?? 0) > 0) {
                continue;
            }

            $total += (float) ($this->isYearly() ? $plan['year'] : $plan['month']);
        }

        return round($total, 2);
    }

    public function hasTrialSelection(): bool
    {
        foreach ($this->moduleOrder as $module) {
            if (empty($this->selectedSystems[$module])) {
                continue;
            }

            $plan = $this->selectedPlan($module);
            if ($plan && ((int) ($plan['trial_months'] ?? 0) > 0)) {
                return true;
            }
        }

        return false;
    }

    public function render()
    {
        return view('livewire.central.site.pricing-page');
    }
}
