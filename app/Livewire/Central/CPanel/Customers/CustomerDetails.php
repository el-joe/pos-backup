<?php

namespace App\Livewire\Central\CPanel\Customers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\PlanPricingService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class CustomerDetails extends Component
{
    use LivewireOperations;

    public string $id;
    public Tenant $tenant;

    public array $renewData = [
        'period' => 'month',
        'systems_allowed' => ['pos'],
    ];

    public array $upgradeData = [
        'selected_plans' => [
            'pos' => null,
            'hrm' => null,
            'booking' => null,
        ],
        'period' => 'month',
    ];

    public function mount(string $id): void
    {
        $this->id = $id;
        $this->tenant = Tenant::query()->with('domains')->findOrFail($id);

        $currentSubscription = Subscription::query()
            ->where('tenant_id', $this->tenant->id)
            ->where('status', 'paid')
            ->orderByDesc('end_date')
            ->first();

        if ($currentSubscription) {
            $this->renewData['period'] = $currentSubscription->billing_cycle === 'yearly' ? 'year' : 'month';
            $systems = collect($currentSubscription->systems_allowed ?? [])->filter()->values()->all();
            $this->renewData['systems_allowed'] = count($systems) ? $systems : ['pos'];
            $this->upgradeData['period'] = $this->renewData['period'];

            $selectedSystemPlans = collect(data_get($currentSubscription->plan_details, 'selected_system_plans', []));
            $mappedPlans = [
                'pos' => null,
                'hrm' => null,
                'booking' => null,
            ];

            foreach ($selectedSystemPlans as $selectedPlan) {
                $module = (string) ($selectedPlan['module'] ?? '');
                $planId = (int) ($selectedPlan['id'] ?? 0);
                if (in_array($module, ['pos', 'hrm', 'booking'], true) && $planId > 0) {
                    $mappedPlans[$module] = $planId;
                }
            }

            if (count(array_filter($mappedPlans)) === 0 && $currentSubscription->plan_id) {
                $fallbackModule = 'pos';
                $currentPlanModule = is_object($currentSubscription->plan?->module_name)
                    ? $currentSubscription->plan?->module_name->value
                    : (string) ($currentSubscription->plan?->module_name ?? '');
                if (in_array($currentPlanModule, ['pos', 'hrm', 'booking'], true)) {
                    $fallbackModule = $currentPlanModule;
                }
                $mappedPlans[$fallbackModule] = (int) $currentSubscription->plan_id;
            }

            $this->upgradeData['selected_plans'] = $mappedPlans;
        }
    }

    public function renewPackage(): void
    {
        $this->validate([
            'renewData.period' => ['required', 'in:month,year'],
            'renewData.systems_allowed' => ['required', 'array', 'min:1'],
            'renewData.systems_allowed.*' => ['required', 'in:pos,hrm,booking'],
        ]);

        $current = Subscription::query()
            ->where('tenant_id', $this->tenant->id)
            ->where('status', 'paid')
            ->orderByDesc('end_date')
            ->first();

        if (!$current) {
            $this->popup('error', 'No active paid subscription found to renew');
            return;
        }

        $plan = Plan::query()->find($current->plan_id);
        if (!$plan) {
            $this->popup('error', 'Plan not found for current subscription');
            return;
        }

        $period = $this->renewData['period'];
        $systemsAllowed = collect($this->renewData['systems_allowed'] ?? [])->filter()->unique()->values()->all();
        if (count($systemsAllowed) === 0) {
            $systemsAllowed = ['pos'];
        }

        $pricing = app(PlanPricingService::class)->calculate($plan, $period, count($systemsAllowed));
        $billingCycle = $period === 'year' ? 'yearly' : 'monthly';
        $startDate = $current->end_date && $current->end_date->isFuture() ? $current->end_date : now();
        $cycleMonths = app(PlanPricingService::class)->cycleMonths($period);
        $endDate = $startDate->copy()->addMonths($cycleMonths + (int) ($pricing['free_trial_months'] ?? 0));
        $payableNow = ((int) ($pricing['free_trial_months'] ?? 0) > 0) ? 0.0 : (float) ($pricing['final_price'] ?? 0);

        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'plan_id' => $plan->id,
            'plan_details' => array_merge($plan->toArray(), [
                'pricing' => $pricing,
                'selected_systems' => $systemsAllowed,
            ]),
            'currency_id' => $this->tenant->currency_id,
            'price' => $payableNow,
            'systems_allowed' => $systemsAllowed,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'paid',
            'billing_cycle' => $billingCycle,
        ]);

        $this->popup('success', 'Package renewed successfully');
    }

    public function upgradePackage(): void
    {
        $this->validate([
            'upgradeData.period' => ['required', 'in:month,year'],
            'upgradeData.selected_plans' => ['required', 'array'],
            'upgradeData.selected_plans.pos' => ['nullable', 'exists:plans,id'],
            'upgradeData.selected_plans.hrm' => ['nullable', 'exists:plans,id'],
            'upgradeData.selected_plans.booking' => ['nullable', 'exists:plans,id'],
        ]);

        $period = $this->upgradeData['period'];

        $selectedPlanIds = collect($this->upgradeData['selected_plans'] ?? [])
            ->filter(fn ($id) => !empty($id))
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($selectedPlanIds->isEmpty()) {
            $this->popup('error', 'Please select at least one plan (ERP, HRM, or Booking)');
            return;
        }

        $plansById = Plan::query()
            ->whereIn('id', $selectedPlanIds)
            ->where('active', true)
            ->get()
            ->keyBy('id');

        $selectedSystemPlans = [];
        foreach (['pos', 'hrm', 'booking'] as $module) {
            $planId = (int) ($this->upgradeData['selected_plans'][$module] ?? 0);
            if ($planId <= 0) {
                continue;
            }

            $plan = $plansById->get($planId);
            if (!$plan) {
                continue;
            }

            $planModule = is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name;
            if ($planModule !== $module) {
                $this->popup('error', 'Selected plan does not belong to '.$module.' module');
                return;
            }

            $selectedSystemPlans[$module] = $plan;
        }

        if (count($selectedSystemPlans) === 0) {
            $this->popup('error', 'No valid module plans selected');
            return;
        }

        $systemsAllowed = array_values(array_keys($selectedSystemPlans));
        $systemsCount = max(1, count($systemsAllowed));

        $pricingBySystem = [];
        $totalPrice = 0.0;
        $payableNow = 0.0;
        $maxTrialMonths = 0;

        foreach ($selectedSystemPlans as $module => $plan) {
            $modulePricing = app(PlanPricingService::class)->calculate($plan, $period, $systemsCount);
            $modulePrice = (float) ($modulePricing['final_price'] ?? 0);
            $moduleTrialMonths = (int) ($modulePricing['free_trial_months'] ?? 0);

            $pricingBySystem[$module] = [
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'pricing' => $modulePricing,
                'payable_now' => $moduleTrialMonths > 0 ? 0.0 : $modulePrice,
            ];

            $totalPrice += $modulePrice;
            if ($moduleTrialMonths === 0) {
                $payableNow += $modulePrice;
            }
            $maxTrialMonths = max($maxTrialMonths, $moduleTrialMonths);
        }

        $pricing = [
            'period' => $period,
            'systems_count' => $systemsCount,
            'per_system' => $pricingBySystem,
            'total_price' => round($totalPrice, 2),
            'due_now' => round($payableNow, 2),
            'total_discount_amount' => 0,
            'free_trial_months' => $maxTrialMonths,
            'is_free_trial' => $maxTrialMonths > 0,
        ];

        $billingCycle = $period === 'year' ? 'yearly' : 'monthly';
        $cycleMonths = app(PlanPricingService::class)->cycleMonths($period);
        $endDate = now()->copy()->addMonths($cycleMonths + (int) ($pricing['free_trial_months'] ?? 0));
        $primaryPlan = collect($selectedSystemPlans)->first();
        $primaryPlanDetails = $primaryPlan instanceof Plan ? $primaryPlan->toArray() : [];

        DB::transaction(function () use ($primaryPlan, $primaryPlanDetails, $billingCycle, $endDate, $pricing, $systemsAllowed, $payableNow, $selectedSystemPlans) {
            Subscription::query()
                ->where('tenant_id', $this->tenant->id)
                ->where('status', 'paid')
                ->where('end_date', '>=', now())
                ->update(['status' => 'cancelled']);

            Subscription::create([
                'tenant_id' => $this->tenant->id,
                'plan_id' => $primaryPlan?->id,
                'plan_details' => array_merge($primaryPlanDetails, [
                    'pricing' => $pricing,
                    'selected_systems' => $systemsAllowed,
                    'selected_system_plans' => collect($selectedSystemPlans)->map(function ($plan, $module) {
                        return [
                            'module' => $module,
                            'id' => $plan->id,
                            'name' => $plan->name,
                            'slug' => $plan->slug,
                        ];
                    })->values()->all(),
                ]),
                'currency_id' => $this->tenant->currency_id,
                'price' => $payableNow,
                'systems_allowed' => $systemsAllowed,
                'start_date' => now(),
                'end_date' => $endDate,
                'status' => 'paid',
                'billing_cycle' => $billingCycle,
            ]);
        });

        $this->popup('success', 'Package upgraded successfully');
    }

    public function render()
    {
        $this->tenant = Tenant::query()->with('domains')->findOrFail($this->id);

        $subscriptions = Subscription::query()
            ->with('plan', 'currency')
            ->where('tenant_id', $this->tenant->id)
            ->orderByDesc('id')
            ->get();

        $plans = Plan::query()->where('active', true)->orderBy('name')->get();
        if ($plans->isEmpty()) {
            $plans = Plan::query()->orderBy('name')->get();
        }

        $plansByModule = [
            'pos' => $plans->filter(function ($plan) {
                $module = is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name;
                return $module === 'pos';
            })->values(),
            'hrm' => $plans->filter(function ($plan) {
                $module = is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name;
                return $module === 'hrm';
            })->values(),
            'booking' => $plans->filter(function ($plan) {
                $module = is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name;
                return $module === 'booking';
            })->values(),
        ];

        return view('livewire.central.cpanel.customers.customer-details', get_defined_vars());
    }
}
