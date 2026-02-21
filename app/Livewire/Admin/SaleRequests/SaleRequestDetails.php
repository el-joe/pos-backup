<?php

namespace App\Livewire\Admin\SaleRequests;

use App\Enums\SaleRequestStatusEnum;
use App\Services\SaleRequestService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Url;
use Livewire\Component;

class SaleRequestDetails extends Component
{
    use LivewireOperations;

    public int $id;
    public $request;

    #[Url]
    public string $activeTab = 'details';

    private SaleRequestService $saleRequestService;

    public function boot(): void
    {
        $this->saleRequestService = app(SaleRequestService::class);
    }

    public function mount(): void
    {
        $this->request = $this->saleRequestService->first($this->id, ['items', 'customer', 'branch']);
        if (!$this->request) {
            abort(404);
        }
    }

    public function updateStatus(string $status): void
    {
        $this->validate([
            'request.id' => 'required',
        ]);

        $this->request->update(['status' => $status]);
        $this->request = $this->request->refresh();
        $this->alert('success', 'Status updated.');
    }

    public function convertToOrder(): void
    {
        // $sale = $this->saleRequestService->convertToSaleOrder($this->request->id);
        // $this->redirect(route('admin.sales.details', $sale->id));
    }

    public function render()
    {
        $statuses = SaleRequestStatusEnum::cases();
        return layoutView('sale-requests.sale-request-details', get_defined_vars())
            ->title('Sale Request Details');
    }
}
