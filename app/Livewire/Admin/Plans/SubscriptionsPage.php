<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SubscriptionsPage extends Component
{
    function renewSubscription()
    {
        $currentSubscription = Subscription::currentTenantSubscriptions()->first();
        if($currentSubscription && $currentSubscription->canRenew() && adminCan('subscriptions.renew')){
            try{
                DB::beginTransaction();
                $currentSubscription->renew();
                DB::commit();
            }catch (\Exception $e) {
                DB::rollBack();
                $this->popup('error','Error occurred while renewing subscription: '.$e->getMessage(), 'center');
                return;
            }
            $this->popup('success', 'Subscription renewed successfully', 'center');
        }else{
            $this->popup('error', 'Cannot renew this subscription', 'center');
        }
    }

    public function changePlanUrl(string $billingCycle): string
    {
        $centralDomain = config('tenancy.central_domains')[0] ?? request()->getHost();
        $scheme = request()->isSecure() ? 'https' : 'http';

        $params = http_build_query([
            'tenant' => tenant('id'),
            'billing_cycle' => $billingCycle,
        ]);

        return "{$scheme}://{$centralDomain}/pricing?{$params}";
    }

    public function render()
    {
        $currentSubscription = Subscription::currentTenantSubscriptions()->first();
        if(!$currentSubscription){
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

        return layoutView('plans.subscriptions-page', get_defined_vars());
    }
}
