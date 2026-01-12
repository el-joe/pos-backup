<?php

namespace App\Livewire\Admin\PurchaseRequests;

use App\Services\BranchService;
use App\Services\PurchaseRequestService;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseRequestsList extends Component
{
    use LivewireOperations, WithPagination;

    private PurchaseRequestService $purchaseRequestService;
    private BranchService $branchService;
    private UserService $userService;

    public bool $collapseFilters = false;
    public ?string $export = null;
    public array $filters = [];

    public function boot(): void
    {
        $this->purchaseRequestService = app(PurchaseRequestService::class);
        $this->branchService = app(BranchService::class);
        $this->userService = app(UserService::class);
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
    }

    public function render()
    {
        if ($this->export === 'excel') {
            $requests = $this->purchaseRequestService->list(relations: ['supplier', 'branch'], filter: $this->filters, orderByDesc: 'id');

            $data = $requests->map(function ($request, $loop) {
                return [
                    'loop' => $loop + 1,
                    'request_number' => $request->request_number,
                    'supplier' => $request->supplier?->name,
                    'branch' => $request->branch?->name,
                    'status' => $request->status?->label() ?? (string) $request->status,
                    'total_amount' => $request->total_amount,
                ];
            })->toArray();

            $columns = ['loop', 'request_number', 'supplier', 'branch', 'status', 'total_amount'];
            $headers = ['#', 'Request No.', 'Supplier', 'Branch', 'Status', 'Total Amount'];
            $fullPath = exportToExcel($data, $columns, $headers, 'purchase-requests');

            $this->redirectToDownload($fullPath);
        }

        $requests = $this->purchaseRequestService->list(relations: ['supplier', 'branch'], filter: $this->filters, perPage: 10, orderByDesc: 'id');
        $branches = $this->branchService->activeList();
        $suppliers = $this->userService->suppliersList();

        return layoutView('purchase-requests.purchase-requests-list', get_defined_vars())
            ->title('Purchase Requests');
    }
}
