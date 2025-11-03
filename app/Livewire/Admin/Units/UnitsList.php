<?php

namespace App\Livewire\Admin\Units;

use App\Models\Tenant\Unit;
use App\Services\UnitService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;

class UnitsList extends Component
{
    use LivewireOperations;

    public $current;
    private $unitService;
    public $data = [
        'parent_id'=>0
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

    function setCurrent($id) {
        $this->current = $this->unitService->find($id);
        if($this->current) {
            $this->data = [
                'name'=>$this->current?->name ?? "",
                'parent_id'=>$this->current?->parent_id ?? 0,
                'count'=>$this->current?->count ?? 0,
                'active'=>(bool)($this->current?->active ?? 0),
            ];
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
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this Unit', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'Unit not found');
            return;
        }

        $this->unitService->delete($this->current->id);

        $this->popup('success', 'Unit deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $units = Unit::paginate(10);

        $parents = Unit::with('children')
            ->where('parent_id', 0)
            ->orWhereNull('parent_id')
            ->get();

        return layoutView('units.units-list', get_defined_vars())
            ->title(__('general.titles.units'));
    }
}
