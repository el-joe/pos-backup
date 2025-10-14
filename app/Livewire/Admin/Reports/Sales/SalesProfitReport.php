<?php

namespace App\Livewire\Admin\Reports\Sales;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class SalesProfitReport extends Component
{
    public $from_date;
    public $to_date;
    public $report = [];

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->loadReport();
    }

    public function updatedFromDate() { $this->loadReport(); }
    public function updatedToDate() { $this->loadReport(); }

    public function loadReport()
    {
        // Sales Profit = Sales Revenue - COGS
        $rows = DB::table('sale_items')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('SUM((sale_items.qty - COALESCE(sale_items.refunded_qty,0)) * sale_items.sell_price) as sales_revenue'),
                DB::raw('SUM((sale_items.qty - COALESCE(sale_items.refunded_qty,0)) * sale_items.unit_cost) as cogs'),
                DB::raw('SUM((sale_items.qty - COALESCE(sale_items.refunded_qty,0)) * (sale_items.sell_price - sale_items.unit_cost)) as gross_profit')
            )
            ->whereBetween('sales.created_at', [$this->from_date, $this->to_date])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('gross_profit')
            ->get();

        // Calculate margin for each row
        foreach ($rows as $row) {
            $row->margin = $row->sales_revenue > 0 ? round($row->gross_profit / $row->sales_revenue * 100, 2) : 0;
        }
        $this->report = $rows;
    }

    public function render()
    {
        return view('livewire.admin.reports.sales.sales-profit-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
