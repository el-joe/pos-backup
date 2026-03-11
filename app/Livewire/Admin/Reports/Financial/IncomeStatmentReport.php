<?php

namespace App\Livewire\Admin\Reports\Financial;

use App\Enums\AccountTypeEnum;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class IncomeStatmentReport extends Component
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
        $fromDate = carbon($this->from_date)->startOfDay()->format('Y-m-d H:i:s');
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');
        $rows = DB::table('transaction_lines')
            ->join('transactions', 'transactions.id', '=', 'transaction_lines.transaction_id')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->select(
                'accounts.type as account_type',
                DB::raw('SUM(CASE WHEN transaction_lines.type = "debit" THEN transaction_lines.amount ELSE 0 END) as debit_total'),
                DB::raw('SUM(CASE WHEN transaction_lines.type = "credit" THEN transaction_lines.amount ELSE 0 END) as credit_total')
            )
            ->whereBetween('transactions.date', [$fromDate, $toDate])
            ->groupBy('accounts.type')
            ->get();


        $accounts = [];

        foreach ($rows as $row) {
            $accounts[$row->account_type] = [
                'debit' => $row->debit_total,
                'credit' => $row->credit_total,
            ];
        }

        $net = function (AccountTypeEnum $accountType, string $normalSide = 'debit') use ($accounts): float {
            $debit = (float) ($accounts[$accountType->value]['debit'] ?? 0);
            $credit = (float) ($accounts[$accountType->value]['credit'] ?? 0);

            return $normalSide === 'credit'
                ? ($credit - $debit)
                : ($debit - $credit);
        };

        // Revenue
        $grossSales = (float) ($accounts[AccountTypeEnum::SALES->value]['credit'] ?? 0);
        $salesReturn = (float) ($accounts[AccountTypeEnum::SALES->value]['debit'] ?? 0)
            + $net(AccountTypeEnum::SALES_RETURN, 'debit');
        $salesDiscount = $net(AccountTypeEnum::SALES_DISCOUNT, 'debit');
        $revenue = $grossSales - $salesDiscount - $salesReturn;

        // Cost of Goods Sold
        $cogsBase = $net(AccountTypeEnum::COGS, 'debit');
        $inventoryShortage = $net(AccountTypeEnum::INVENTORY_SHORTAGE, 'debit');
        $purchaseDiscount = $net(AccountTypeEnum::PURCHASE_DISCOUNT, 'credit');
        // IMPORTANT: Purchase returns should NOT appear in income statement (per business rule).
        $cogs = $cogsBase + $inventoryShortage - $purchaseDiscount;

        $gross_profit = $revenue - $cogs;

        // Expenses (sum all expense-type accounts)
        $expenseTypes = [
            AccountTypeEnum::EXPENSE,
            AccountTypeEnum::FINANCE_EXPENSE,
            AccountTypeEnum::MARKETING_EXPENSE,
            AccountTypeEnum::OPERATING_EXPENSE,
            AccountTypeEnum::GENERAL_AND_ADMINISTRATIVE_EXPENSE,
            AccountTypeEnum::MAINTENANCE_AND_DEPRECIATION_EXPENSE,
            AccountTypeEnum::INVENTORY_EXPENSE,
        ];

        $expensesBreakdown = [];
        $expensesTotal = 0.0;
        foreach ($expenseTypes as $type) {
            $amount = $net($type, 'debit');
            $expensesBreakdown[$type->value] = $amount;
            $expensesTotal += $amount;
        }

        $net_profit = $gross_profit - $expensesTotal;

        $this->report = [
            'accounts' => $accounts,
            'gross_sales' => $grossSales,
            'sales_discount_total' => $salesDiscount,
            'sales_return_total' => $salesReturn,
            'cogs_total' => $cogsBase,
            'inventory_shortage_total' => $inventoryShortage,
            'purchase_discount_total' => $purchaseDiscount,
            'revenue' => $revenue,
            'cogs' => $cogs,
            'gross_profit' => $gross_profit,
            'expenses' => $expensesTotal,
            'expenses_breakdown' => $expensesBreakdown,
            'net_profit' => $net_profit,
        ];
    }

    public function render()
    {
        return layoutView('reports.financial.income-statment-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
        // return view('livewire.admin.reports.financial.income-statment-report', [
        //     'report' => $this->report,
        //     'from_date' => $this->from_date,
        //     'to_date' => $this->to_date,
        // ]);
    }
}
