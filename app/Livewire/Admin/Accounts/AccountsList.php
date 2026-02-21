<?php

namespace App\Livewire\Admin\Accounts;

use App\Enums\AccountTypeEnum;
use App\Services\AccountService;
use App\Services\BranchService;
use App\Services\PaymentMethodService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AccountsList extends Component
{
    use LivewireOperations,WithPagination;
    private $accountService,$branchService,$paymentMethodService;
    public $current;
    public $subPage = false;
    public $data = [];
    public $filters = [];

    function boot() {
        $this->accountService = app(AccountService::class);
        $this->branchService = app(BranchService::class);
        $this->paymentMethodService = app(PaymentMethodService::class);
    }

    function setCurrent($id) {
        $this->current = $this->accountService->find($id);

        $this->dispatch('setCurrentAccount', [
            'currentId' => $id,
            'filters' => $this->filters,
            'subPage' => $this->subPage
        ]);

        if($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete','warning','Are you sure?','You want to delete this account','Yes, delete it!');
    }

    function delete() {
        if(!$this->current) {
            $this->popup('error','Account not found');
            return;
        }

        $this->accountService->delete($this->current->id);

        $this->popup('success','Account deleted successfully');

        $this->dismiss();

        $this->reset('current','data');
    }

    #[On('setCurrentAccountIntoList')]
    public function render()
    {
        $accounts = $this->accountService->list(['branch'],[...$this->filters],10,'id');
        $accountTypes = AccountTypeEnum::cases();
        $branches = $this->branchService->activeList();
        $paymenthMethods = $this->paymentMethodService->activeList(filter : ['branch_id'=>$this->data['branch_id'] ?? null]);

        return layoutView('accounts.accounts-list', get_defined_vars(),$this->subPage ?? false)
            ->title(__('general.titles.accounts') );
    }
}
