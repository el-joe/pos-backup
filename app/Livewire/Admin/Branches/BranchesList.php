<?php

namespace App\Livewire\Admin\Branches;

use App\Models\Subscription;
use App\Services\BranchService;
use App\Services\TaxService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class BranchesList extends Component
{
    use LivewireOperations,WithPagination;
    private $branchService, $taxService;
    public $current;
    public $data = [];

    public $filters = [];
    public $collapseFilters = false;

    public $export;

    public $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',
        'address' => 'nullable|string|max:500',
        'website' => 'nullable|url|max:255',
        'tax_id' => 'nullable|exists:taxes,id',
    ];

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

    function save() {

        if(!$this->current?->id){
            $currentSubscription = Subscription::currentTenantSubscriptions()->first();
            $limit = $currentSubscription?->plan?->features['branches']['limit'] ?? 999999;
            $totalBranches = $this->branchService->count();

            if($totalBranches >= $limit){
                $this->popup('error','Branch limit reached. Please upgrade your subscription to add more branches.');
                return;
            }
        }

        if(!$this->validator())return;

        $this->branchService->save($this->current?->id,$this->data);

        $this->popup('success','Branch saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

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
