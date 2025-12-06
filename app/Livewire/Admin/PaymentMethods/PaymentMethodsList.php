<?php

namespace App\Livewire\Admin\PaymentMethods;

use App\Services\BranchService;
use App\Services\PaymentMethodService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentMethodsList extends Component
{
    use LivewireOperations, WithPagination;
    private $paymentMethodService, $branchService;
    public $current;
    public $data = [
        'active' => false,
    ];

    public $rules = [
        'name' => 'required|string|max:255',
        'branch_id' => 'nullable',
    ];

    public $collapseFilters = false;
    public $filters = [];
    public $export;

    function boot() {
        $this->paymentMethodService = app(PaymentMethodService::class);
        $this->branchService = app(BranchService::class);
    }

    function mount(){
        if(admin()->branch_id){
            $this->data['branch_id'] = admin()->branch_id;
        }
    }

    function setCurrent($id) {
        $this->current = $this->paymentMethodService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->current['active'];
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
        if ($this->export == 'excel') {
            $paymentMethods = $this->paymentMethodService->list(relations: [], filter: $this->filters, orderByDesc: 'id');

            $data = $paymentMethods->map(function ($paymentMethod, $loop) {
                return [
                    'loop' => $loop + 1,
                    'name' => $paymentMethod->name,
                    'branch' => $paymentMethod->branch?->name,
                    'active' => $paymentMethod->active ? 'Active' : 'Inactive',
                ];
            })->toArray();

            $columns = ['loop', 'name', 'branch', 'active'];
            $headers = ['#', 'Name', 'Branch', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'payment-methods');

            $this->redirectToDownload($fullPath);
        }

        $paymentMethods = $this->paymentMethodService->list(relations: [], filter: $this->filters, perPage: 10, orderByDesc: 'id');
        $branches = $this->branchService->activeList();

        return layoutView('payment-methods.payment-methods-list', get_defined_vars())
            ->title(__( 'general.titles.payment-methods' ));
    }
}
