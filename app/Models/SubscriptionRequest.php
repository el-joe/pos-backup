<?php

namespace App\Models;

use App\Services\PlanPricingService;
use Illuminate\Database\Eloquent\Model;

class SubscriptionRequest extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'billing_cycle',
        'systems_allowed',
        'price',
        'payment_method_id',
        'pay_from_balance',
        'manual',
        'currency_id',
        'currency_code',
        'currency_symbol',
        'conversion_rate',
        'converted_amount',
        'receipt_path',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'systems_allowed' => 'array',
        'price' => 'float',
        'pay_from_balance' => 'boolean',
        'manual' => 'boolean',
        'conversion_rate' => 'float',
        'converted_amount' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function (SubscriptionRequest $request) {
            if ($request->isDirty('status') && $request->status === 'approved') {
                $request->applyToSubscription();
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function applyToSubscription(): ?Subscription
    {
        $tenant = $this->tenant;
        $plan = $this->plan;

        if (!$tenant || !$plan) {
            return null;
        }

        $currentSubscription = Subscription::forTenant($tenant->id)
            ->current()->paid()
            ->orderByDesc('end_date')
            ->first();

        $startDate = $currentSubscription && carbon($currentSubscription->end_date)->isFuture()
            ? carbon($currentSubscription->end_date)
            : now();

        $period = $plan->isYearly() ? 'year' : 'month';
        $cycleMonths = app(PlanPricingService::class)->cycleMonths($period);
        $endDate = $startDate->copy()->addMonths($cycleMonths);

        return Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'plan_details' => [
                'selected_systems' => $this->systems_allowed,
                'source' => 'subscription_request',
                'subscription_request_id' => $this->id,
            ],
            'currency_id' => $this->currency_id,
            'price' => $this->price,
            'systems_allowed' => $this->systems_allowed,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'paid',
            'billing_cycle' => $this->billing_cycle,
        ]);
    }
}
