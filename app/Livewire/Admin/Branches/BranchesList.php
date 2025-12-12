<?php

namespace App\Livewire\Admin\Branches;

use App\Models\Subscription;
use App\Services\BranchService;
use App\Services\TaxService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class BranchesList extends Component
{
    use LivewireOperations,WithPagination;
    private $branchService, $taxService;
    public $current;
    public $data = [
        'active' => false
    ];

    public $filters = [];
    public $collapseFilters = false;

    public $export;

    function boot() {
        $this->branchService = app(BranchService::class);
        $this->taxService = app(TaxService::class);
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


    #[On('re-render')]
    public function render()
    {
        if ($this->export == 'excel') {
            $branches = $this->branchService->list(
                orderByDesc : 'branches.created_at',
                filter : $this->filters
            );

            $data = $branches->map(function ($branch, $loop) {
                return [
                    'loop' => $loop + 1,
                    'name' => $branch->name,
                    'phone' => $branch->phone,
                    'email' => $branch->email,
                    'address' => $branch->address,
                    'active' => $branch->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'name', 'phone', 'email','address', 'active'];
            $headers = ['#', 'Name', 'Phone', 'Email', 'Address', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'branches');

            $this->redirectToDownload($fullPath);
        }

        $branches = $this->branchService->list(
            perPage : 10,
            orderByDesc : 'branches.created_at',
            filter : $this->filters
        );


        $taxes = $this->taxService->list();

        return layoutView('branches.branches-list', get_defined_vars())
            ->title(__('general.titles.branches'));
    }
}
