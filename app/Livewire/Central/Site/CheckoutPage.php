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
use Livewire\Component;

class CheckoutPage extends Component
{
    public $plan, $period, $slug;

    public $data = [
        'domain_mode'=>'subdomain',
        'systems_allowed' => ['pos'],
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
        $newPlanSlug = request()->query('plan');
        $data = decodedData($newPlanSlug);
        $this->period = $data['period'] ?? 'month';
        $this->slug = $slug = $data['slug'] ?? null;
        $this->plan = Plan::with('planFeatures.feature')->whereSlug($slug)->firstOrFail();
        $defaultModule = is_object($this->plan->module_name) ? $this->plan->module_name->value : (string) $this->plan->module_name;
        $this->data['systems_allowed'] = in_array($defaultModule, ['pos', 'hrm', 'booking'], true) ? [$defaultModule] : ['pos'];

        $countryCode = old('data.country_id') ?? strtoupper(session('country'));
        $currencyCode = old('data.currency_id') ?? strtoupper(session('country'));

        $this->data['country_id'] = Country::where((old('data.country_id') != null ? 'id' : 'code'), $countryCode)->first()?->id;
        $this->data['currency_id'] = Currency::where((old('data.currency_id') != null ? 'id' : 'country_code'), $currencyCode)->first()?->id;
    }

    function completeSubscription()
    {
        $this->validate();

        $systemsAllowed = collect($this->data['systems_allowed'] ?? [])
            ->filter(fn ($system) => in_array($system, ['pos', 'hrm', 'booking'], true))
            ->unique()
            ->values()
            ->all();
        if (count($systemsAllowed) === 0) {
            $systemsAllowed = ['pos'];
        }

        $pricing = app(PlanPricingService::class)->calculate($this->plan, $this->period, count($systemsAllowed));
        $amount = ((int) ($pricing['free_trial_months'] ?? 0) > 0) ? 0.0 : (float) ($pricing['final_price'] ?? 0);

        $newData = $this->data + [
            'plan_id' => $this->plan?->id,
            'period' => $this->period,
            'systems_allowed' => $systemsAllowed,
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

    public function render()
    {
        $countries = Country::orderBy('name')->get();
        $currencies = Currency::orderBy('name')->get();
        $currentCurrency = Currency::find($this->data['currency_id'] ?? null);
        $systemsCount = count($this->data['systems_allowed'] ?? []);
        $pricingSummary = app(PlanPricingService::class)->calculate($this->plan, $this->period, max(1, $systemsCount));
        return view('livewire.central.'. defaultLandingLayout() .'.checkout-page', get_defined_vars());
    }
}
