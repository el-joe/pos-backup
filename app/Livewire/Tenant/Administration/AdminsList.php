<?php

namespace App\Livewire\Tenant\Administration;

use App\Models\Tenant\Admin;
use App\Traits\LivewireOperations;
use Livewire\Component;

class AdminsList extends Component
{
    use LivewireOperations;

    public $current;
    public $data = [];

    public $model = Admin::class;

    public $rules = [
        'name' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
    ];

    function setCurrent($id) {
        $this->current = $model = $this->model::find($id);

        $this->data = [
            'name'=>$model?->name ?? "",
            'phone'=>$model?->phone ?? "",
            'email'=>$model?->email ?? "",
            'active'=>($model?->active ?? 0)==1,
        ];
    }

    function save() {
        if(!$this->current) {
            $this->rules['password'] = 'required|min:6';
        }else{
            unset($this->rules['password']);
        }

        if(!$this->validator()) return;


        $model = $this->current;

        if(!$model){
            $model = new $this->model();
        }
        foreach($this->data as $key=>$value){
            if($key == 'password' && empty($value)) continue;

            $model->{$key} = $value;
        }

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
        $admins = $this->model::query();

        $admins = $admins->paginate(20);

        return view('livewire.tenant.administration.admins-list',get_defined_vars());
    }
}
