<?php

namespace App\Livewire\Admin\Discounts;

use App\Services\BranchService;
use App\Services\DiscountService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class DiscountsList extends Component
{

    use LivewireOperations, WithPagination;
    private $discountService, $branchService;
    public $current;
    public $data = [];

    public $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255',
        'type' => 'required|string|in:fixed,rate',
        'value' => 'required|numeric',
        'max_discount_amount' => 'nullable|numeric',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'usage_limit' => 'nullable|integer',
        'branch_id' => 'nullable|exists:branches,id',
        'sales_threshold' => 'nullable|numeric',
    ];

    function boot() {
        $this->discountService = app(DiscountService::class);
        $this->branchService = app(BranchService::class);
    }

    function setCurrent($id) {
        $this->current = $this->discountService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this discount', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'Discount not found');
            return;
        }

        $this->discountService->delete($this->current->id);

        $this->popup('success', 'Discount deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save() {
        if (!$this->validator()) return;

        $this->discountService->save($this->current?->id, $this->data);

        $this->popup('success', 'Discount saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $discounts = $this->discountService->list(perPage : 10 , orderByDesc: 'id');
        $branches = $this->branchService->activeList();
        return view('livewire.admin.discounts.discounts-list', get_defined_vars());
    }
}
