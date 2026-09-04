<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Models\WalletTopupRequest;
use App\Payments\Services\PaymentService;
use App\Services\PlanPricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubscriptionsController extends Controller
{
    public function index()
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

        $plans = Plan::query()->active()->whereIn('slug', ['monthly', 'annual'])->orderBy('price')->get();
        $paymentMethods = PaymentMethod::query()->where('active', true)->with('currency')->orderBy('id')->get();

        $selectedPlanId = $currentSubscription?->plan_id;
        $selectedPlan = $selectedPlanId ? $plans->firstWhere('id', $selectedPlanId) : null;
        $pricingPreview = $selectedPlan ? $this->pricingFor($selectedPlan, $currentSubscription?->systems_allowed ?? ['pos']) : null;

        return controllerLayoutView('plans.subscriptions-page', get_defined_vars());
    }

    public function planPricing(Request $request, Plan $plan): JsonResponse
    {
        $currentSubscription = $this->currentSubscription();
        $pricing = $this->pricingFor($plan, $currentSubscription?->systems_allowed ?? ['pos']);

        return response()->json(['pricing' => $pricing]);
    }

    public function changePlan(Request $request)
    {
        if (!adminCan('subscriptions.renew')) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }

        try {
            $data = $request->validate([
                'plan_id' => 'required|integer',
                'pay_from_balance' => 'nullable|boolean',
                'payment_method_id' => 'nullable|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $plan = Plan::query()->active()->whereIn('slug', ['monthly', 'annual'])->find($data['plan_id']);
        if (!$plan) {
            return response()->json(['errors' => ['plan_id' => ['Please select a valid plan.']]], 422);
        }

        $payFromBalance = (bool) ($data['pay_from_balance'] ?? false);

        $currentSubscription = $this->currentSubscription();
        $systemsAllowed = $currentSubscription->systems_allowed ?? ['pos'];

        $pricing = $this->pricingFor($plan, $systemsAllowed);
        $amount = (float) ($pricing['final_price'] ?? 0);
        $billingCycle = $plan->isYearly() ? 'yearly' : 'monthly';
        $tenantModel = tenant();

        // Pay from wallet balance
        if ($payFromBalance) {
            if ((float) $tenantModel->balance < $amount) {
                return response()->json(['errors' => ['pay_from_balance' => ['Your wallet balance is not enough to cover this amount.']]], 422);
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
                    'currency_id' => $this->usdCurrencyId(),
                    'status' => 'pending',
                ]);
                $subscriptionRequest->update(['status' => 'approved']);

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                report($e);
                return response()->json(['message' => 'Something went wrong while processing your payment.'], 500);
            }

            return response()->json(['message' => 'Subscription updated successfully using your wallet balance.']);
        }

        // Pay via a configured payment method
        $paymentMethod = !empty($data['payment_method_id'])
            ? PaymentMethod::query()->where('active', true)->find($data['payment_method_id'])
            : null;

        if (!$paymentMethod) {
            return response()->json(['errors' => ['payment_method_id' => ['Please select a payment method.']]], 422);
        }

        // Manual payment method => pending admin approval with receipt proof.
        if ($paymentMethod->manual) {
            try {
                $request->validate([
                    'receipt_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);
            } catch (ValidationException $e) {
                return response()->json(['errors' => $e->errors()], 422);
            }

            $receiptPath = $request->file('receipt_file')->store('subscription-requests/receipts', 'public');

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

            return response()->json(['message' => 'Your payment proof was submitted and is pending admin approval.']);
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
            'currency_id' => $currentSubscription?->currency_id ?? $this->usdCurrencyId(),
            'status' => 'pending',
        ]);

        [$redirectUrl, $errorField, $errorMessage] = $this->initiateGatewayPayment($paymentMethod, $amount, [
            'kind' => 'subscription_request',
            'reference_id' => $subscriptionRequest->id,
        ], 'payment_method_id');

        if ($redirectUrl) {
            return response()->json(['redirect' => $redirectUrl]);
        }

        $subscriptionRequest->delete();

        return response()->json(['errors' => [$errorField => [$errorMessage]]], 422);
    }

    public function topUp(Request $request)
    {
        if (!adminCan('subscriptions.renew')) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }

        try {
            $data = $request->validate([
                'amount' => 'required|numeric|min:1',
                'payment_method_id' => 'required|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $amount = (float) $data['amount'];
        $tenantModel = tenant();

        $paymentMethod = PaymentMethod::query()->where('active', true)->find($data['payment_method_id']);
        if (!$paymentMethod) {
            return response()->json(['errors' => ['payment_method_id' => ['Please select a payment method.']]], 422);
        }

        // Manual payment method => pending admin approval with receipt proof.
        if ($paymentMethod->manual) {
            try {
                $request->validate([
                    'receipt_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);
            } catch (ValidationException $e) {
                return response()->json(['errors' => $e->errors()], 422);
            }

            $receiptPath = $request->file('receipt_file')->store('wallet-topups/receipts', 'public');

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

            return response()->json(['message' => 'Your payment proof was submitted and is pending admin approval.']);
        }

        // Real gateway => create the pending request, then redirect to pay.
        $topUpRequest = WalletTopupRequest::create([
            'tenant_id' => $tenantModel->id,
            'amount' => $amount,
            'payment_method_id' => $paymentMethod->id,
            'manual' => false,
            'status' => 'pending',
        ]);

        [$redirectUrl, $errorField, $errorMessage] = $this->initiateGatewayPayment($paymentMethod, $amount, [
            'kind' => 'wallet_topup',
            'reference_id' => $topUpRequest->id,
        ], 'payment_method_id');

        if ($redirectUrl) {
            return response()->json(['redirect' => $redirectUrl]);
        }

        $topUpRequest->delete();

        return response()->json(['errors' => [$errorField => [$errorMessage]]], 422);
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
     * Subscription plans are always billed in USD (see initiateGatewayPayment()),
     * so a subscription with no prior currency to inherit from defaults to it.
     */
    private function usdCurrencyId(): ?int
    {
        return Currency::query()->where('code', 'USD')->value('id');
    }

    /**
     * Kicks off a real gateway payment (currently only PayPal is functional) and returns
     * [redirectUrl, errorField, errorMessage] — redirectUrl is null on failure.
     */
    private function initiateGatewayPayment(PaymentMethod $paymentMethod, float $amount, array $metadata, string $errorField): array
    {
        $paymentProviderClass = 'App\\Payments\\Providers\\' . ($paymentMethod->provider ?? '');
        if (!class_exists($paymentProviderClass)) {
            return [null, $errorField, 'This payment gateway is not configured.'];
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
            return [null, $errorField, 'The payment gateway is temporarily unavailable. Please try again shortly.'];
        }

        if (($requestPayload['status'] ?? null) === 'error') {
            return [null, $errorField, $requestPayload['message'] ?? 'Unable to start the payment process.'];
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
            return [null, $errorField, 'Unable to start the payment process.'];
        }

        return [$redirectUrl, null, null];
    }
}
