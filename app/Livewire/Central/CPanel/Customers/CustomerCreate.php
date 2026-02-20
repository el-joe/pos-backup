<?php

namespace App\Livewire\Central\CPanel\Customers;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class CustomerCreate extends Component
{
    use LivewireOperations;

    public array $data = [
        'company_name' => '',
        'company_email' => '',
        'company_phone' => '',
        'country_id' => null,
        'currency_id' => null,
        'tax_number' => '',
        'address' => '',
        'domain' => '',
        'admin_name' => '',
        'admin_email' => '',
        'admin_phone' => '',
        'admin_password' => '',
        'plan_id' => null,
        'period' => 'month',
        'active' => false,
    ];

    public function mount(): void
    {
        $this->data['country_id'] = Country::query()->value('id');
        $this->data['currency_id'] = Currency::query()->value('id');
        $this->data['plan_id'] = Plan::query()->where('active', true)->value('id') ?: Plan::query()->value('id');
    }

    public function save(): void
    {
        $this->validate([
            'data.company_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_ ]+$/'],
            'data.company_email' => ['required', 'email', 'max:255'],
            'data.company_phone' => ['required', 'string', 'max:50'],
            'data.country_id' => ['required', 'exists:countries,id'],
            'data.currency_id' => ['required', 'exists:currencies,id'],
            'data.tax_number' => ['nullable', 'string', 'max:100'],
            'data.address' => ['nullable', 'string', 'max:500'],
            'data.domain' => ['required', 'string', 'max:255', 'unique:domains,domain'],
            'data.admin_name' => ['required', 'string', 'max:255'],
            'data.admin_email' => ['required', 'email', 'max:255'],
            'data.admin_phone' => ['nullable', 'string', 'max:50'],
            'data.admin_password' => ['required', 'string', 'min:6'],
            'data.plan_id' => ['required', 'exists:plans,id'],
            'data.period' => ['required', 'in:month,year'],
            'data.active' => ['boolean'],
        ]);

        $tenantId = $this->generateUniqueTenantId((string) $this->data['company_name']);
        $plan = Plan::query()->findOrFail($this->data['plan_id']);
        $period = $this->data['period'] === 'year' ? 'year' : 'month';
        $billingCycle = $period === 'year' ? 'yearly' : 'monthly';
        $endDate = $period === 'year' ? now()->addYear() : now()->addMonth();

        $tenant = DB::transaction(function () use ($tenantId, $plan, $period, $billingCycle, $endDate) {
            $tenant = Tenant::create([
                'id' => $tenantId,
                'name' => $this->data['company_name'],
                'phone' => $this->data['company_phone'],
                'email' => $this->data['company_email'],
                'country_id' => $this->data['country_id'],
                'currency_id' => $this->data['currency_id'],
                'address' => $this->data['address'] ?: null,
                'tax_number' => $this->data['tax_number'] ?: null,
                'active' => (bool) ($this->data['active'] ?? false),
            ]);

            $tenant->domains()->create([
                'domain' => $this->data['domain'],
            ]);

            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'plan_details' => $plan->toArray(),
                'currency_id' => $this->data['currency_id'],
                'price' => (float) ($plan->{'price_' . $period} ?? 0),
                'systems_allowed' => ['pos'],
                'start_date' => now(),
                'end_date' => $endDate,
                'status' => 'paid',
                'billing_cycle' => $billingCycle,
            ]);

            return $tenant;
        });

        Artisan::call('tenants:seed', [
            '--tenants' => [$tenant->id],
        ]);

        $adminPayload = json_encode([
            'name' => $this->data['admin_name'],
            'phone' => $this->data['admin_phone'],
            'email' => $this->data['admin_email'],
            'password' => $this->data['admin_password'],
            'type' => 'super_admin',
            'country_id' => $this->data['country_id'],
        ]);

        Artisan::call('tenants:run', [
            'commandname' => 'app:tenant-create-admin',
            '--tenants' => [$tenant->id],
            '--argument' => ["request={$adminPayload}"],
        ]);

        $this->popup('success', 'Tenant created successfully');
        $this->redirect(route('cpanel.customers.details', ['id' => $tenant->id]), navigate: true);
    }

    private function generateUniqueTenantId(string $companyName): string
    {
        $base = Str::slug($companyName, '_');
        $base = preg_replace('/[^a-zA-Z0-9_]/', '_', $base) ?: 'tenant';
        $candidate = $base;
        $counter = 1;

        while (Tenant::query()->where('id', $candidate)->exists()) {
            $candidate = $base . '_' . $counter;
            $counter++;
        }

        return $candidate;
    }

    public function render()
    {
        $countries = Country::query()->orderBy('name')->get();
        $currencies = Currency::query()->orderBy('name')->get();
        $plans = Plan::query()->where('active', true)->orderBy('name')->get();

        if ($plans->isEmpty()) {
            $plans = Plan::query()->orderBy('name')->get();
        }

        return view('livewire.central.cpanel.customers.customer-create', get_defined_vars());
    }
}
