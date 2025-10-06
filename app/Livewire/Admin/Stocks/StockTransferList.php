<?php

namespace App\Livewire\Admin\Stocks;

use App\Services\StockTransferService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class StockTransferList extends Component
{
    private $stockTransferService;

    function boot() {
        $this->stockTransferService = app()->make(StockTransferService::class);
    }

    public function render()
    {
        $stockTransfers = $this->stockTransferService->list(['items', 'fromBranch', 'toBranch'], [], 10, 'created_at')
        ->through(function($st) {
            return [
                'id' => $st->id,
                'from_branch' => $st->fromBranch ? $st->fromBranch->name : 'N/A',
                'to_branch' => $st->toBranch ? $st->toBranch->name : 'N/A',
                'items_count' => $st->items ? $st->items->count() : 0,
                'total_quantity' => $st->items ? $st->items->sum('qty') : 0,
                'transfer_date' => $st->transfer_date,
                'ref_no' => $st->ref_no,
                'status' => $st->status->label(),
                'status_class' => $st->status->colorClass(),
                'created_at' => $st->created_at,
            ];
        });

        $headers = [
            '#' , 'Ref No' , 'From' , 'To' , 'Items Count' , 'Total Quantity' , 'Transfer Date' , 'Status' , 'Created At' , 'Actions'
        ];

        $columns = [
            'id' => ['type'=>'number'],
            'ref_no' => ['type'=>'text'],
            'from_branch' => ['type'=>'text'],
            'to_branch' => ['type'=>'text'],
            'items_count' => ['type'=>'number'],
            'total_quantity' => ['type'=>'number'],
            'transfer_date' => ['type'=>'date'],
            'status' => [
                'type'=>'badge' , 'class' => fn($row) => $row['status_class']
            ],
            'created_at' => ['type'=>'datetime'],
            'actions' => [ 'type' => 'actions' , 'actions' => [
                [
                    'title' => 'Details',
                    'icon' => 'fa fa-eye',
                    'class' => 'btn btn-info btn-sm',
                    'route' => fn($row) => route('admin.stocks.transfers.details', $row['id']),
                    'attributes' => [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'data-original-title' => 'Details',
                    ],
                ],
            ]],
        ];

        return view('livewire.admin.stocks.stock-transfer-list',get_defined_vars());
    }
}
