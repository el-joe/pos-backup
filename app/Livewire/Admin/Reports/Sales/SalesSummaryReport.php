<?php

namespace App\Livewire\Admin\Reports\Sales;

use App\Enums\AccountTypeEnum;
use App\Services\SellService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SalesSummaryReport extends Component
{
    public $period = 'day'; // day, week, month
    public $report = [];
    public $from_date;
    public $to_date;

    protected $sellService;

    function boot(){
        $this->sellService = app(SellService::class);
    }

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->loadReport();
    }

    public function updatedPeriod() { $this->loadReport(); }
    public function updatedFromDate() { $this->loadReport(); }
    public function updatedToDate() { $this->loadReport(); }

    public function loadReport()
    {

        $this->report = $this->sellService->salesSummaryReport(
            $this->from_date,
            $this->to_date,
            $this->period
        );
    }

    public function render()
    {

        return layoutView('reports.sales.sales-summary-report', [
            'report' => $this->report,
            'period' => $this->period,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
