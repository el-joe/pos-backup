<?php

namespace App\Livewire\Central\CPanel\SubscriptionRequests;

use App\Models\SubscriptionRequest;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class SubscriptionRequestsList extends Component
{
    use LivewireOperations, WithPagination;

    public $current;

    function setCurrent($id)
    {
        $this->current = SubscriptionRequest::with(['tenant', 'plan', 'paymentMethod'])->find($id);
    }

    function viewDetails($id)
    {
        $this->setCurrent($id);
    }

    function closeDetails()
    {
        $this->current = null;
    }

    function changeStatus($id, $status)
    {
        if (cpanelAdmin()->type !== 'super_admin') {
            $this->popup('error', 'You are not authorized to perform this action');
            return;
        }

        $this->setCurrent($id);
        $this->current->update(['status' => $status]);

        $this->closeDetails();
        $this->popup('success', 'Subscription request '.$status);
    }

    public function render()
    {
        $subscriptionRequests = SubscriptionRequest::with(['tenant', 'plan', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withPath(route('cpanel.subscription-requests.list'));

        return view('livewire.central.cpanel.subscription-requests.subscription-requests-list', get_defined_vars());
    }
}
