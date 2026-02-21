<?php

namespace App\Livewire\Admin\Reports\Sales;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ProductSalesReport extends Component
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
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');

        $soldQtyRaw = "sale_items.qty - COALESCE(sale_items.refunded_qty, 0)";

        $rows = DB::table('sale_items')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                // Net Quantity Sold=Sales Quantity−Return Quantity
                DB::raw("SUM($soldQtyRaw) as quantity_sold"),
                // Total cost = soldQty * unit_cost
                DB::raw("SUM(sale_items.unit_cost * ($soldQtyRaw)) as total_cost"),
                // Total Revenue = soldQty * sell_price
                DB::raw("SUM(sale_items.sell_price * ($soldQtyRaw)) as total_revenue")
            )
            ->whereBetween('sales.created_at', [$this->from_date, $toDate])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('quantity_sold')
            ->get();

        $this->report = $rows;
    }

    public function render()
    {
        return layoutView('reports.sales.product-sales-report',[
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
