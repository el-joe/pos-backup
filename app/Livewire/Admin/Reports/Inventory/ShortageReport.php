<?php


namespace App\Livewire\Admin\Reports\Inventory;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ShortageReport extends Component
{
    public $report = [];

    public function render()
    {
        $this->report = $this->getShortageReport();
        return view('livewire.admin.reports.inventory.shortage-report', [
            'report' => $this->report,
        ]);
    }

    protected function getShortageReport()
    {
        // Aggregate shortages from stock_takings and stock_taking_products
    $query = DB::table('stock_taking_products')
            ->join('stock_takings', 'stock_taking_products.stock_taking_id', '=', 'stock_takings.id')
            ->join('stocks', 'stock_taking_products.stock_id', '=', 'stocks.id')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->join('units', 'stocks.unit_id', '=', 'units.id')
            ->join('branches', 'stock_takings.branch_id', '=', 'branches.id')
            ->select([
                DB::raw('CONCAT(products.name, " (", units.name, ")") as product_name'),
                'branches.name as branch_name',
                DB::raw('SUM(GREATEST(stock_taking_products.current_qty - stock_taking_products.actual_qty, 0)) as shortage_qty'),
                DB::raw('SUM(GREATEST(stock_taking_products.current_qty - stock_taking_products.actual_qty, 0) * stock_taking_products.unit_cost) as shortage_value'),
            ])
            ->groupBy('products.id', 'products.name', 'units.name', 'branches.name')
            ->orderByDesc('shortage_value');

        return $query->get();
    }
}
