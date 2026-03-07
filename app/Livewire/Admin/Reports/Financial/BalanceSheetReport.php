<?php

namespace App\Livewire\Admin\Reports\Financial;

use App\Enums\AccountTypeEnum;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BalanceSheetReport extends Component
{
    public $from_date;
    public $to_date;
    public $report = [];

    protected function getEndingInventoryValue(): float
    {
        return (float) DB::table('stocks')
            ->selectRaw('COALESCE(SUM(stocks.qty * stocks.unit_cost), 0) as stock_value')
            ->value('stock_value');
    }

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->loadReport();
    }

    public function updatedFromDate() { $this->loadReport(); }
    public function updatedToDate() { $this->loadReport(); }

    public function resetFilters(): void
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->loadReport();
    }

    public function loadReport()
    {
        $fromDate = carbon($this->from_date)->startOfDay()->format('Y-m-d H:i:s');
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');

        $rows = DB::table('transaction_lines')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->select(
                'accounts.type as account_type',
                DB::raw('SUM(CASE WHEN transaction_lines.type = "debit" THEN transaction_lines.amount ELSE 0 END) as debit_total'),
                DB::raw('SUM(CASE WHEN transaction_lines.type = "credit" THEN transaction_lines.amount ELSE 0 END) as credit_total')
            )
            ->whereBetween('transaction_lines.created_at', [$fromDate, $toDate])
            ->groupBy('accounts.type')
            ->get();

        $accounts = [];
        foreach ($rows as $row) {
            $accounts[$row->account_type] = [
                'debit' => $row->debit_total,
                'credit' => $row->credit_total,
            ];
        }

        $net = function (string $accountType, string $normalSide = 'debit') use ($accounts): float {
            $debit = (float) ($accounts[$accountType]['debit'] ?? 0);
            $credit = (float) ($accounts[$accountType]['credit'] ?? 0);

            return $normalSide === 'credit'
                ? ($credit - $debit)
                : ($debit - $credit);
        };

        $netEnum = function (AccountTypeEnum $accountType, string $normalSide = 'debit') use ($net): float {
            return $net($accountType->value, $normalSide);
        };

        // Asset accounts
        $assets = [
            AccountTypeEnum::BRANCH_CASH->value => $netEnum(AccountTypeEnum::BRANCH_CASH, 'debit'),
            AccountTypeEnum::CUSTOMER->value => $netEnum(AccountTypeEnum::CUSTOMER, 'debit'),
            AccountTypeEnum::CHECKS_UNDER_COLLECTION->value => $netEnum(AccountTypeEnum::CHECKS_UNDER_COLLECTION, 'debit'),
            AccountTypeEnum::FIXED_ASSET->value => $netEnum(AccountTypeEnum::FIXED_ASSET, 'debit'),
            // Note: inventory in balance sheet should be ending/actual inventory, not total purchases.
            AccountTypeEnum::INVENTORY->value => $this->getEndingInventoryValue(),
            AccountTypeEnum::VAT_RECEIVABLE->value => $netEnum(AccountTypeEnum::VAT_RECEIVABLE, 'debit'),
            AccountTypeEnum::ACCRUED_REVENUE->value => $netEnum(AccountTypeEnum::ACCRUED_REVENUE, 'debit'),
        ];

        // Liability accounts
        $liabilities = [
            AccountTypeEnum::SUPPLIER->value => $netEnum(AccountTypeEnum::SUPPLIER, 'credit'),
            AccountTypeEnum::ISSUED_CHECKS->value => $netEnum(AccountTypeEnum::ISSUED_CHECKS, 'credit'),
            AccountTypeEnum::VAT_PAYABLE->value => $netEnum(AccountTypeEnum::VAT_PAYABLE, 'credit'),
            AccountTypeEnum::UNEARNED_REVENUE->value => $netEnum(AccountTypeEnum::UNEARNED_REVENUE, 'credit'),
            AccountTypeEnum::LONGTERM_LIABILITY->value => $netEnum(AccountTypeEnum::LONGTERM_LIABILITY, 'credit'),
        ];

        // Equity
        $equity = [
            AccountTypeEnum::OWNER_ACCOUNT->value => $netEnum(AccountTypeEnum::OWNER_ACCOUNT, 'credit'),
        ];

        $total_assets = array_sum($assets);
        $total_liabilities = array_sum($liabilities);
        $total_equity = array_sum($equity);

        $this->report = [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'total_assets' => $total_assets,
            'total_liabilities' => $total_liabilities,
            'total_equity' => $total_equity,
        ];
    }

    public function render()
    {
        return layoutView('reports.financial.balance-sheet-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
