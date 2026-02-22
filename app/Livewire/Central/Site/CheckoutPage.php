<?php

namespace App\Livewire\Central\Site;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Partner;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Payments\Providers\Paypal;
use App\Payments\Services\PaymentService;
use App\Services\PlanPricingService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.central.gemini.layout')]
class CheckoutPage extends Component
{
    public $plan, $period, $slug;

    private array $modules = ['pos', 'hrm', 'booking'];

    public $data = [
        'domain_mode'=>'subdomain',
        'subdomain' => null,
        'domain' => null,
        'final_domain' => null,
        'systems_allowed' => ['pos'],
        'selected_plans' => [
            'pos' => null,
            'hrm' => null,
            'booking' => null,
        ],
        'privacy_policy_agree' => false,
        'terms_conditions_agree' => false,
    ];

    public $rules = [
        'data.company_name'=>'required|string|max:255|unique:tenants,id|regex:/^[a-zA-Z0-9_ ]+$/',
        'data.company_email'=>'required|email|max:255',
        'data.company_phone'=>'required|string|max:50',
        'data.domain_mode'=>'required|in:subdomain,domain',
        'data.final_domain'=>'required|string|max:255|unique:domains,domain',
        'data.country_id'=>'required|exists:countries,id',
        'data.currency_id'=>'required|exists:currencies,id',
        'data.tax_number'=>'nullable|string|max:100',
        'data.address'=>'nullable|string|max:500',
        'data.admin_name'=>'required|string|max:255',
        'data.admin_email'=>'required|email|max:255',
        'data.admin_phone'=>'nullable|string|max:50',
        'data.admin_password'=>'required|string|min:6',
        'data.systems_allowed' => 'required|array|min:1',
        'data.systems_allowed.*' => 'required|in:pos,hrm,booking',
        'data.selected_plans' => 'nullable|array',
        'data.selected_plans.pos' => 'nullable|exists:plans,id',
        'data.selected_plans.hrm' => 'nullable|exists:plans,id',
        'data.selected_plans.booking' => 'nullable|exists:plans,id',
        'data.privacy_policy_agree' => 'accepted',
        'data.terms_conditions_agree' => 'accepted',
    ];

    // function updateDomain()
    // {
    //     // Keep only characters that match /^[a-zA-Z0-9_]+$/
    //     $raw = $this->data['subdomain'] ?? '';
    //     $this->updatingDataSubdomain($raw);
    // }

    function updatingDataSubdomain($value){
        $clean = preg_replace('/[^a-z0-9_]/', '', $value);
        $clean = strtolower(trim($clean));
        $clean = substr($clean, 0, 100);
        $this->data['final_domain'] = $clean ? ($clean . '.' . ($_SERVER['HTTP_HOST'] ?? '')) : '';
    }

    function updatingDataDomain($value)
    {
        // if domain mode is domain, set final_domain to domain and make sure domain is valid url
        $domain = $value ?? '';
        $domain = trim($domain);
        // prepend scheme if missing for validation
        $testUrl = (preg_match('/^https?:\/\//i', $domain) ? $domain : 'http://' . $domain);
        if (filter_var($testUrl, FILTER_VALIDATE_URL)) {
            $this->data['final_domain'] = $domain;
        } else {
            $this->data['final_domain'] = '';
        }
    }

    function updatingDataDomainMode($value)
    {
        if ($value === 'subdomain') {
            $this->updatingDataSubdomain($this->data['subdomain'] ?? '');
        } else {
            $this->updatingDataDomain($this->data['domain'] ?? '');
        }
    }

    function mount()
    {
        // New flow (multi-module): token payload from PricingPage / landing checkout.
        $token = request()->route('token') ?? request()->query('token');
        $decodedToken = is_string($token) && trim($token) !== '' ? decodedData($token) : null;

        $initializedFromToken = false;
        if (is_array($decodedToken) && isset($decodedToken['systems'])) {
            $this->period = ($decodedToken['period'] ?? 'month') === 'year' ? 'year' : 'month';

            $requestedSystems = collect($decodedToken['systems'] ?? [])
                ->filter(fn ($item) => is_array($item))
                ->values();

            $selectedSystemPlans = [];
            foreach ($requestedSystems as $requestedSystem) {
                $module = (string) ($requestedSystem['module'] ?? '');
                if (!in_array($module, $this->modules, true)) {
                    continue;
                }

                $planId = (int) ($requestedSystem['plan_id'] ?? 0);
                $planSlug = trim((string) ($requestedSystem['slug'] ?? ''));

                $plan = null;
                if ($planId > 0) {
                    $plan = Plan::query()->active()->where('id', $planId)->first();
                } elseif ($planSlug !== '') {
                    $plan = Plan::query()->active()->where('module_name', $module)->where('slug', $planSlug)->first();
                }

                if (!$plan) {
                    continue;
                }

                $planModule = is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name;
                if ($planModule !== $module) {
                    continue;
                }

                $selectedSystemPlans[$module] = $plan;
            }

            if (count($selectedSystemPlans) > 0) {
                $this->data['systems_allowed'] = array_values(array_keys($selectedSystemPlans));
                foreach ($this->modules as $module) {
                    $this->data['selected_plans'][$module] = $selectedSystemPlans[$module]->id ?? null;
                }

                $primaryPlan = collect($selectedSystemPlans)->first();
                $this->plan = $primaryPlan
                    ? Plan::with('planFeatures.feature')->find($primaryPlan->id)
                    : null;
                $this->slug = $this->plan?->slug;
                $initializedFromToken = true;
            }
        }

        // Old flow (single plan): query param "plan" contains encoded slug/period.
        if (!$initializedFromToken) {
            $newPlanSlug = request()->query('plan');
            $data = decodedData($newPlanSlug);
            if (!is_array($data)) {
                $data = [];
            }

            $this->period = ($data['period'] ?? 'month') === 'year' ? 'year' : 'month';
            $this->slug = $slug = $data['slug'] ?? null;

            $this->plan = $slug
                ? Plan::with('planFeatures.feature')->whereSlug($slug)->first()
                : null;

            if (!$this->plan) {
                $this->plan = Plan::query()
                    ->active()
                    ->with('planFeatures.feature')
                    ->orderByDesc('recommended')
                    ->orderBy('price_month')
                    ->first();
            }

            if ($this->plan) {
                $defaultModule = is_object($this->plan->module_name) ? $this->plan->module_name->value : (string) $this->plan->module_name;
                $this->data['systems_allowed'] = in_array($defaultModule, $this->modules, true) ? [$defaultModule] : ['pos'];
                foreach ($this->modules as $module) {
                    $this->data['selected_plans'][$module] = $defaultModule === $module ? $this->plan->id : null;
                }
            }
        }

        $countryCode = old('data.country_id') ?? strtoupper(session('country'));
        $currencyCode = old('data.currency_id') ?? strtoupper(session('country'));

        $this->data['country_id'] = Country::where((old('data.country_id') != null ? 'id' : 'code'), $countryCode)->first()?->id;
        $this->data['currency_id'] = Currency::where((old('data.currency_id') != null ? 'id' : 'country_code'), $currencyCode)->first()?->id;
    }

    private function buildSelectedSystemPlans(array $systemsAllowed, string $period): array
    {
        $systemsAllowed = collect($systemsAllowed)
            ->filter(fn ($system) => in_array($system, $this->modules, true))
            ->unique()
            ->values()
            ->all();

        $selectedPlanIds = collect($this->data['selected_plans'] ?? [])
            ->filter(fn ($id) => !empty($id))
            ->map(fn ($id) => (int) $id)
            ->values();

        $plansById = Plan::query()
            ->whereIn('id', $selectedPlanIds)
            ->where('active', true)
            ->get()
            ->keyBy('id');

        $selectedSystemPlans = [];
        foreach ($systemsAllowed as $module) {
            $planId = (int) ($this->data['selected_plans'][$module] ?? 0);
            $plan = $planId > 0 ? $plansById->get($planId) : null;

            if (!$plan) {
                $fallbackPlanId = (int) Plan::query()
                    ->active()
                    ->where('module_name', $module)
                    ->orderByDesc('recommended')
                    ->orderBy('price_month')
                    ->value('id');
                $plan = $fallbackPlanId > 0 ? Plan::query()->active()->find($fallbackPlanId) : null;
            }

            if (!$plan) {
                continue;
            }

            $planModule = is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name;
            if ($planModule !== $module) {
                continue;
            }

            $selectedSystemPlans[$module] = $plan;
        }

        return $selectedSystemPlans;
    }

    private function calculateMultiModulePricing(array $selectedSystemPlans, string $period): array
    {
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

        $totalPrice = round($totalPrice, 2);
        $payableNow = round($payableNow, 2);

        return [
            // Common keys used across the app.
            'period' => $period,
            'systems_count' => $systemsCount,
            'free_trial_months' => $maxTrialMonths,
            'is_free_trial' => $maxTrialMonths > 0,

            // Aggregated totals.
            'total_price' => $totalPrice,
            'due_now' => $payableNow,

            // Legacy keys expected by existing checkout blade.
            'base_price' => $totalPrice,
            'final_price' => $totalPrice,
            'total_discount_amount' => 0,
            'plan_discount_amount' => 0,
            'multi_system_discount_amount' => 0,

            // Per-system breakdown.
            'per_system' => $pricingBySystem,
        ];
    }

    function completeSubscription()
    {

        if (empty($this->data['admin_name'])) {
            $first = trim((string) ($this->data['admin_first_name'] ?? ''));
            $last = trim((string) ($this->data['admin_last_name'] ?? ''));
            $combined = trim($first . ' ' . $last);
            if ($combined !== '') {
                $this->data['admin_name'] = $combined;
            }
        }

        $this->validate();

        $systemsAllowed = collect($this->data['systems_allowed'] ?? [])
            ->filter(fn ($system) => in_array($system, ['pos', 'hrm', 'booking'], true))
            ->unique()
            ->values()
            ->all();
        if (count($systemsAllowed) === 0) {
            $systemsAllowed = ['pos'];
        }

        $period = $this->period === 'year' ? 'year' : 'month';
        $selectedSystemPlans = $this->buildSelectedSystemPlans($systemsAllowed, $period);

        if (count($selectedSystemPlans) === 0) {
            $this->addError('data.selected_plans.pos', 'Please select at least one valid plan.');
            return;
        }

        $systemsAllowed = array_values(array_keys($selectedSystemPlans));
        $pricing = $this->calculateMultiModulePricing($selectedSystemPlans, $period);
        $amount = (float) ($pricing['due_now'] ?? 0);

        $primaryPlan = collect($selectedSystemPlans)->first();

        $selectedPlansMap = [];
        foreach ($selectedSystemPlans as $module => $plan) {
            $selectedPlansMap[$module] = $plan->id;
        }

        $newData = $this->data + [
            'plan_id' => $primaryPlan?->id,
            'period' => $period,
            'systems_allowed' => $systemsAllowed,
            'selected_plans' => $selectedPlansMap,
            'selected_system_plans' => collect($selectedSystemPlans)->map(function ($plan, $module) {
                return [
                    'module' => $module,
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                ];
            })->values()->all(),
            'pricing' => $pricing,
            'amount' => $amount,
        ];

        if(session()->has('p_ref')){
            $newData['partner_id'] = Partner::where('referral_code',session('p_ref'))->first()?->id;
        }

        $dataToString = encodedData($newData);

        // else proceed to payment gateway
        if($newData['amount'] <= 0){
            return redirect()->route('payment.callback', ['type' => 'success', 'data' => $dataToString]);
        }

        $paymentService = new PaymentService(new Paypal());
        $requestPayload = $paymentService->pay([
            'amount' => $newData['amount'],
            'currency' => 'USD',
            'description' => 'Mohaaseb Subscription Payment',
            'metadata' => $newData,
            'return_url' => url('/payment/check'),
            'cancel_url' => url('/payment/failed'),
            'token' => $dataToString
        ]);

        $requestPayload['metadata'] = $dataToString;

        PaymentTransaction::create([
            // 'tenant_id',
            'payment_method_id' => 1, // Paypal
            'amount' => $newData['amount'],
            'status' => 'pending',
            'request_payload' => $requestPayload,
            'transaction_reference' => $requestPayload['payment']['id'] ?? null,
        ]);

        return redirect()->to($requestPayload['payment']['links'][1]['href']);
    }

    public function updatedDataTermsConditionsAgree($value): void
    {
        $this->data['privacy_policy_agree'] = (bool) $value;
    }

    public function updatedDataPrivacyPolicyAgree($value): void
    {
        $this->data['terms_conditions_agree'] = (bool) $value;
    }

    public function render()
    {
        $moduleTitles = [
            'pos' => 'POS & ERP System',
            'hrm' => 'HRM System',
            'booking' => 'Booking & Reservations',
        ];

        $countries = Country::orderBy('name')->get();
        $currencies = Currency::orderBy('name')->get();
        $currentCurrency = Currency::find($this->data['currency_id'] ?? null);
        $systemsAllowed = collect($this->data['systems_allowed'] ?? [])
            ->filter(fn ($system) => in_array($system, $this->modules, true))
            ->unique()
            ->values()
            ->all();

        $period = $this->period === 'year' ? 'year' : 'month';
        $selectedSystemPlans = $this->buildSelectedSystemPlans($systemsAllowed, $period);

        $selectedSystemsSummary = [];
        $selectedFeatureNames = [];
        $selectedDueNow = 0.0;
        $hasAnyFreeTrial = false;

        if (count($selectedSystemPlans) > 0) {
            $systemsCount = max(1, count($selectedSystemPlans));

            $plansWithFeatures = Plan::query()
                ->whereIn('id', collect($selectedSystemPlans)->map(fn ($plan) => $plan->id)->values()->all())
                ->with(['plan_features.feature' => function ($query) {
                    $query->where('active', true);
                }])
                ->get()
                ->keyBy('id');

            foreach ($selectedSystemPlans as $plan) {
                $planWithFeatures = $plansWithFeatures->get($plan->id) ?? $plan;

                $pricing = app(PlanPricingService::class)->calculate($planWithFeatures, $period, $systemsCount);
                $price = (float) ($pricing['final_price'] ?? 0);
                $freeTrialMonths = (int) ($pricing['free_trial_months'] ?? 0);
                $payableNow = $freeTrialMonths > 0 ? 0.0 : $price;

                $module = is_object($planWithFeatures->module_name)
                    ? $planWithFeatures->module_name->value
                    : (string) $planWithFeatures->module_name;

                $selectedSystemsSummary[] = [
                    'module' => $module,
                    'module_title' => $moduleTitles[$module] ?? ucfirst($module),
                    'plan_name' => $planWithFeatures->name,
                    'price' => $price,
                    'free_trial_months' => $freeTrialMonths,
                    'payable_now' => $payableNow,
                ];

                $featureNames = $planWithFeatures->plan_features
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
                    ->map(function ($planFeature) {
                        $feature = $planFeature->feature;
                        $name = app()->getLocale() === 'ar' ? ($feature->name_ar ?? null) : ($feature->name_en ?? null);
                        return $name ?: ($feature->name_en ?: $feature->code);
                    })
                    ->unique()
                    ->values()
                    ->take(4)
                    ->all();

                $selectedFeatureNames = array_values(array_unique(array_merge($selectedFeatureNames, $featureNames)));

                $selectedDueNow += $payableNow;
                if ($freeTrialMonths > 0) {
                    $hasAnyFreeTrial = true;
                }
            }
        }

        $selectedDueNow = round($selectedDueNow, 2);

        $pricingSummary = count($selectedSystemPlans) > 0
            ? $this->calculateMultiModulePricing($selectedSystemPlans, $period)
            : ( $this->plan ? app(PlanPricingService::class)->calculate($this->plan, $period, max(1, count($systemsAllowed))) : [] );

        $viewName = 'livewire.central.' . defaultLandingLayout() . '.checkout-page';
        if (!view()->exists($viewName)) {
            $viewName = 'livewire.central.site.checkout-page';
        }

        return view($viewName, get_defined_vars());
    }
}
