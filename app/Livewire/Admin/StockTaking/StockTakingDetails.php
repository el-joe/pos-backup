<?php

namespace App\Livewire\Admin\StockTaking;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\StockTakingService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class StockTakingDetails extends Component
{
    use LivewireOperations;

    public $id,$stockTake;
    private $stockTakingService;

    #[Url]
    public $activeTab = 'details';

    public $stProductId;

    public function boot(){
        $this->stockTakingService = app(StockTakingService::class);
    }

    function mount() {
        $this->stockTake = $this->stockTakingService->first([
            'branch','user','products' => [
                'stock' => ['product','unit']
            ]
        ],[
            'id' => $this->id
        ]);
    }

    function returnStockAlert($id) {
        $this->stProductId = $id;

        AuditLog::log(AuditLogActionEnum::RETURN_STOCK_TAKING_PRODUCT_TRY, ['id' => $id]);

        $this->confirm('returnStock', 'warning', 'Are you sure?', 'You want to return this stock', 'Yes, return it!');
    }

    function returnStock() {
        $this->stockTakingService->returnStock($this->stProductId);

        AuditLog::log(AuditLogActionEnum::RETURN_STOCK_TAKING_PRODUCT, ['id' => $this->stProductId]);

        $this->popup('success', 'Stock returned successfully');
    }

    public function render()
    {
        return layoutView('stock-taking.stock-taking-details');
    }
}
