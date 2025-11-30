<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Subscription;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SubscriptionsPage extends Component
{
    public function render()
    {
        $currentSubscription = Subscription::current()->paid()->forTenant(tenant('id'))->first();
        if(!$currentSubscription){
            $currentSubscription = Subscription::forTenant(tenant('id'))
                ->orderBy('end_date', 'desc')->first();
        }
        $subscriptions = Subscription::forTenant(tenant('id'))
            ->orderBy('start_date', 'desc')->get();
        return layoutView('plans.subscriptions-page', get_defined_vars());
    }
}
