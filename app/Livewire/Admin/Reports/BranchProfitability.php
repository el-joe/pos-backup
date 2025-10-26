<?php

namespace App\Livewire\Admin\Reports;

use App\Enums\AccountTypeEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class BranchProfitability extends Component
{
    public $from_date;
    public $to_date;
    public $branch_id;
    public $report = [];

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->branch_id = null;
        $this->loadReport();
    }

    public function updatedFromDate() { $this->loadReport(); }
    public function updatedToDate() { $this->loadReport(); }
    public function updatedBranchId() { $this->loadReport(); }

    public function applyFilter() { $this->loadReport(); }
    public function resetFilters()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->branch_id = null;
        $this->loadReport();
    }

    public function loadReport()
    {
        $toDate = Carbon::parse($this->to_date)->endOfDay()->format('Y-m-d H:i:s');

        $sales = AccountTypeEnum::SALES->value;
        $cogs = AccountTypeEnum::COGS->value;
        $expense = AccountTypeEnum::EXPENSE->value;

        // Match the query semantics (credit - debit for revenue, debit - credit for costs)
        $salesRevenue = "SUM(CASE WHEN accounts.type = '$sales' THEN IF(transaction_lines.type = 'credit', transaction_lines.amount, (transaction_lines.amount * -1)) ELSE 0 END)";
        $cogsSum = "SUM(CASE WHEN accounts.type = '$cogs' THEN IF(transaction_lines.type = 'debit', transaction_lines.amount, (transaction_lines.amount * -1)) ELSE 0 END)";
        $expensesSum = "SUM(CASE WHEN accounts.type = '$expense' THEN IF(transaction_lines.type = 'debit', transaction_lines.amount, (transaction_lines.amount * -1)) ELSE 0 END)";
        $salesDiscount = "SUM(CASE WHEN accounts.type = '" . AccountTypeEnum::SALES_DISCOUNT->value . "' THEN IF(transaction_lines.type = 'debit', transaction_lines.amount, (transaction_lines.amount * -1)) ELSE 0 END)";
        $salesReturn = "SUM(CASE WHEN accounts.type = '" . AccountTypeEnum::SALES_RETURN->value . "' THEN IF(transaction_lines.type = 'debit', transaction_lines.amount, (transaction_lines.amount * -1)) ELSE 0 END)";
        $inventoryShortage = "SUM(CASE WHEN accounts.type = '" . AccountTypeEnum::INVENTORY_SHORTAGE->value . "' THEN IF(transaction_lines.type = 'debit', transaction_lines.amount, (transaction_lines.amount * -1)) ELSE 0 END)";
        $purchaseDiscountSum = "SUM(CASE WHEN accounts.type = '" . AccountTypeEnum::PURCHASE_DISCOUNT->value . "' THEN IF(transaction_lines.type = 'credit', transaction_lines.amount, (transaction_lines.amount * -1)) ELSE 0 END)";
        // No explicit 'revenue' enum exists, so other income equals purchase discount here
        $otherIncome = $purchaseDiscountSum;
        $netProfit = "$salesRevenue + $purchaseDiscountSum - $cogsSum - $expensesSum - $salesDiscount - $salesReturn - $inventoryShortage";

        $query = DB::table('transaction_lines')
            ->join('transactions', 'transactions.id', '=', 'transaction_lines.transaction_id')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->leftJoin('branches', 'branches.id', '=', 'transactions.branch_id')
            ->select(
                'transactions.branch_id as branch_id',
                DB::raw('COALESCE(branches.name, CONCAT("Branch #", transactions.branch_id)) as branch_name'),
                DB::raw("$salesRevenue as sales_revenue"),
                DB::raw("$cogsSum as cogs"),
                DB::raw("$expensesSum as expenses"),
                DB::raw("$otherIncome as other_income"),
                DB::raw("$netProfit as net_profit")
            )
            ->whereBetween('transactions.date', [$this->from_date, $toDate])
            ->groupBy('transactions.branch_id', 'branches.name')
            ->orderByDesc('net_profit');

        if ($this->branch_id) {
            $query->where('transactions.branch_id', $this->branch_id);
        }

        $this->report = $query->get();
    }
    public function render()
    {
        return view('livewire.admin.reports.branch-profitability', [
            'report' => $this->report,
        ]);
    }
}
