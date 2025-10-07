<?php

namespace App\Livewire\Admin\StockTaking;

use App\Services\StockTakingService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class StockTakingList extends Component
{
    private $stockTakingService;
    function boot() {
        $this->stockTakingService = app(StockTakingService::class);
    }
    public function render()
    {

        $stockAdjustments = $this->stockTakingService->list(['branch','user'],[],20,'id')
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

        $columns = [
            'id' => [ 'type' => 'number'],
            'branch' => [ 'type' => 'text'],
            'date' => [ 'type' => 'date'],
            'products_count' => [ 'type' => 'number'],
            'created_by' => [ 'type' => 'text'],
            'created_at' => [ 'type' => 'datetime'],
            'note' => [ 'type' => 'text'],
            'actions' => [ 'type' => 'actions' , 'actions' => [
                [
                    'title' => 'Details',
                    'icon' => 'fa fa-eye',
                    'class' => 'btn btn-info btn-sm',
                    'route' => fn($row) => route('admin.stocks.adjustments.details', $row['id']),
                    'attributes' => [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'data-original-title' => 'Details',
                    ],
                ],
            ]]
        ];
        return view('livewire.admin.stock-taking.stock-taking-list',get_defined_vars());
    }
}
