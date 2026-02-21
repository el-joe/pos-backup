<?php

namespace App\Models;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\PlanPricingService;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    // boot method to set partner commission on create and update

    protected static function boot()
    {
        parent::boot();

        static::created(function ($subscription) {
            if($subscription->status === 'paid'){
                $tenant = $subscription->tenant;
                if($tenant && !!$tenant->partner_id){
                    $partner = $tenant->partner;
                    if($partner){
                        $commissionRate = $partner->commission_rate ?? 0;
                        $commissionAmount = ($subscription->price * $commissionRate) / 100;
                        $partner->commissions()->firstOrCreate([
                            'tenant_id' => $tenant->id,
                            'subscription_id' => $subscription->id,
                        ], [
                            'amount' => $commissionAmount,
                            'status' => 'pending',
                            'subscription_id' => $subscription->id,
                            'currency_id' => $subscription->currency_id,
                            'commission_date' => now(),
                        ]);

                    }
                }
            }
        });

        static::updated(function ($subscription) {
            if($subscription->isDirty('status') && $subscription->status === 'paid'){
                $tenant = $subscription->tenant;
                if($tenant && !!$tenant->partner_id){
                    $partner = $tenant->partner;
                    if($partner){
                        $commissionRate = $partner->commission_rate ?? 0;
                        $commissionAmount = ($subscription->price * $commissionRate) / 100;
                        $partner->commissions()->updateOrCreate([
                            'tenant_id' => $tenant->id,
                            'subscription_id' => $subscription->id,
                        ], [
                            'amount' => $commissionAmount,
                            'status' => 'pending',
                            'currency_id' => $subscription->currency_id,
                            'commission_date' => now(),
                        ]);
                    }
                }
            }elseif($subscription->isDirty('status') && $subscription->status !== 'paid'){
                $tenant = $subscription->tenant;
                if($tenant && !!$tenant->partner_id){
                    $partner = $tenant->partner;
                    if($partner){
                        $partner->commissions()->where('subscription_id', $subscription->id)->delete();
                    }
                }
            }
        });

        static::deleted(function ($subscription) {
            $tenant = $subscription->tenant;
            if($tenant && !!$tenant->partner_id){
                $partner = $tenant->partner;
                if($partner){
                    $partner->commissions()->where('subscription_id', $subscription->id)->delete();
                }
            }
        });
    }

    protected $connection = 'central';

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'plan_details',
        'currency_id',
        'price',
        'systems_allowed',
        'start_date',
        'end_date',
        'status',
        'payment_transaction_id',
        'billing_cycle',
    ];

    protected $casts = [
        'plan_details' => 'array',
        'systems_allowed' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'billing_cycle' => 'string',
    ];

    function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class, 'payment_transaction_id');
    }

    function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }

    function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    function isActive()
    {
        $startDate = carbon($this->start_date);
        $endDate = carbon($this->end_date);
        $now = now();
        return $this->status === 'paid' && $now->between($startDate, $endDate);
    }

    // if billing_cycle is monthly he can cancel first 3 days from start date , if yearly he can cancel first 14 days
    function canCancel(){
        $startDate = carbon($this->start_date);
        $now = now();

        if ($this->billing_cycle === 'monthly') {
            return $startDate->diffInDays($now) <= 3;
        } elseif ($this->billing_cycle === 'yearly') {
            return $startDate->diffInDays($now) <= 14;
        }

        return false;
    }

    // if billing_cycle is monthly he can renew last 3 days from end date , if yearly he can renew last 14 days
    function canRenew(){
        $endDate = carbon($this->end_date);
        $now = now();

        if ($this->billing_cycle === 'monthly') {
            return $now->diffInDays($endDate) <= 3;
        } elseif ($this->billing_cycle === 'yearly') {
            return $now->diffInDays($endDate) <= 14;
        }

        return false;
    }

    static function currentTenantSubscriptions(){
        return self::forTenant(tenant('id'))
            ->current()->paid()
            ->with('plan')
            ->orderByDesc('end_date')
            ->get();
    }

        public function statusColor()
    {
        return [
            'paid' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'warning',
        ][$this->status] ?? 'secondary';
    }

    static function cancel(){
        /** @var self|null $currentSubscription */
        $currentSubscription = self::currentTenantSubscriptions()->first();
        if($currentSubscription && $currentSubscription->canCancel()){
            AuditLog::log(AuditLogActionEnum::CANCEL_SUBSCRIPTION, ['id' => $currentSubscription->id]);
            $currentSubscription->status = 'cancelled';
            $currentSubscription->save();
            tenant()->update(['balance' => tenant()->balance + $currentSubscription->price]);
            return true;
        }
        return false;
    }

    static function renew(){
        /** @var self|null $currentSubscription */
        $currentSubscription = self::currentTenantSubscriptions()->first();
        if($currentSubscription && $currentSubscription->canRenew()){
            $billingCycle = $currentSubscription->billing_cycle === 'yearly' ? 'yearly' : 'monthly';
            $period = $billingCycle === 'yearly' ? 'year' : 'month';
            $startDate = $currentSubscription->end_date && carbon($currentSubscription->end_date)->isFuture()
                ? carbon($currentSubscription->end_date)
                : now();

            $plan = $currentSubscription->plan ?: Plan::query()->find($currentSubscription->plan_id);
            $systemsCount = count($currentSubscription->systems_allowed ?? []);
            $pricing = $plan
                ? app(PlanPricingService::class)->calculate($plan, $period, max(1, $systemsCount))
                : [
                    'final_price' => (float) $currentSubscription->price,
                    'free_trial_months' => 0,
                ];

            $cycleMonths = app(PlanPricingService::class)->cycleMonths($period);
            $endDate = $startDate->copy()->addMonths($cycleMonths + (int) ($pricing['free_trial_months'] ?? 0));
            $payableNow = ((int) ($pricing['free_trial_months'] ?? 0) > 0) ? 0.0 : (float) ($pricing['final_price'] ?? 0);

            Subscription::create([
                'tenant_id' => $currentSubscription->tenant_id,
                'plan_id' => $currentSubscription->plan_id,
                'plan_details' => array_merge($currentSubscription->plan_details ?? [], [
                    'pricing' => $pricing,
                    'selected_systems' => $currentSubscription->systems_allowed,
                ]),
                'currency_id' => $currentSubscription->currency_id,
                'price' => $payableNow,
                'systems_allowed' => $currentSubscription->systems_allowed,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'paid',
                'billing_cycle' => $currentSubscription->billing_cycle,
            ]);
            AuditLog::log(AuditLogActionEnum::RENEW_SUBSCRIPTION, ['id' => $currentSubscription->id]);
            return true;
        }
        return false;
    }

    function withEndAfterDays($days)
    {
        $endDate = carbon($this->end_date);
        $now = now();
        return $endDate->greaterThan($now) && $endDate->lessThanOrEqualTo($now->copy()->addDays($days));
    }
}
