<?php

namespace App\Livewire\Admin\SaleRequests;

use App\Services\BranchService;
use App\Services\SaleRequestService;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithPagination;

class SaleRequestsList extends Component
{
    use LivewireOperations, WithPagination;

    private SaleRequestService $saleRequestService;
    private BranchService $branchService;
    private UserService $userService;

    public bool $collapseFilters = false;
    public ?string $export = null;
    public array $filters = [];

    public function boot(): void
    {
        $this->saleRequestService = app(SaleRequestService::class);
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
            $requests = $this->saleRequestService->list(relations: ['customer', 'branch'], filter: $this->filters, orderByDesc: 'id');

            $data = $requests->map(function ($request, $loop) {
                return [
                    'loop' => $loop + 1,
                    'quote_number' => $request->quote_number,
                    'customer' => $request->customer?->name,
                    'branch' => $request->branch?->name,
                    'status' => $request->status?->label() ?? (string) $request->status,
                    'total_amount' => $request->grand_total_amount,
                ];
            })->toArray();

            $columns = ['loop', 'quote_number', 'customer', 'branch', 'status', 'total_amount'];
            $headers = ['#', 'Quote No.', 'Customer', 'Branch', 'Status', 'Total Amount'];
            $fullPath = exportToExcel($data, $columns, $headers, 'sale-requests');

            $this->redirectToDownload($fullPath);
        }

        $requests = $this->saleRequestService->list(relations: ['customer', 'branch'], filter: $this->filters, perPage: 10, orderByDesc: 'id');
        $branches = $this->branchService->activeList();
        $customers = $this->userService->customersList();

        return layoutView('sale-requests.sale-requests-list', get_defined_vars())
            ->title('Sale Requests');
    }
}
