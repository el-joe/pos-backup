<?php

namespace App\Livewire\Admin\StockTaking;

use App\Services\AdminService;
use App\Services\BranchService;
use App\Services\StockTakingService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;

class StockTakingList extends Component
{
    use LivewireOperations;

    private $stockTakingService , $branchService , $adminService;
    public $filters = [];
    public $export = null;
    public $collapseFilters = false;

    function boot() {
        $this->stockTakingService = app(StockTakingService::class);
        $this->branchService = app(BranchService::class);
        $this->adminService = app(AdminService::class);
    }
    public function render()
    {
        if ($this->export == 'excel') {
            $stockTaking = $this->stockTakingService->list(relations: ['branch','user'],filter: $this->filters,orderByDesc: 'id');
            #	Branch	Date	Products Count	Created by	Created at	Note
            $data = $stockTaking->map(function ($stockTaking, $loop) {
                return [
                    'loop' => $loop + 1,
                    'branch' => $stockTaking->branch?->name ?? 'N/A',
                    'date' => $stockTaking->date,
                    'products_count' => $stockTaking->products->unique('product_id')?->count() ?? 0,
                    'created_by' => $stockTaking->user?->name ?? 'N/A',
                    'created_at' => $stockTaking->created_at,
                    'note' => $stockTaking->note,
                ];
            })->toArray();
            $columns = ['loop', 'branch', 'date', 'products_count', 'created_by', 'created_at', 'note'];
            $headers = ['#', 'Branch', 'Date', 'Products Count', 'Created by', 'Created at', 'Note'];

            $fullPath = exportToExcel($data, $columns, $headers, 'stock-takings');

            $this->redirectToDownload($fullPath);
        }

        $stockAdjustments = $this->stockTakingService->list(['branch','user'],$this->filters,20,'id')
        ->withQueryString()
        ->through(function($st) {
            return [
                'id' => $st->id,
                'branch' => $st->branch?->name ?? 'N/A',
                'date' => $st->date,
                'note' => $st->note,
                'created_by' => $st->user?->name ?? 'N/A',
                'products_count' => $st->products->unique('product_id')?->count() ?? 0,
                'created_at' => $st->created_at,
            ];
        });

        $headers = [
            '#' , 'Branch' , 'Date' , 'Products Count', 'Created by' , 'Created at' , 'Note' , 'Actions'
        ];

        $actions = [];
        if(adminCan('stock_adjustments.show')) {
            $actions[] = [
                    'title' => 'Details',
                    'icon' => 'fa fa-eye',
                    'class' => 'btn btn-info btn-sm',
                    'route' => fn($row) => route('admin.stocks.adjustments.details', $row['id']),
                    'attributes' => [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'data-original-title' => 'Details',
                    ],
                ];
        }

        $columns = [
            'id' => [ 'type' => 'number'],
            'branch' => [ 'type' => 'text'],
            'date' => [ 'type' => 'date'],
            'products_count' => [ 'type' => 'number'],
            'created_by' => [ 'type' => 'text'],
            'created_at' => [ 'type' => 'datetime'],
            'note' => [ 'type' => 'text'],
            'actions' => [ 'type' => 'actions' , 'actions' => $actions]
        ];

        $branches = $this->branchService->activeList();
        $admins = $this->adminService->activeList();

        return layoutView('stock-taking.stock-taking-list',get_defined_vars());
    }
}
