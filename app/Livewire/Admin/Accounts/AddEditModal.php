<?php

namespace App\Livewire\Admin\Accounts;

use App\Enums\AccountTypeEnum;
use App\Services\BranchService;
use App\Services\PaymentMethodService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class AddEditModal extends Component
{
    use LivewireOperations;

    protected $accountService;

    function boot() {
        $this->accountService = app(\App\Services\AccountService::class);
    }

    public $rules = [
        'name' => 'required|string|max:255',
        'code' => 'nullable|string|max:50',
        'type' => 'required',
        'branch_id' => 'nullable',
        'payment_method_id' => 'required',
    ];

    public $data = [];
    public $current;
    public $filters = [];
    public $subPage = false;

    #[On('setCurrentAccount')]
    function updateCurrent($data){
        list($currentId,$filters,$subPage) = array_values($data);
        $this->filters = $filters;
        $this->subPage = $subPage;
        $this->setCurrent($currentId);
    }

    function setCurrent($id) {
        $this->current = $this->accountService->find($id);
        if($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }else{
            $this->data = [];
        }
    }


    function save() {
        if($this->subPage && !isset($this->data['type'])){
            unset($this->rules['type']);
        }
        if(!$this->validator())return;

        $data = $this->data;

        if($this->filters['model_id'] ?? null) {
            $data['model_type'] = $this->filters['model_type'];
            $data['model_id'] = $this->filters['model_id'];
        }

        if(!isset($this->data['type'])){
            $data['type'] = ($this->filters['model_type'])::find($this->filters['model_id'])?->type?->value;
        }
        try{
            DB::beginTransaction();
            $this->accountService->save($this->current?->id,$data);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $this->popup('error','Error occurred while saving account: '.$e->getMessage());
            return;
        }

        $this->popup('success','Account saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');

        $this->dispatch('setCurrentAccountIntoList');
    }

    public function render()
    {
        $accountTypes = AccountTypeEnum::cases();
        $branches = app(BranchService::class)->activeList();
        $paymenthMethods = app(PaymentMethodService::class)->activeList(filter : ['branch_id'=>$this->data['branch_id'] ?? null]);

        return view('livewire.'. defaultLayout() .'.accounts.add-edit-modal',get_defined_vars());
    }
}
