<?php

namespace App\Livewire\Central\CPanel\Customers;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\PlanPricingService;
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

    private array $modules = ['pos', 'hrm', 'booking'];

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
        'selected_plans' => [
            'pos' => null,
            'hrm' => null,
            'booking' => null,
        ],
        'period' => 'month',
        'systems_allowed' => [],
        'active' => false,
    ];

    public function mount(): void
    {
        $this->data['country_id'] = Country::query()->value('id');
        $this->data['currency_id'] = Currency::query()->value('id');

        foreach ($this->modules as $module) {
            $defaultPlanId = Plan::query()
                ->where('active', true)
                ->where('module_name', $module)
                ->orderByDesc('recommended')
                ->orderBy('price_month')
                ->value('id');

            if ($module === 'pos') {
                $this->data['selected_plans'][$module] = $defaultPlanId ?: Plan::query()->where('module_name', $module)->value('id');
            }
        }
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
            'data.selected_plans' => ['required', 'array'],
            'data.selected_plans.pos' => ['required', 'exists:plans,id'],
            'data.selected_plans.hrm' => ['nullable', 'exists:plans,id'],
            'data.selected_plans.booking' => ['nullable', 'exists:plans,id'],
            'data.period' => ['required', 'in:month,year'],
            'data.active' => ['boolean'],
        ]);

        $tenantId = $this->generateUniqueTenantId((string) $this->data['company_name']);
        $period = $this->data['period'] === 'year' ? 'year' : 'month';

        $selectedPlanIds = collect($this->data['selected_plans'] ?? [])
            ->filter(fn ($id) => !empty($id))
            ->map(fn ($id) => (int) $id)
            ->values();

        $selectedPlans = Plan::query()
            ->whereIn('id', $selectedPlanIds)
            ->where('active', true)
            ->get()
            ->keyBy('id');

        $selectedSystemPlans = [];
        foreach ($this->modules as $module) {
            $planId = (int) ($this->data['selected_plans'][$module] ?? 0);
            if ($planId <= 0) {
                continue;
            }

            $plan = $selectedPlans->get($planId);
            if (!$plan) {
                continue;
            }

            $planModule = is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name;
            if ($planModule !== $module) {
                continue;
            }

            $selectedSystemPlans[$module] = $plan;
        }

        if (count($selectedSystemPlans) === 0) {
            $this->addError('data.selected_plans.pos', 'Please select at least one valid plan.');
            return;
        }

        $systemsAllowed = array_values(array_keys($selectedSystemPlans));
        $this->data['systems_allowed'] = $systemsAllowed;

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

        $tenant = DB::connection('central')->transaction(function () use ($tenantId, $primaryPlan, $billingCycle, $endDate, $pricing, $systemsAllowed, $payableNow, $selectedSystemPlans) {
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
                'plan_id' => $primaryPlan?->id,
                'plan_details' => array_merge($primaryPlan?->toArray() ?? [], [
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
                'currency_id' => $this->data['currency_id'],
                'price' => $payableNow,
                'systems_allowed' => $systemsAllowed,
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
        $plansByModule = Plan::query()
            ->where('active', true)
            ->orderBy('module_name')
            ->orderBy('price_month')
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Plan $plan) => is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name);

        return view('livewire.central.cpanel.customers.customer-create', get_defined_vars());
    }
}
