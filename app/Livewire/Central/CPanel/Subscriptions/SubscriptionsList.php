<?php

namespace App\Livewire\Central\CPanel\Subscriptions;

use App\Models\Subscription;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class SubscriptionsList extends Component
{
    use LivewireOperations, WithPagination;

    public function render()
    {
        $subscriptions = Subscription::with('tenant', 'plan')
            ->orderBy('start_date', 'desc')
            ->paginate(10)->withPath(route('cpanel.subscriptions.list'));

        return view('livewire.central.cpanel.subscriptions.subscriptions-list', get_defined_vars());
    }
}
