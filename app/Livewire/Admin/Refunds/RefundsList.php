<?php

namespace App\Livewire\Admin\Refunds;

use App\Models\Tenant\Refund;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Sale;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class RefundsList extends Component
{
    use LivewireOperations, WithPagination;


    public $export = null;

    public $filters = [];

    public $collapseFilters = false;

    public function render()
    {

        $refundsQuery = Refund::query()
            ->with(['items.product', 'items.unit'])
            ->orderByDesc('id');

        if ($this->export == 'excel') {
            $refunds = $refundsQuery->get();

            $data = $refunds->map(function ($refund, $loop) {
                return [
                    'loop' => $loop + 1,
                    'order_type' => class_basename($refund->order_type),
                    'order_id' => $refund->order_id,
                    'items_count' => $refund->items?->count() ?? 0,
                    'total' => $refund->total,
                    'reason' => $refund->reason,
                    'created_at' => $refund->created_at?->format('Y-m-d H:i') ?? null,
                ];
            })->toArray();

            $columns = ['loop', 'order_type', 'order_id', 'items_count', 'total', 'reason', 'created_at'];
            $headers = ['#', 'Order Type', 'Order ID', 'Items', 'Total', 'Reason', 'Created At'];

            $fullPath = exportToExcel($data, $columns, $headers, 'refunds');

            $this->redirectToDownload($fullPath);
        }

        $refunds = $refundsQuery->paginate(10);

        return layoutView('refunds.refunds-list', get_defined_vars())
            ->title(__('general.titles.refunds'));
    }
}
