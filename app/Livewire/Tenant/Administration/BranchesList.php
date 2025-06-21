<?php

namespace App\Livewire\Tenant\Administration;

use App\Models\Tenant\Branch;
use App\Traits\LivewireOperations;
use Livewire\Component;

class BranchesList extends Component
{
    use LivewireOperations;

    public $current;
    public $data = [];

    public $model = Branch::class;

    public $rules = [
        'name' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
        'address' => "required",
        'website'=> 'required|url',
    ];

    function setCurrent($id) {
        $this->current = $model = $this->model::find($id);

        $this->data = [
            'name'=>$model?->name ?? "",
            'phone'=>$model?->phone ?? "",
            'email'=>$model?->email ?? "",
            'address'=>$model?->address ?? "",
            'website'=>$model?->website ?? "",
            'active'=>($model?->active ?? 0)==1,
        ];
    }

    function save() {

        if(!$this->validator()) return;


        $model = $this->current;

        if(!$model){
            $model = new $this->model();
        }
        foreach($this->data as $key=>$value){
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
        $branches = $this->model::query();


        $branches = $branches->paginate(20);

        return view('livewire.tenant.administration.branches-list',get_defined_vars());
    }
}
