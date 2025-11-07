<?php

namespace App\Livewire\Admin\Reports\Performance;

use App\Models\Tenant\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductProfitMarginReport extends Component
{
    public $from_date;
    public $to_date;
    public $report = [];

    public function mount()
    {
        $this->from_date = Carbon::now()->startOfMonth()->toDateString();
        $this->to_date = Carbon::now()->endOfDay()->toDateString();
    }

    public function updated($property)
    {
        if (in_array($property, ['from_date', 'to_date'])) {
            $this->loadReport();
        }
    }

    public function loadReport()
    {
        $products = Product::with('saleItems')->orderBy('name')->get();

        $fromDate = Carbon::parse($this->from_date)->startOfDay();
        $toDate = Carbon::parse($this->to_date)->endOfDay();

        $this->report = $products->map(function ($product) use ($fromDate, $toDate) {

            $saleItems = $product->saleItems
                ->whereBetween('created_at', [$fromDate, $toDate]);


            $totalSales = $saleItems->sum(fn($q)=>$q->grand_total);
            $totalCogs = $saleItems->sum(fn($q)=>$q->total_cost);

            $profit = $totalSales - $totalCogs;

            return (object)[
                'product_name' => $product->name,
                'total_sales' => $totalSales,
                'total_cogs' => $totalCogs,
                'profit' => $profit,
                'profit_margin_percent' => $totalSales > 0 ? round(($profit / $totalSales) * 100, 2) : 0,
            ];
        })->toArray();
    }

    public function render()
    {
        if (empty($this->report)) {
            $this->loadReport();
        }

        return layoutView('reports.performance.product-profit-margin-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
