<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Subscription;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SubscriptionsPage extends Component
{

    function cancelSubscription()
    {
        $currentSubscription = Subscription::currentTenantSubscriptions()->first();
        if($currentSubscription && $currentSubscription->canCancel() && adminCan('subscriptions.cancel')){
            $currentSubscription->cancel();
            $this->popup('success', 'Subscription cancelled successfully', 'center');
        }else{
            $this->popup('error', 'Cannot cancel this subscription', 'center');
        }
    }


    function renewSubscription()
    {
        $currentSubscription = Subscription::currentTenantSubscriptions()->first();
        if($currentSubscription && $currentSubscription->canRenew() && adminCan('subscriptions.renew')){
            $currentSubscription->renew();
            $this->popup('success', 'Subscription renewed successfully', 'center');
        }else{
            $this->popup('error', 'Cannot renew this subscription', 'center');
        }
    }

    public function render()
    {
        $currentSubscription = Subscription::currentTenantSubscriptions()->first();
        if(!$currentSubscription){
            $currentSubscription = Subscription::forTenant(tenant('id'))
                ->orderBy('end_date', 'desc')->first();
        }
        $subscriptions = Subscription::forTenant(tenant('id'))
            ->orderBy('start_date', 'desc')->get();
        return layoutView('plans.subscriptions-page', get_defined_vars());
    }
}
