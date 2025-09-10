<?php

namespace App\Livewire\Admin\PaymentMethods;

use App\Services\BranchService;
use App\Services\PaymentMethodService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class PaymentMethodsList extends Component
{
    use LivewireOperations, WithPagination;
    private $paymentMethodService, $branchService;
    public $current;
    public $data = [];

    public $rules = [
        'name' => 'required|string|max:255',
        'branch_id' => 'nullable',
    ];

    function boot() {
        $this->paymentMethodService = app(PaymentMethodService::class);
        $this->branchService = app(BranchService::class);
    }

    function setCurrent($id) {
        $this->current = $this->paymentMethodService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this payment method', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'Payment method not found');
            return;
        }

        $this->paymentMethodService->delete($this->current->id);

        $this->popup('success', 'Payment method deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save() {
        if (!$this->validator()) return;

        $this->paymentMethodService->save($this->current?->id, $this->data);

        $this->popup('success', 'Payment method saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $paymentMethods = $this->paymentMethodService->list([], [], 10, 'id');
        $branches = $this->branchService->activeList();
        return view('livewire.admin.payment-methods.payment-methods-list',get_defined_vars());
    }
}
