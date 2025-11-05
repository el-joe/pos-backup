<?php

namespace App\Livewire\Admin\Reports\Sales;

use App\Helpers\SaleHelper;
use App\Models\Tenant\Branch;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class BranchSalesReport extends Component
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
        $rows = Branch::with('sales.saleItems')->get();

        $this->report = $rows;
    }

    public function render()
    {
        return layoutView('reports.sales.branch-sales-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
