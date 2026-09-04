<?php

namespace App\Livewire\Admin\Plans;

use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Models\WalletTopupRequest;
use App\Payments\Services\PaymentService;
use App\Services\PlanPricingService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubscriptionsPage extends Component
{
    use WithFileUploads;

    // Renew / change plan panel
    public bool $showChangePlanPanel = false;

    public ?int $selectedPlanId = null;

    public ?int $selectedPaymentMethodId = null;

    public bool $payFromBalance = false;

    public $receiptFile;

    // Wallet top-up panel
    public bool $showTopUpPanel = false;

    public ?float $topUpAmount = null;

    public ?int $topUpPaymentMethodId = null;

    public $topUpReceiptFile;

    public function mount(): void
    {
        $currentSubscription = $this->currentSubscription();
        $this->selectedPlanId = $currentSubscription?->plan_id;
    }

    public function openChangePlanPanel(): void
    {
        $currentSubscription = $this->currentSubscription();
        $this->selectedPlanId = $currentSubscription?->plan_id;
        $this->payFromBalance = false;
        $this->selectedPaymentMethodId = null;
        $this->receiptFile = null;
        $this->showChangePlanPanel = true;
    }

    public function closeChangePlanPanel(): void
    {
        $this->showChangePlanPanel = false;
    }

    public function openTopUpPanel(): void
    {
        $this->topUpAmount = null;
        $this->topUpPaymentMethodId = null;
        $this->topUpReceiptFile = null;
        $this->showTopUpPanel = true;
    }

    public function closeTopUpPanel(): void
    {
        $this->showTopUpPanel = false;
    }

    public function updatedPayFromBalance($value): void
    {
        if ($value) {
            $this->selectedPaymentMethodId = null;
        }
    }

    public function updatedSelectedPaymentMethodId($value): void
    {
        if ($value) {
            $this->payFromBalance = false;
        }
    }

    private function currentSubscription(): ?Subscription
    {
        return Subscription::forTenant(tenant('id'))
            ->current()->paid()
            ->with('plan')
            ->orderByDesc('end_date')
            ->first();
    }

    private function pricingFor(Plan $plan, array $systemsAllowed): array
    {
        $period = $plan->isYearly() ? 'year' : 'month';

        return app(PlanPricingService::class)->calculate($plan, $period, max(1, count($systemsAllowed)));
    }

    /**
     * Kicks off a real gateway payment (currently only PayPal is functional) and returns
     * a redirect response on success, or null (with a validation error already set) on failure.
     */
    private function initiateGatewayPayment(PaymentMethod $paymentMethod, float $amount, array $metadata, string $errorField)
    {
        $paymentProviderClass = 'App\\Payments\\Providers\\' . ($paymentMethod->provider ?? '');
        if (!class_exists($paymentProviderClass)) {
            $this->addError($errorField, 'This payment gateway is not configured.');
            return null;
        }

        $centralDomain = config('tenancy.central_domains')[0] ?? request()->getHost();
        $scheme = request()->isSecure() ? 'https' : 'http';
        $token = encodedData($metadata);

        $provider = new $paymentProviderClass();
        $paymentService = new PaymentService($provider);

        try {
            $requestPayload = $paymentService->pay([
                'amount' => $amount,
                'currency' => 'USD',
                'description' => 'Mohaaseb Subscription Payment',
                'metadata' => $metadata,
                'return_url' => "{$scheme}://{$centralDomain}/payment/check",
                'cancel_url' => "{$scheme}://{$centralDomain}/payment/failed?token=" . urlencode($token),
                'token' => $token,
            ]);
        } catch (\Throwable $e) {
            report($e);
            $this->addError($errorField, 'The payment gateway is temporarily unavailable. Please try again shortly.');
            return null;
        }

        if (($requestPayload['status'] ?? null) === 'error') {
            $this->addError($errorField, $requestPayload['message'] ?? 'Unable to start the payment process.');
            return null;
        }

        $requestPayload['metadata'] = $token;

        PaymentTransaction::create([
            'payment_method_id' => $paymentMethod->id,
            'amount' => $amount,
            'status' => 'pending',
            'request_payload' => $requestPayload,
            'transaction_reference' => $requestPayload['payment']['id'] ?? null,
        ]);

        $redirectUrl = method_exists($provider, 'getApproveUrl')
            ? $provider->getApproveUrl($requestPayload['payment'] ?? [])
            : ($requestPayload['payment']['links'][1]['href'] ?? null);

        if (!$redirectUrl) {
            $this->addError($errorField, 'Unable to start the payment process.');
            return null;
        }

        return redirect()->to($redirectUrl);
    }

    public function processSubscriptionChange()
    {
        if (!adminCan('subscriptions.renew')) {
            $this->popup('error', 'You are not authorized to perform this action', 'center');
            return;
        }

        $plan = Plan::query()->active()->find($this->selectedPlanId);
        if (!$plan) {
            $this->addError('selectedPlanId', 'Please select a valid plan.');
            return;
        }

        $currentSubscription = $this->currentSubscription();
        $systemsAllowed = $currentSubscription->systems_allowed ?? ['pos'];

        $pricing = $this->pricingFor($plan, $systemsAllowed);
        $amount = (float) ($pricing['final_price'] ?? 0);
        $billingCycle = $plan->isYearly() ? 'yearly' : 'monthly';
        $tenantModel = tenant();

        // Pay from wallet balance
        if ($this->payFromBalance) {
            if ((float) $tenantModel->balance < $amount) {
                $this->addError('payFromBalance', 'Your wallet balance is not enough to cover this amount.');
                return;
            }

            try {
                DB::beginTransaction();

                $tenantModel->update(['balance' => $tenantModel->balance - $amount]);

                $subscriptionRequest = SubscriptionRequest::create([
                    'tenant_id' => $tenantModel->id,
                    'plan_id' => $plan->id,
                    'billing_cycle' => $billingCycle,
                    'systems_allowed' => $systemsAllowed,
                    'price' => $amount,
                    'pay_from_balance' => true,
                    'currency_id' => $currentSubscription?->currency_id,
                    'status' => 'pending',
                ]);
                $subscriptionRequest->update(['status' => 'approved']);

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                report($e);
                $this->popup('error', 'Something went wrong while processing your payment.', 'center');
                return;
            }

            $this->showChangePlanPanel = false;
            $this->popup('success', 'Subscription updated successfully using your wallet balance.', 'center');
            return;
        }

        // Pay via a configured payment method
        $paymentMethod = $this->selectedPaymentMethodId
            ? PaymentMethod::query()->where('active', true)->find($this->selectedPaymentMethodId)
            : null;

        if (!$paymentMethod) {
            $this->addError('selectedPaymentMethodId', 'Please select a payment method.');
            return;
        }

        // Manual payment method => pending admin approval with receipt proof.
        if ($paymentMethod->manual) {
            $this->validate([
                'receiptFile' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);

            $receiptPath = $this->receiptFile->store('subscription-requests/receipts', 'public');

            $payload = [
                'tenant_id' => $tenantModel->id,
                'plan_id' => $plan->id,
                'billing_cycle' => $billingCycle,
                'systems_allowed' => $systemsAllowed,
                'price' => $amount,
                'payment_method_id' => $paymentMethod->id,
                'manual' => true,
                'receipt_path' => $receiptPath,
                'status' => 'pending',
            ];

            if ($paymentMethod->currency) {
                $payload['currency_id'] = $paymentMethod->currency->id;
                $payload['currency_code'] = $paymentMethod->currency->code;
                $payload['currency_symbol'] = $paymentMethod->currency->symbol;
                $payload['conversion_rate'] = (float) $paymentMethod->currency->conversion_rate;
                $payload['converted_amount'] = round($amount * (float) $paymentMethod->currency->conversion_rate, 2);
            }

            SubscriptionRequest::create($payload);

            $this->showChangePlanPanel = false;
            $this->receiptFile = null;
            $this->popup('success', 'Your payment proof was submitted and is pending admin approval.', 'center');
            return;
        }

        // Real gateway (PayPal / Paymob / Stripe) => create the pending request, then redirect to pay.
        $subscriptionRequest = SubscriptionRequest::create([
            'tenant_id' => $tenantModel->id,
            'plan_id' => $plan->id,
            'billing_cycle' => $billingCycle,
            'systems_allowed' => $systemsAllowed,
            'price' => $amount,
            'payment_method_id' => $paymentMethod->id,
            'manual' => false,
            'currency_id' => $currentSubscription?->currency_id,
            'status' => 'pending',
        ]);

        $result = $this->initiateGatewayPayment($paymentMethod, $amount, [
            'kind' => 'subscription_request',
            'reference_id' => $subscriptionRequest->id,
        ], 'selectedPaymentMethodId');

        if ($result) {
            return $result;
        }

        $subscriptionRequest->delete();
    }

    public function processTopUp()
    {
        if (!adminCan('subscriptions.renew')) {
            $this->popup('error', 'You are not authorized to perform this action', 'center');
            return;
        }

        $this->validate([
            'topUpAmount' => 'required|numeric|min:1',
            'topUpPaymentMethodId' => 'required|integer',
        ]);

        $amount = (float) $this->topUpAmount;
        $tenantModel = tenant();

        $paymentMethod = PaymentMethod::query()->where('active', true)->find($this->topUpPaymentMethodId);
        if (!$paymentMethod) {
            $this->addError('topUpPaymentMethodId', 'Please select a payment method.');
            return;
        }

        // Manual payment method => pending admin approval with receipt proof.
        if ($paymentMethod->manual) {
            $this->validate([
                'topUpReceiptFile' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);

            $receiptPath = $this->topUpReceiptFile->store('wallet-topups/receipts', 'public');

            $payload = [
                'tenant_id' => $tenantModel->id,
                'amount' => $amount,
                'payment_method_id' => $paymentMethod->id,
                'manual' => true,
                'receipt_path' => $receiptPath,
                'status' => 'pending',
            ];

            if ($paymentMethod->currency) {
                $payload['currency_id'] = $paymentMethod->currency->id;
                $payload['currency_code'] = $paymentMethod->currency->code;
                $payload['currency_symbol'] = $paymentMethod->currency->symbol;
                $payload['conversion_rate'] = (float) $paymentMethod->currency->conversion_rate;
                $payload['converted_amount'] = round($amount * (float) $paymentMethod->currency->conversion_rate, 2);
            }

            WalletTopupRequest::create($payload);

            $this->showTopUpPanel = false;
            $this->topUpReceiptFile = null;
            $this->popup('success', 'Your payment proof was submitted and is pending admin approval.', 'center');
            return;
        }

        // Real gateway => create the pending request, then redirect to pay.
        $topUpRequest = WalletTopupRequest::create([
            'tenant_id' => $tenantModel->id,
            'amount' => $amount,
            'payment_method_id' => $paymentMethod->id,
            'manual' => false,
            'status' => 'pending',
        ]);

        $result = $this->initiateGatewayPayment($paymentMethod, $amount, [
            'kind' => 'wallet_topup',
            'reference_id' => $topUpRequest->id,
        ], 'topUpPaymentMethodId');

        if ($result) {
            return $result;
        }

        $topUpRequest->delete();
    }

    public function render()
    {
        $currentSubscription = $this->currentSubscription();
        if (!$currentSubscription) {
            $currentSubscription = Subscription::forTenant(tenant('id'))
                ->orderBy('end_date', 'desc')->first();
        }
        $subscriptions = Subscription::forTenant(tenant('id'))
            ->with('plan')
            ->orderBy('start_date', 'desc')->get();
        $accountBalance = tenant('balance');

        $daysRemaining = null;
        $daysTotal = null;
        $percentRemaining = 0;
        if ($currentSubscription) {
            $start = carbon($currentSubscription->start_date);
            $end = carbon($currentSubscription->end_date);
            $now = now();
            $daysTotal = max(1, $start->diffInDays($end));
            $daysRemaining = max(0, $now->diffInDays($end, false) > 0 ? $now->diffInDays($end) : 0);
            $percentRemaining = min(100, max(0, (int) round(($daysRemaining / $daysTotal) * 100)));
        }

        $plans = Plan::query()->active()->orderBy('price')->get();
        $paymentMethods = PaymentMethod::query()->where('active', true)->orderBy('id')->get();

        $selectedPlan = $this->selectedPlanId ? $plans->firstWhere('id', $this->selectedPlanId) : null;
        $pricingPreview = $selectedPlan ? $this->pricingFor($selectedPlan, $currentSubscription?->systems_allowed ?? ['pos']) : null;

        $selectedPaymentMethod = $this->selectedPaymentMethodId
            ? $paymentMethods->firstWhere('id', $this->selectedPaymentMethodId)
            : null;

        $topUpPaymentMethod = $this->topUpPaymentMethodId
            ? $paymentMethods->firstWhere('id', $this->topUpPaymentMethodId)
            : null;

        return layoutView('plans.subscriptions-page', get_defined_vars());
    }
}
