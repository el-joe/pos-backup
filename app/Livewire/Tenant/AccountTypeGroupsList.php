<?php

namespace App\Livewire\Tenant;

use App\Models\Tenant\AccountTypeGroup;
use App\Traits\LivewireOperations;
use Livewire\Component;

class AccountTypeGroupsList extends Component
{
    use LivewireOperations;

    public $current;
    public $data = [];

    public $model = AccountTypeGroup::class;

    public $rules = [
        'name' => 'required'
    ];

    function setCurrent($id) {
        $this->current = $model = $this->model::find($id);

        $this->data = [
            'name'=>$model?->name ?? ""
        ];
    }

    function save() {

        if(!$this->validator()) return;


        $model = $this->current;

        if(!$model){
            $model = new $this->model();
        }

        $model->name = $this->data['name'];
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
        $accountTypeGroups = $this->model::query();

        $accountTypeGroups = $accountTypeGroups->paginate(20);
        return view('livewire.tenant.account-type-groups-list',get_defined_vars());
    }
}
