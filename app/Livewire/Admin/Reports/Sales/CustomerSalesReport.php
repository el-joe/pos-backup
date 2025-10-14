<?php

namespace App\Livewire\Admin\Reports\Sales;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class CustomerSalesReport extends Component
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
            ->select(
                'users.id as customer_id',
                'users.name as customer_name',
                DB::raw('SUM(sales.paid_amount) as total_spent'),
                DB::raw('COUNT(sales.id) as sale_count')
            )
            ->whereBetween('sales.created_at', [$this->from_date, $toDate])
            ->where('sales.paid_amount', '>', 0)
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_spent')
            ->get();

        $this->report = $rows;
    }

    public function render()
    {
        return view('livewire.admin.reports.sales.customer-sales-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
