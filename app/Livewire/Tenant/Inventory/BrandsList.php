<?php

namespace App\Livewire\Tenant\Inventory;

use App\Models\Tenant\Brand;
use App\Traits\LivewireOperations;
use Livewire\Component;

class BrandsList extends Component
{
    use LivewireOperations;

    public $current;
    public $data = [];

    public $model = Brand::class;

    public $rules = [
        'name' => 'required',
        'active' => 'nullable',
    ];

    function setCurrent($id) {
        $this->current = $model = $this->model::find($id);

        $this->data = [
            'name'=>$model?->name ?? "",
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
        $brands = $this->model::query();

        $brands = $brands->paginate(20);

        return view('livewire.tenant.inventory.brands-list',get_defined_vars());
    }
}
