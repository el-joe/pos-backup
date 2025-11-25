<?php

namespace App\Livewire\Admin\Reports\Sales;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SalesReturnReport extends Component
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

        $rows = DB::table('sales')
            ->join('users', 'users.id', '=', 'sales.customer_id')
            ->join('sale_items', 'sale_items.sale_id', '=', 'sales.id')
            ->select(
                'sales.id as sale_id',
                'sales.invoice_number',
                'users.name as customer_name',
                DB::raw('SUM(sale_items.refunded_qty) as return_count'),
                DB::raw('SUM(sale_items.refunded_qty * sale_items.sell_price) as return_amount')
            )
            ->whereBetween('sales.created_at', [$this->from_date, $toDate])
            ->where('sale_items.refunded_qty', '>', 0)
            ->groupBy('sales.id', 'sales.invoice_number', 'users.name')
            ->orderByDesc('return_amount')
            ->get();

        $this->report = $rows;
    }

    public function render()
    {
        return layoutView('reports.sales.sales-return-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
