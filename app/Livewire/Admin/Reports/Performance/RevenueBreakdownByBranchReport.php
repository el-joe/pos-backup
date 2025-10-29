<?php

namespace App\Livewire\Admin\Reports\Performance;


use App\Enums\AccountTypeEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('layouts.admin')]
class RevenueBreakdownByBranchReport extends Component
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

        $totalDebit = 'SUM(CASE WHEN transaction_lines.type = "debit" THEN transaction_lines.amount ELSE 0 END)';
        $totalCredit = 'SUM(CASE WHEN transaction_lines.type = "credit" THEN transaction_lines.amount ELSE 0 END)';

        $this->report = DB::table('transaction_lines')
            ->join('transactions', 'transactions.id', '=', 'transaction_lines.transaction_id')
            ->join('branches', 'branches.id', '=', 'transactions.branch_id')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->select(
                'branches.id as branch_id',
                'branches.name as branch_name',
                DB::raw("$totalDebit as total_debit"),
                DB::raw("$totalCredit as total_credit"),
                DB::raw("($totalCredit - $totalDebit) as total_revenue")
            )
            ->whereIn('accounts.type', [
                AccountTypeEnum::SALES,
            ])
            ->whereBetween('transaction_lines.created_at', [$fromDate, $toDate])
            ->groupBy('branches.id', 'branches.name')
            ->orderByDesc('total_revenue')
            ->get();

    }

    public function render()
    {
        if (empty($this->report)) {
            $this->loadReport();
        }
        return view('livewire.admin.reports.performance.revenue-breakdown-by-branch-report');
    }
}
