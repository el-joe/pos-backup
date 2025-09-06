<?php

namespace App\Livewire\Admin\Branches;

use App\Services\BranchService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class BranchesList extends Component
{
    use LivewireOperations,WithPagination;
    private $branchService;
    public $current;
    public $data = [];

    public $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',
        'address' => 'nullable|string|max:500',
        'website' => 'nullable|url|max:255',
    ];

    function boot() {
        $this->branchService = app(BranchService::class);
    }

    function setCurrent($id) {
        $this->current = $this->branchService->find($id);
        if($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }

        $this->dispatch('iCheck-load');
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete','warning','Are you sure?','You want to delete this branch','Yes, delete it!');
    }

    function delete() {
        if(!$this->current) {
            $this->popup('error','Branch not found');
            return;
        }

        $this->branchService->delete($this->current->id);

        $this->popup('success','Branch deleted successfully');

        $this->dismiss();

        $this->reset('current','data');
    }

    function save() {
        if(!$this->validator())return;

        $this->branchService->save($this->current?->id,$this->data);

        $this->popup('success','Branch saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $branches = $this->branchService->list(
            perPage : 10,
            orderByDesc : 'branches.created_at'
        );

        return view('livewire.admin.branches.branches-list', get_defined_vars());
    }
}
