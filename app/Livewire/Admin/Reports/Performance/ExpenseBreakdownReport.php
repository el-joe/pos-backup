<?php

namespace App\Livewire\Admin\Reports\Performance;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ExpenseBreakdownReport extends Component
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

        $this->report = DB::table('transaction_lines')
            ->join('accounts', function($join) {
                $join->on('accounts.id', '=', 'transaction_lines.account_id')
                     ->where('accounts.type', AccountTypeEnum::EXPENSE);
            })
            ->join('transactions', function($join) {
                $join->on('transactions.id', '=', 'transaction_lines.transaction_id')
                ->where('transactions.reference_type' , Expense::class);
            })
            ->leftJoin('expenses', 'expenses.id', '=', 'transactions.reference_id')
            ->leftJoin('expense_categories', 'expense_categories.id', '=', 'expenses.expense_category_id')
            ->select(
                'expense_categories.id as category_id',
                // check if no name then set default name 'Uncategorized'
                DB::raw("COALESCE(expense_categories.name, 'Uncategorized') as category_name"),
                DB::raw("$totalDebit as total_debit"),
                DB::raw("$totalCredit as total_credit"),
            )
            ->whereBetween('transaction_lines.created_at', [$fromDate, $toDate])
            ->groupBy('expense_categories.id')
            ->get();
    }

    public function render()
    {
        if (empty($this->report)) {
            $this->loadReport();
        }
        return view('livewire.admin.reports.performance.expense-breakdown-report');
    }
}
