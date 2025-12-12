<?php

namespace App\Livewire\Admin\Branches;

use App\Models\Subscription;
use App\Services\BranchService;
use App\Services\TaxService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\On;
use Livewire\Component;

class BranchModal extends Component
{
    use LivewireOperations;

    public $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',
        'address' => 'nullable|string|max:500',
        'website' => 'nullable|url|max:255',
        'tax_id' => 'nullable|exists:taxes,id',
    ];

    public $current;
    public $data = [
        'active' => false
    ];

    private $branchService, $taxService;

    function boot() {
        $this->branchService = app(BranchService::class);
        $this->taxService = app(TaxService::class);
    }

    #[On('branch-set-current')]
    function setCurrent($id = null) {
        $this->current = $this->branchService->find($id);
        if($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }else{
            $this->reset('data');
        }
    }

    function save() {

        if(!$this->current?->id){
            $currentSubscription = Subscription::currentTenantSubscriptions()->first();
            $limit = $currentSubscription?->plan?->features['branches']['limit'] ?? 999999;
            $totalBranches = $this->branchService->count();

            if($totalBranches >= $limit){
                $this->popup('error','Branch limit reached. Please upgrade your subscription to add more branches.');
                return;
            }
        }

        if(!$this->validator())return;

        $this->branchService->save($this->current?->id,$this->data);

        $this->popup('success','Branch saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');

        $this->dispatch('re-render');
    }

    public function render()
    {
        $taxes = $this->taxService->list();
        return view('livewire.admin.branches.branch-modal',get_defined_vars());
    }
}
