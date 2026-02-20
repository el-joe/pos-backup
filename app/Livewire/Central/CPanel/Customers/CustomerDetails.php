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
        'plan_id' => null,
        'period' => 'month',
        'systems_allowed' => ['pos'],
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
            $this->upgradeData['plan_id'] = $currentSubscription->plan_id;
            $this->upgradeData['systems_allowed'] = $this->renewData['systems_allowed'];
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
            'upgradeData.plan_id' => ['required', 'exists:plans,id'],
            'upgradeData.period' => ['required', 'in:month,year'],
            'upgradeData.systems_allowed' => ['required', 'array', 'min:1'],
            'upgradeData.systems_allowed.*' => ['required', 'in:pos,hrm,booking'],
        ]);

        $plan = Plan::query()->findOrFail($this->upgradeData['plan_id']);
        $period = $this->upgradeData['period'];
        $systemsAllowed = collect($this->upgradeData['systems_allowed'] ?? [])->filter()->unique()->values()->all();
        if (count($systemsAllowed) === 0) {
            $systemsAllowed = ['pos'];
        }

        $pricing = app(PlanPricingService::class)->calculate($plan, $period, count($systemsAllowed));
        $billingCycle = $period === 'year' ? 'yearly' : 'monthly';
        $cycleMonths = app(PlanPricingService::class)->cycleMonths($period);
        $endDate = now()->copy()->addMonths($cycleMonths + (int) ($pricing['free_trial_months'] ?? 0));
        $payableNow = ((int) ($pricing['free_trial_months'] ?? 0) > 0) ? 0.0 : (float) ($pricing['final_price'] ?? 0);

        DB::transaction(function () use ($plan, $billingCycle, $endDate, $pricing, $systemsAllowed, $payableNow) {
            Subscription::query()
                ->where('tenant_id', $this->tenant->id)
                ->where('status', 'paid')
                ->where('end_date', '>=', now())
                ->update(['status' => 'cancelled']);

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

        return view('livewire.central.cpanel.customers.customer-details', get_defined_vars());
    }
}
