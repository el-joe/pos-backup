<?php

namespace App\Livewire\Admin\Units;

use App\Models\Tenant\Unit;
use App\Services\UnitService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\On;
use Livewire\Component;

class UnitModal extends Component
{
    use LivewireOperations;
    public $current;
    private $unitService;
    public $data = [
        'parent_id'=>0,
        'active' => false
    ];

    public $rules = [
        'name' => 'required',
        'active' => 'nullable',
        'parent_id' => 'required|exists:units,id',
        'count'=> 'required|numeric'
    ];

    function boot() {
        $this->unitService = app(UnitService::class);
    }

    #[On('unit-set-current')]
    function setCurrent($id = null) {
        $this->current = $this->unitService->find($id);
        if($this->current) {
            $this->data = [
                'name'=>$this->current?->name ?? "",
                'parent_id'=>$this->current?->parent_id ?? 0,
                'count'=>$this->current?->count ?? 0,
                'active'=>(bool)($this->current?->active ?? 0),
            ];
        }else{
            $this->reset('data');
        }
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


        $this->unitService->save($this->current?->id , $this->data);

        $this->swal('Success!','Saved Successfully!','success');

        $this->dismiss();

        $this->reset('current','data');

        $this->dispatch('re-render');
    }


    public function render()
    {
        $parents = Unit::with('children')
            ->where('id', '!=', $this->current?->id ?? 0)
            ->where('parent_id', 0)
            ->orWhereNull('parent_id')
            ->get();

        return view('livewire.admin.units.unit-modal',get_defined_vars())->layout(null);
    }
}
