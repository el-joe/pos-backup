<?php

namespace App\Livewire\Admin\Reports\Inventory;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class StockValuationReport extends Component
{
    public $report = [];

    public function render()
    {
        $this->report = $this->getStockValuationReport();

        return layoutView('reports.inventory.stock-valuation-report', [
            'report' => $this->report,
        ]);
    }

    protected function getStockValuationReport()
    {
        // Aggregate current stock quantity and value per product
        $query = DB::table('stocks')
        ->join('products', 'products.id', '=', 'stocks.product_id')
        ->join('units', 'units.id', '=', 'products.unit_id')
        ->leftJoin('branches', 'branches.id', '=', 'stocks.branch_id')
            ->selectRaw('
                CONCAT(products.name, " (", units.name, ")") as product_name,
                branches.name as branch_name,
                SUM(stocks.qty) as stock_qty,
                stocks.unit_cost as unit_cost,
                SUM(stocks.qty * stocks.unit_cost) as stock_value
            ')
            ->groupBy('stocks.id')
            ->orderByRaw('CONCAT(products.name, " (", units.name, ")")');

        return $query->get();
    }
}
