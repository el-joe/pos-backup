<?php

namespace App\Livewire\Central\Site;

use App\Models\Plan;
use App\Services\SeoService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.central.gemini.layout')]
class PricingPage extends Component
{
    public string $billingPeriod = 'monthly';

    public array $plans = [];

    public ?int $selectedPlanId = null;

    public function mount(): void
    {
        $billingCycle = request()->query('billing_cycle');
        if (in_array($billingCycle, ['monthly', 'annual'], true)) {
            $this->billingPeriod = $billingCycle;
        }

        $plans = Plan::query()
            ->active()
            ->whereIn('slug', ['monthly', 'annual'])
            ->get()
            ->keyBy('slug');

        $locale = app()->getLocale();
        $includedFeatures = $this->includedFeatures();

        $this->plans = collect(['monthly', 'annual'])
            ->filter(fn (string $slug) => $plans->has($slug))
            ->map(function (string $slug) use ($plans, $locale, $includedFeatures) {
                $plan = $plans->get($slug);

                return [
                    'id' => $plan->id,
                    'slug' => $plan->slug,
                    'name' => $plan->localizedName($locale),
                    'price' => round((float) $plan->price, 2),
                    'type' => $plan->type,
                    'recommended' => $slug === 'annual',
                    'features' => $includedFeatures,
                    'free_trial_days' => (int) $plan->free_trial_days,
                ];
            })->values()->all();

        $defaultPlan = collect($this->plans)->firstWhere('slug', $this->billingPeriod === 'yearly' ? 'annual' : 'monthly')
            ?? collect($this->plans)->first();
        $this->selectedPlanId = isset($defaultPlan['id']) ? (int) $defaultPlan['id'] : null;
    }

    private function includedFeatures(): array
    {
        $locale = app()->getLocale();

        return $locale === 'ar'
            ? [
                'نقطة بيع (POS) كاملة',
                'إدارة الموارد البشرية (HRM)',
                'إدارة المقاولات',
                'المحاسبة والفواتير',
                'إدارة المخزون والفروع',
                'التقارير المتقدمة',
                'دعم فني غير محدود',
            ]
            : [
                'Full POS System',
                'HRM Management',
                'Contracting Management',
                'Accounting & Invoicing',
                'Inventory & Branches',
                'Advanced Reports',
                'Unlimited Support',
            ];
    }

    public function setBilling(string $period): void
    {
        $this->billingPeriod = $period === 'yearly' ? 'yearly' : 'monthly';
    }

    public function setPlan(int $planId): void
    {
        $exists = collect($this->plans)->contains(fn (array $plan) => (int) $plan['id'] === $planId);
        if (!$exists) {
            return;
        }

        $this->selectedPlanId = $planId;
    }

    public function proceedToCheckout(bool $trial = false)
    {
        $plan = $this->selectedPlan();
        if (!$plan) {
            return redirect()->route('pricing');
        }

        if ($trial && (int) ($plan['free_trial_days'] ?? 0) <= 0) {
            $trial = false;
        }

        $payload = [
            'period' => $this->isYearly() ? 'year' : 'month',
            'plan_id' => $plan['id'],
            'slug' => $plan['slug'],
            'trial' => $trial,
        ];

        $token = encodedData($payload);

        $params = ['token' => $token];
        $tenantId = request()->query('tenant');
        if ($tenantId) {
            $params['tenant'] = $tenantId;
        }

        return redirect()->route('tenant-checkout', $params);
    }

    public function checkoutPlan(int $planId)
    {
        $this->setPlan($planId);
        return $this->proceedToCheckout();
    }

    public function checkoutPlanTrial(int $planId)
    {
        $this->setPlan($planId);
        return $this->proceedToCheckout(true);
    }

    public function isYearly(): bool
    {
        return $this->billingPeriod === 'yearly';
    }

    public function selectedPlan(): ?array
    {
        $selectedPlanId = (int) $this->selectedPlanId;
        if ($selectedPlanId <= 0) {
            return null;
        }

        return collect($this->plans)->first(fn (array $plan) => (int) $plan['id'] === $selectedPlanId);
    }

    public function totalPrice(): float
    {
        $plan = $this->selectedPlan();
        if (!$plan) {
            return 0.0;
        }

        return round((float) $plan['price'], 2);
    }

    public function dueNow(): float
    {
        return $this->totalPrice();
    }

    public function render()
    {
        $monthly = collect($this->plans)->firstWhere('slug', 'monthly');
        $annual = collect($this->plans)->firstWhere('slug', 'annual');

        $offers = array_values(array_filter([
            $monthly ? [
                '@type' => 'Offer',
                'name' => 'Monthly',
                'price' => (string) $monthly['price'],
                'priceCurrency' => 'SAR',
                'billingDuration' => 'P1M',
            ] : null,
            $annual ? [
                '@type' => 'Offer',
                'name' => 'Annual',
                'price' => (string) $annual['price'],
                'priceCurrency' => 'SAR',
                'billingDuration' => 'P1Y',
            ] : null,
        ]));

        $seoHtml = SeoService::page([
            'title' => 'Pricing — Mohaaseb ERP Plans',
            'description' => 'Simple transparent pricing. Monthly and annual plans with full access to all features.',
            'canonical' => route('pricing'),
            'locale' => app()->getLocale() === 'ar' ? 'ar_EG' : 'en_US',
            'schema' => [
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => 'Mohaaseb ERP',
                'offers' => $offers,
            ],
        ]);

        return view('livewire.central.'. defaultLandingLayout() .'.pricing-page')
            ->layout('layouts.central.gemini.layout', ['seoHtml' => $seoHtml]);
    }
}
