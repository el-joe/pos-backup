<?php

namespace App\Livewire\Central\CPanel\WalletTopupRequests;

use App\Models\WalletTopupRequest;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class WalletTopupRequestsList extends Component
{
    use LivewireOperations, WithPagination;

    public $current;

    function setCurrent($id)
    {
        $this->current = WalletTopupRequest::with(['tenant', 'paymentMethod'])->find($id);
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
        $this->popup('success', 'Wallet top-up request '.$status);
    }

    public function render()
    {
        $topupRequests = WalletTopupRequest::with(['tenant', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withPath(route('cpanel.wallet-topup-requests.list'));

        return view('livewire.central.cpanel.wallet-topup-requests.wallet-topup-requests-list', get_defined_vars());
    }
}
