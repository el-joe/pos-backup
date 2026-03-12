<?php

namespace App\Livewire\Admin\StockTaking;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
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
                    'branch' => $stockTaking->branch?->name ?? __('general.messages.n_a'),
                    'date' => $stockTaking->date,
                    'products_count' => $stockTaking->products->unique('product_id')?->count() ?? 0,
                    'created_by' => $stockTaking->user?->name ?? __('general.messages.n_a'),
                    'created_at' => dateTimeFormat($stockTaking->created_at),
                    'note' => $stockTaking->note,
                ];
            })->toArray();
            $columns = ['loop', 'branch', 'date', 'products_count', 'created_by', 'created_at', 'note'];
            $headers = ['#', __('general.pages.stock-taking.branch'), __('general.pages.stock-taking.date'), __('general.pages.stock-taking.products_count'), __('general.pages.stock-taking.created_by'), __('general.pages.stock-taking.created_at'), __('general.pages.stock-taking.note')];

            $fullPath = exportToExcel($data, $columns, $headers, 'stock-takings');

            AuditLog::log(AuditLogActionEnum::EXPORT_STOCK_TAKINGS, ['url' => $fullPath]);

            $this->redirectToDownload($fullPath);
        }

        $stockAdjustments = $this->stockTakingService->list(['branch','user'],$this->filters,20,'id')
        ->withQueryString()
        ->through(function($st) {
            return [
                'id' => $st->id,
                'branch' => $st->branch?->name ?? __('general.messages.n_a'),
                'date' => dateTimeFormat($st->date, true, false),
                'note' => $st->note,
                'created_by' => $st->user?->name ?? __('general.messages.n_a'),
                'products_count' => $st->products->unique('product_id')?->count() ?? 0,
                'created_at' => dateTimeFormat($st->created_at),
            ];
        });

        $headers = [
            '#' , __('general.pages.stock-taking.branch') , __('general.pages.stock-taking.date') , __('general.pages.stock-taking.products_count'), __('general.pages.stock-taking.created_by') , __('general.pages.stock-taking.created_at') , __('general.pages.stock-taking.note') , __('general.pages.stock-taking.action')
        ];

        $actions = [];
        if(adminCan('stock_adjustments.show')) {
            $actions[] = [
                    'title' => __('general.pages.stock-taking.details'),
                    'icon' => 'fa fa-eye',
                    'class' => 'inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20',
                    'route' => fn($row) => route('admin.stocks.adjustments.details', $row['id']),
                    'attributes' => [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'data-original-title' => __('general.pages.stock-taking.details'),
                    ],
                ];
        }

        $columns = [
            'id' => [ 'type' => 'number'],
            'branch' => [ 'type' => 'text'],
            'date' => [ 'type' => 'text'],
            'products_count' => [ 'type' => 'number'],
            'created_by' => [ 'type' => 'text'],
            'created_at' => [ 'type' => 'text'],
            'note' => [ 'type' => 'text'],
            'actions' => [ 'type' => 'actions' , 'actions' => $actions]
        ];

        $branches = $this->branchService->activeList();
        $admins = $this->adminService->activeList();

        return layoutView('stock-taking.stock-taking-list',get_defined_vars());
    }
}
