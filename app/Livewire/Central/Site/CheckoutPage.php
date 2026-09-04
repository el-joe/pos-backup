<?php

namespace App\Livewire\Central\Site;

use App\Models\Country;
use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Models\Partner;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\Tenant;
use App\Payments\Services\PaymentService;
use App\Services\PlanPricingService;
use App\Traits\LivewireOperations;
use App\Mail\AdminRegisterRequestMail;
use App\Mail\RegisterRequestMail;
use App\Models\RegisterRequest;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.central.gemini.layout')]
class CheckoutPage extends Component
{
    use WithFileUploads;

    public $plan, $period, $slug;

    public bool $isTrial = false;

    public $data = [
        'domain_mode'=>'subdomain',
        'subdomain' => null,
        'domain' => null,
        'final_domain' => null,
        'plan_id' => null,
        'privacy_policy_agree' => false,
        'terms_conditions_agree' => false,
        'payment_method_id' => null,
    ];

    public $receiptFile = null;

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
        'data.plan_id' => 'required|exists:plans,id',
        'data.privacy_policy_agree' => 'accepted',
        'data.terms_conditions_agree' => 'accepted',
        'data.payment_method_id' => 'nullable|integer',
    ];

    function updatingDataSubdomain($value){
        $clean = preg_replace('/[^a-z0-9_]/', '', $value);
        $clean = strtolower(trim($clean));
        $clean = substr($clean, 0, 100);
        $this->data['final_domain'] = $clean ? ($clean . '.' . ($_SERVER['HTTP_HOST'] ?? '')) : '';
    }

    function updatingDataDomain($value)
    {
        $domain = trim($value ?? '');
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
        $token = request()->route('token') ?? request()->query('token');
        $decodedToken = is_string($token) && trim($token) !== '' ? decodedData($token) : null;

        $this->period = (is_array($decodedToken) && ($decodedToken['period'] ?? 'month') === 'year') ? 'year' : 'month';
        $this->isTrial = is_array($decodedToken) && !empty($decodedToken['trial']);

        $plan = null;
        if (is_array($decodedToken)) {
            $planId = (int) ($decodedToken['plan_id'] ?? 0);
            $planSlug = trim((string) ($decodedToken['slug'] ?? ''));

            if ($planId > 0) {
                $plan = Plan::query()->active()->find($planId);
            }
            if (!$plan && $planSlug !== '') {
                $plan = Plan::query()->active()->where('slug', $planSlug)->first();
            }
        }

        if (!$plan) {
            $newPlanSlug = request()->query('plan');
            $data = decodedData($newPlanSlug);
            $slug = is_array($data) ? ($data['slug'] ?? null) : null;

            $plan = $slug ? Plan::query()->active()->where('slug', $slug)->first() : null;

            if (!$plan) {
                $plan = Plan::query()->active()->orderBy('price')->first();
            }
        }

        $this->plan = $plan;
        $this->slug = $plan?->slug;
        $this->data['plan_id'] = $plan?->id;

        $countryCode = old('data.country_id') ?? strtoupper(session('country'));
        $currencyCode = old('data.currency_id') ?? strtoupper(session('country'));

        $this->data['country_id'] = Country::where((old('data.country_id') != null ? 'id' : 'code'), $countryCode)->first()?->id;
        $this->data['currency_id'] = Currency::where((old('data.currency_id') != null ? 'id' : 'country_code'), $currencyCode)->first()?->id;

        if (empty($this->data['payment_method_id'])) {
            $activeMethodId = (int) PaymentMethod::query()->where('active', true)->orderBy('id')->value('id');
            if ($activeMethodId > 0) {
                $this->data['payment_method_id'] = $activeMethodId;
            }
        }
    }

    private function calculatePricing(Plan $plan, string $period): array
    {
        return app(PlanPricingService::class)->calculate($plan, $period, 1);
    }

    private function buildPlanPayloadFromCheckout(array $newData): array
    {
        $period = ($newData['period'] ?? 'month') === 'year' ? 'year' : 'month';

        return [
            'id' => $newData['plan_id'] ?? null,
            'period' => $period,
            'systems_allowed' => ['pos'],
            'pricing' => $newData['pricing'] ?? [],
            'is_trial' => !empty($newData['is_trial']),
            'trial_days' => (int) ($newData['trial_days'] ?? 0),
        ];
    }

    private function createRegisterRequestForCheckout(array $newData, ?PaymentMethod $paymentMethod, array $paymentPayload): RegisterRequest
    {
        $registerRequest = RegisterRequest::create([
            'data' => [
                'company' => [
                    'name' => $newData['company_name'],
                    'email' => $newData['company_email'],
                    'phone' => $newData['company_phone'],
                    'country_id' => $newData['country_id'],
                    'tax_number' => $newData['tax_number'] ?? null,
                    'address' => $newData['address'] ?? null,
                    'domain' => $newData['final_domain'],
                    'currency_id' => $newData['currency_id'],
                ],
                'admin' => [
                    'name' => $newData['admin_name'],
                    'email' => $newData['admin_email'],
                    'phone' => $newData['admin_phone'] ?? null,
                    'password' => $newData['admin_password'],
                ],
                'plan' => $this->buildPlanPayloadFromCheckout($newData),
                'partner_id' => $newData['partner_id'] ?? null,
                'payment' => array_merge([
                    'manual' => (bool) ($paymentPayload['manual'] ?? false),
                    'amount' => (float) ($paymentPayload['amount'] ?? 0),
                    'payment_method_id' => $paymentMethod?->id,
                    'payment_method_name' => $paymentMethod?->name,
                    'submitted_at' => now()->toISOString(),
                ], $paymentPayload),
            ],
            'status' => 'pending',
        ]);

        Mail::to($newData['company_email'])->send(new RegisterRequestMail([
            'name' => $newData['company_name'],
        ]));

        Mail::to(env('ADMIN_EMAIL', 'eljoe1717@gmail.com'))->send(new AdminRegisterRequestMail(
            registerRequest: $registerRequest
        ));

        return $registerRequest;
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

        $period = $this->period === 'year' ? 'year' : 'month';

        $plan = Plan::query()->active()->find($this->data['plan_id']);
        if (!$plan) {
            $this->addError('data.plan_id', 'Please select a valid plan.');
            return;
        }

        $pricing = $this->calculatePricing($plan, $period);
        $freeTrialMonths = (int) ($pricing['free_trial_months'] ?? 0);
        $amount = $freeTrialMonths > 0 ? 0.0 : (float) ($pricing['final_price'] ?? 0);

        $wantsTrial = $this->isTrial;
        if ($wantsTrial && !$plan->hasFreeTrial()) {
            $this->addError('data.plan_id', 'This plan does not offer a free trial.');
            return;
        }

        if ($wantsTrial) {
            $tenantId = request()->query('tenant');
            if ($tenantId) {
                $existingTenant = Tenant::find($tenantId);
                if ($existingTenant && $existingTenant->hasUsedTrial()) {
                    $this->addError('data.plan_id', 'You have already used your free trial.');
                    return;
                }
            }
        }

        $isTrial = $wantsTrial && $plan->hasFreeTrial();
        if ($isTrial) {
            $amount = 0.0;
        }

        $newData = $this->data + [
            'plan_id' => $plan->id,
            'period' => $period,
            'pricing' => $pricing,
            'amount' => $amount,
            'is_trial' => $isTrial,
            'trial_days' => $isTrial ? (int) $plan->free_trial_days : 0,
        ];

        if(session()->has('p_ref')){
            $newData['partner_id'] = Partner::where('referral_code',session('p_ref'))->first()?->id;
        }

        $dataToString = encodedData($newData);

        $paymentMethodId = (int) ($this->data['payment_method_id'] ?? 0);
        $paymentMethod = $paymentMethodId > 0
            ? PaymentMethod::query()->where('active', true)->find($paymentMethodId)
            : null;

        // If amount is 0 (free trial/fully discounted), skip payment gateway and create a register request.
        if ((float) ($newData['amount'] ?? 0) <= 0) {
            $this->createRegisterRequestForCheckout($newData, $paymentMethod, [
                'manual' => false,
                'amount' => 0.0,
            ]);

            return redirect()->route('payment-callback', [
                'type' => 'success',
                'message' => 'Thanks! Your registration was submitted successfully.',
            ]);
        }

        // Amount > 0 requires selecting a valid payment method.
        if (!$paymentMethod) {
            $this->addError('data.payment_method_id', 'Please select a payment method.');
            return;
        }

        // Manual method => create a register request with receipt proof.
        if ((bool) $paymentMethod->manual) {
            $this->validate([
                'receiptFile' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);

            $receiptPath = $this->receiptFile
                ? $this->receiptFile->store('register-requests/receipts', 'public')
                : null;

            $manualPaymentPayload = [
                'manual' => true,
                'amount' => (float) ($newData['amount'] ?? 0),
                'receipt_path' => $receiptPath,
            ];

            if ($paymentMethod->currency) {
                $manualPaymentPayload['currency_id'] = $paymentMethod->currency->id;
                $manualPaymentPayload['currency_code'] = $paymentMethod->currency->code;
                $manualPaymentPayload['currency_symbol'] = $paymentMethod->currency->symbol;
                $manualPaymentPayload['conversion_rate'] = (float) $paymentMethod->currency->conversion_rate;
                $manualPaymentPayload['converted_amount'] = round(
                    (float) ($newData['amount'] ?? 0) * (float) $paymentMethod->currency->conversion_rate,
                    2
                );
            }

            $this->createRegisterRequestForCheckout($newData, $paymentMethod, $manualPaymentPayload);

            return redirect()->route('payment-callback', [
                'type' => 'success',
                'message' => 'Thanks! Your payment proof was submitted and is pending verification.',
            ]);
        }

        $paymentProvider = 'App\\Payments\\Providers\\' . ($paymentMethod->provider ?? '');
        if (!class_exists($paymentProvider)) {
            $this->addError('data.payment_method_id', 'Payment gateway is not configured.');
            return;
        }

        $provider = new $paymentProvider();
        $paymentService = new PaymentService($provider);

        try {
            $requestPayload = $paymentService->pay([
                'amount' => $newData['amount'],
                'currency' => 'USD',
                'description' => 'Mohaaseb Subscription Payment',
                'metadata' => $newData,
                'return_url' => url('/payment/check'),
                'cancel_url' => url('/payment/failed', ['token' => $dataToString]),
                'token' => $dataToString
            ]);
        } catch (\Throwable $e) {
            report($e);
            $this->addError('data.payment_method_id', 'The payment gateway is temporarily unavailable. Please try again shortly.');
            return;
        }

        if (($requestPayload['status'] ?? null) === 'error') {
            $this->addError('data.payment_method_id', $requestPayload['message'] ?? 'Unable to start the payment process.');
            return;
        }

        $requestPayload['metadata'] = $dataToString;

        PaymentTransaction::create([
            'payment_method_id' => $paymentMethod->id,
            'amount' => $newData['amount'],
            'status' => 'pending',
            'request_payload' => $requestPayload,
            'transaction_reference' => $requestPayload['payment']['id'] ?? null,
        ]);

        $redirectUrl = method_exists($provider, 'getApproveUrl')
            ? $provider->getApproveUrl($requestPayload['payment'] ?? [])
            : ($requestPayload['payment']['links'][1]['href'] ?? null);

        if (!$redirectUrl) {
            $this->addError('data.payment_method_id', 'Unable to start the payment process.');
            return;
        }

        return redirect()->to($redirectUrl);
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
        $countries = Country::orderBy('name')->get();
        $currencies = Currency::orderBy('name')->get();
        $currentCurrency = Currency::find($this->data['currency_id'] ?? null);

        $period = $this->period === 'year' ? 'year' : 'month';

        $selectedSystemsSummary = [];
        $selectedFeatureNames = [];
        $selectedDueNow = 0.0;
        $hasAnyFreeTrial = false;
        $pricingSummary = [];

        $plan = $this->data['plan_id'] ? Plan::query()->active()->find($this->data['plan_id']) : $this->plan;

        if ($plan) {
            $pricing = $this->calculatePricing($plan, $period);
            $price = (float) ($pricing['final_price'] ?? 0);
            $isTrialSelection = $this->isTrial && $plan->hasFreeTrial();
            $freeTrialMonths = $isTrialSelection
                ? (int) ceil(((int) $plan->free_trial_days) / 30)
                : (int) ($pricing['free_trial_months'] ?? 0);
            $payableNow = $isTrialSelection || $freeTrialMonths > 0 ? 0.0 : $price;

            $selectedSystemsSummary[] = [
                'module' => 'pos',
                'module_title' => __('gemini-landing.checkout_page.system_fallback'),
                'plan_name' => $plan->localizedName(),
                'price' => $price,
                'free_trial_months' => $freeTrialMonths,
                'payable_now' => $payableNow,
            ];

            $selectedFeatureNames = [];

            $selectedDueNow = round($payableNow, 2);
            $hasAnyFreeTrial = $freeTrialMonths > 0;
            $pricingSummary = $pricing;
        }

        $viewName = 'livewire.central.' . defaultLandingLayout() . '.checkout-page';
        if (!view()->exists($viewName)) {
            $viewName = 'livewire.central.site.checkout-page';
        }

        $paymentMethods = PaymentMethod::query()
            ->where('active', true)
            ->when($this->data['country_id'] ?? null, fn ($q, $countryId) => $q->forCountry($countryId))
            ->orderBy('id')
            ->get();

        $selectedPaymentMethod = null;
        $selectedMethodId = (int) ($this->data['payment_method_id'] ?? 0);
        if ($selectedMethodId > 0) {
            $selectedPaymentMethod = $paymentMethods->firstWhere('id', $selectedMethodId);
        }
        if (!$selectedPaymentMethod && $paymentMethods->isNotEmpty()) {
            $selectedPaymentMethod = $paymentMethods->first();
            $this->data['payment_method_id'] = $selectedPaymentMethod->id;
        }

        return view($viewName, get_defined_vars());
    }
}
