<?php

namespace App\Livewire\Admin\Reports\Performance;


use App\Models\Tenant\User;
use App\Models\Tenant\Account;
use App\Models\Tenant\TransactionLine;
use App\Enums\AccountTypeEnum;
use App\Enums\UserTypeEnum;
use App\Services\UserService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class SupplierPayableReport extends Component
{
    public $report = [];
    public $from_date;
    public $to_date;

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
        $fromDate = Carbon::parse($this->from_date)->startOfDay();
        $toDate = Carbon::parse($this->to_date)->endOfDay();

        $totalDebit = "SUM(CASE WHEN transaction_lines.type = 'debit' THEN transaction_lines.amount ELSE 0 END)";
        $totalCredit = "SUM(CASE WHEN transaction_lines.type = 'credit' THEN transaction_lines.amount ELSE 0 END)";

        $this->report = app(UserService::class)->supplierDueAmountsReport($fromDate, $toDate);

            // dd($this->report);
    }

    public function render()
    {
        if (empty($this->report)) {
            $this->loadReport();
        }

        return layoutView('reports.performance.supplier-payable-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
