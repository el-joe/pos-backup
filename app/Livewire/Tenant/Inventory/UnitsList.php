<?php

namespace App\Livewire\Tenant\Inventory;

use App\Models\Tenant\Unit;
use App\Traits\LivewireOperations;
use Livewire\Component;

class UnitsList extends Component
{
    use LivewireOperations;

    public $current;
    public $data = [
        'parent'=>0
    ];

    public $model = Unit::class;

    public $rules = [
        'name' => 'required',
        'active' => 'nullable',
        'parent_id' => 'required|exists:units,id',
        'count'=> 'required|numeric'
    ];

    function setCurrent($id) {
        $this->current = $model = $this->model::find($id);

        $this->data = [
            'name'=>$model?->name ?? "",
            'parent_id'=>$model?->parent_id ?? 0,
            'count'=>$model?->count ?? 0,
            'active'=>($model?->active ?? 0)==1,
        ];
    }

    function save() {

        if($this->data['parent_id'] == 0){
            unset($this->rules['parent_id']);
            $this->data['count'] = 1;
        }else{
            $this->rules['parent_id'] = 'required|exists:units,id';
            $this->rules['count'] = 'required|numeric|min:2';
        }

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
        $units = $this->model::query();

        $units = $units->paginate(20);

        $parents = $this->model::with('children')->whereParentId(0)->get();

        return view('livewire.tenant.inventory.units-list',get_defined_vars());
    }
}
