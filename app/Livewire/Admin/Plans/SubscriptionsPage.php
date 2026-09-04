<?php

namespace App\Livewire\Admin\Plans;

use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Services\PlanPricingService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubscriptionsPage extends Component
{
    use WithFileUploads;

    public bool $showChangePlanPanel = false;

    public ?int $selectedPlanId = null;

    public ?int $selectedPaymentMethodId = null;

    public bool $payFromBalance = false;

    public $receiptFile;

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

    public function processSubscriptionChange(): void
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

        if (!$paymentMethod->manual) {
            $this->addError('selectedPaymentMethodId', 'This payment method is not available yet for existing subscriptions. Please choose Wallet Balance or a manual payment method.');
            return;
        }

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

        return layoutView('plans.subscriptions-page', get_defined_vars());
    }
}
