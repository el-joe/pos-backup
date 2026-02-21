<?php

namespace App\Livewire\Admin\PurchaseRequests;

use App\Enums\PurchaseRequestStatusEnum;
use App\Services\PurchaseRequestService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Url;
use Livewire\Component;

class PurchaseRequestDetails extends Component
{
    use LivewireOperations;

    public int $id;
    public $request;

    #[Url]
    public string $activeTab = 'details';

    private PurchaseRequestService $purchaseRequestService;

    public function boot(): void
    {
        $this->purchaseRequestService = app(PurchaseRequestService::class);
    }

    public function mount(): void
    {
        $this->request = $this->purchaseRequestService->first($this->id, ['items', 'supplier', 'branch']);
        if (!$this->request) {
            abort(404);
        }
    }

    public function updateStatus(string $status): void
    {
        $this->request->update(['status' => $status]);
        $this->request = $this->request->refresh();
        $this->alert('success', 'Status updated.');
    }

    public function convertToOrder(): void
    {
        // $purchase = $this->purchaseRequestService->convertToPurchaseOrder($this->request->id);
        // $this->redirect(route('admin.purchases.details', $purchase->id));
    }

    public function render()
    {
        $statuses = PurchaseRequestStatusEnum::cases();
        return layoutView('purchase-requests.purchase-request-details', get_defined_vars())
            ->title('Purchase Request Details');
    }
}
