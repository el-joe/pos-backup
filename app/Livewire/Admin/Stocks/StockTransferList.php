<?php

namespace App\Livewire\Admin\Stocks;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\BranchService;
use App\Services\StockTransferService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;

class StockTransferList extends Component
{
    use LivewireOperations;
    private $stockTransferService;

    public $collapseFilters = false;

    public $filters = [];
    public $export = null;

    public function updatedFilters(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
        $this->collapseFilters = false;
        $this->resetPage();
    }

    function boot() {
        $this->stockTransferService = app()->make(StockTransferService::class);
    }

    public function render()
    {
        if ($this->export == 'excel') {
            $stockTransfers = $this->stockTransferService->list(relations: ['items', 'fromBranch', 'toBranch'], filter: $this->filters, orderByDesc: 'created_at');

            $data = $stockTransfers->map(function ($stockTransfer, $loop) {
                // #	Ref No	From	To	Items Count	Total Quantity	Transfer Date	Status	Created At
                return [
                    'loop' => $loop + 1,
                    'ref_no' => $stockTransfer->ref_no,
                    'from_branch' => $stockTransfer->fromBranch ? $stockTransfer->fromBranch->name : __('general.messages.n_a'),
                    'to_branch' => $stockTransfer->toBranch ? $stockTransfer->toBranch->name : __('general.messages.n_a'),
                    'items_count' => $stockTransfer->items ? $stockTransfer->items->count() : 0,
                    'total_quantity' => $stockTransfer->items ? $stockTransfer->items->sum('qty') : 0,
                    'transfer_date' => dateTimeFormat($stockTransfer->transfer_date),
                    'status' => $stockTransfer->status->label(),
                    'created_at' => $stockTransfer->created_at,
                ];
            })->toArray();
            $columns = ['loop', 'ref_no', 'from_branch', 'to_branch', 'items_count', 'total_quantity', 'transfer_date', 'status', 'created_at'];
            $headers = ['#', __('general.pages.stock-transfers.ref_no'), __('general.pages.stock-transfers.from_branch'), __('general.pages.stock-transfers.to_branch'), __('general.pages.stock-transfers.items_count'), __('general.pages.stock-transfers.total_quantity'), __('general.pages.stock-transfers.transfer_date'), __('general.pages.stock-transfers.status'), __('general.pages.stock-transfers.created_at')];

            $fullPath = exportToExcel($data, $columns, $headers, 'stock_transfers');

            AuditLog::log(AuditLogActionEnum::EXPORT_STOCK_TRANSFERS, ['url' => $fullPath]);

            $this->redirectToDownload($fullPath);

            $this->export = null;
        }

        $stockTransfers = $this->stockTransferService->list(['items', 'fromBranch', 'toBranch'], $this->filters, 10, 'created_at')
        ->through(function($st) {
            return [
                'id' => $st->id,
                'from_branch' => $st->fromBranch ? $st->fromBranch->name : __('general.messages.n_a'),
                'to_branch' => $st->toBranch ? $st->toBranch->name : __('general.messages.n_a'),
                'items_count' => $st->items ? $st->items->count() : 0,
                'total_quantity' => $st->items ? $st->items->sum('qty') : 0,
                'transfer_date' => dateTimeFormat($st->transfer_date),
                'ref_no' => $st->ref_no,
                'status' => $st->status->label(),
                'status_class' => $st->status->colorClass(),
                'created_at' => dateTimeFormat($st->created_at),
            ];
        });

        $headers = [
            '#' , __('general.pages.stock-transfers.ref_no') , __('general.pages.stock-transfers.from_branch') , __('general.pages.stock-transfers.to_branch') , __('general.pages.stock-transfers.items_count') , __('general.pages.stock-transfers.total_quantity') , __('general.pages.stock-transfers.transfer_date') , __('general.pages.stock-transfers.status') , __('general.pages.stock-transfers.created_at') , __('general.pages.stock-transfers.action')
        ];

        $actions = [];
        if(adminCan('stock_transfers.show')){
            $actions[] = [
                'title' => __('general.pages.stock-transfers.details_tab'),
                'icon' => 'fa fa-eye',
                'class' => 'btn btn-info btn-sm',
                'route' => fn($row) => route('admin.stocks.transfers.details', $row['id']),
                'attributes' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'data-original-title' => __('general.pages.stock-transfers.details_tab'),
                ],
            ];
        }

        $columns = [
            'id' => ['type'=>'number'],
            'ref_no' => ['type'=>'text'],
            'from_branch' => ['type'=>'text'],
            'to_branch' => ['type'=>'text'],
            'items_count' => ['type'=>'number'],
            'total_quantity' => ['type'=>'number'],
            'transfer_date' => ['type'=>'text'],
            'status' => [
                'type'=>'badge' , 'class' => fn($row) => $row['status_class']
            ],
            'created_at' => ['type'=>'text'],
            'actions' => [ 'type' => 'actions' , 'actions' => $actions],
        ];

        $branches = app()->make(BranchService::class)->activeList();

        return layoutView('stocks.stock-transfer-list', get_defined_vars());
    }
}
