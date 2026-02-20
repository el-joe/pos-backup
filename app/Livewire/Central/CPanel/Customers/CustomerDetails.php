<?php

namespace App\Livewire\Central\CPanel\Customers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
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
    ];

    public array $upgradeData = [
        'plan_id' => null,
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
            $this->upgradeData['period'] = $this->renewData['period'];
            $this->upgradeData['plan_id'] = $currentSubscription->plan_id;
        }
    }

    public function renewPackage(): void
    {
        $this->validate([
            'renewData.period' => ['required', 'in:month,year'],
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
        $billingCycle = $period === 'year' ? 'yearly' : 'monthly';
        $startDate = $current->end_date && $current->end_date->isFuture() ? $current->end_date : now();
        $endDate = $period === 'year' ? $startDate->copy()->addYear() : $startDate->copy()->addMonth();

        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'plan_id' => $plan->id,
            'plan_details' => $plan->toArray(),
            'currency_id' => $this->tenant->currency_id,
            'price' => (float) ($plan->{'price_' . $period} ?? 0),
            'systems_allowed' => ['pos'],
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
        ]);

        $plan = Plan::query()->findOrFail($this->upgradeData['plan_id']);
        $period = $this->upgradeData['period'];
        $billingCycle = $period === 'year' ? 'yearly' : 'monthly';
        $endDate = $period === 'year' ? now()->addYear() : now()->addMonth();

        DB::transaction(function () use ($plan, $period, $billingCycle, $endDate) {
            Subscription::query()
                ->where('tenant_id', $this->tenant->id)
                ->where('status', 'paid')
                ->where('end_date', '>=', now())
                ->update(['status' => 'cancelled']);

            Subscription::create([
                'tenant_id' => $this->tenant->id,
                'plan_id' => $plan->id,
                'plan_details' => $plan->toArray(),
                'currency_id' => $this->tenant->currency_id,
                'price' => (float) ($plan->{'price_' . $period} ?? 0),
                'systems_allowed' => ['pos'],
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
