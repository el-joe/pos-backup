<?php

namespace App\Livewire\Tenant\Account;

use App\Models\Tenant\Account;
use App\Models\Tenant\AccountTypeGroup;
use App\Traits\LivewireOperations;
use Livewire\Component;

class AccountsList extends Component
{
    use LivewireOperations;

    public $current;
    public $data = [];

    public $model = Account::class;

    public $rules = [
        'code' => 'required',
        'account_type_id'=>'required',
        'model_type'=>'required',
        'model_id'=>'required'
    ];

    function setCurrent($id) {
        $this->current = $model = $this->model::find($id);

        $this->data = [
            'code'=>$model?->code ?? "",
            'account_type_id'=>$model?->account_type_id ?? "",
            'model_type'=> $model?->model->type . 's' ?? "",
            'model_id' => $model?->model_id ?? ""
        ];
    }

    function save() {

        if(!$this->validator()) return;


        $model = $this->current;

        if(!$model){
            $model = new $this->model();
        }

        $model->code = $this->data['code'];
        $model->account_type_id = $this->data['account_type_id'];
        $model->model_type = Account::RELATED_TO[$this->data['model_type']];
        $model->model_id = $this->data['model_id'];
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
        $groups = AccountTypeGroup::with([
            'accountTypes'=> fn($q)=>$q->whereParentId(0)->with('children')
            ])->get();
        $accounts = $this->model::query();

        $accounts = $accounts->paginate(20);
        return view('livewire.tenant.account.accounts-list',get_defined_vars());
    }
}
