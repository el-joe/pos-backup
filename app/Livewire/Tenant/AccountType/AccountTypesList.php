<?php

namespace App\Livewire\Tenant\AccountType;

use App\Models\Tenant\AccountType;
use App\Models\Tenant\AccountTypeGroup;
use App\Traits\LivewireOperations;
use Livewire\Component;

class AccountTypesList extends Component
{
    use LivewireOperations;

    public $current;
    public $data = [];

    public $model = AccountType::class;

    public $rules = [
        'name' => 'required',
        'account_type_group_id'=>'required'
    ];

    function setCurrent($id) {
        $this->current = $model = $this->model::find($id);

        $this->data = [
            'name'=>$model?->name ?? "",
            'account_type_group_id'=>$model?->account_type_group_id ?? "",
            'parent_id'=>$model?->parent_id ?? 0,
        ];
    }

    function save() {

        if(!$this->validator()) return;


        $model = $this->current;

        if(!$model){
            $model = new $this->model();
        }

        $model->name = $this->data['name'];
        $model->account_type_group_id = $this->data['account_type_group_id'];
        $model->parent_id = $this->data['parent_id'] ?? 0;
        $model->save();

        $this->swal('Success!','Saved Successfully!','success');
        $this->dismiss();
    }

    function delete() {
        $this->current->delete();
        $this->swal('Success!','Deleted Successfully!','success');
        $this->dismiss();
    }
    public function render()
    {
        $accountTypes = $this->model::query();

        $accountTypes = $accountTypes->with('group')->paginate(20);

        $accountTypeGroups = AccountTypeGroup::with([
            'accountTypes' => fn($q)=>$q->whereParentId(0)->with('children')
        ])->get();

        return view('livewire.tenant.account-type.account-types-list',get_defined_vars());
    }
}
