<?php

namespace App\Livewire\Admin\Reports\Financial;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BalanceSheetReport extends Component
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

        // Asset accounts
        $assets = [
            'Fixed Asset' => $net('fixed_asset', 'debit'),
            'Current Asset' => $net('current_asset', 'debit'),
            'Inventory' => $net('inventory', 'debit'),
            'VAT Receivable' => $net('vat_receivable', 'debit'),
        ];

        // Liability accounts
        $liabilities = [
            'Long-term Liability' => $net('longterm_liability', 'credit'),
            'VAT Payable' => $net('vat_payable', 'credit'),
            'Unearned Revenue' => $net('unearned_revenue', 'credit'),
        ];

        // Equity
        $equity = [
            'Owner Account' => $net('owner_account', 'credit'),
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
