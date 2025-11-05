<?php

namespace App\Livewire\Admin\Reports\Financial;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
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

    public function loadReport()
    {
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');
        $rows = DB::table('transaction_lines')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->select(
                'accounts.type as account_type',
                DB::raw('SUM(CASE WHEN transaction_lines.type = "debit" THEN transaction_lines.amount ELSE 0 END) as debit_total'),
                DB::raw('SUM(CASE WHEN transaction_lines.type = "credit" THEN transaction_lines.amount ELSE 0 END) as credit_total')
            )
            ->whereBetween('transaction_lines.created_at', [$this->from_date, $toDate])
            ->groupBy('accounts.type')
            ->get();

        $accounts = [];
        foreach ($rows as $row) {
            $accounts[$row->account_type] = [
                'debit' => $row->debit_total,
                'credit' => $row->credit_total,
            ];
        }

        // Asset accounts
        $assets = [
            'Fixed Asset' => ($accounts['fixed_asset']['debit'] ?? 0) - ($accounts['fixed_asset']['credit'] ?? 0),
            'Current Asset' => ($accounts['current_asset']['debit'] ?? 0) - ($accounts['current_asset']['credit'] ?? 0),
            'Inventory' => ($accounts['inventory']['debit'] ?? 0) - ($accounts['inventory']['credit'] ?? 0),
            'VAT Receivable' => ($accounts['vat_receivable']['debit'] ?? 0) - ($accounts['vat_receivable']['credit'] ?? 0),
            'Owner Account' => ($accounts['owner_account']['debit'] ?? 0) - ($accounts['owner_account']['credit'] ?? 0),
        ];

        // Liability accounts
        $liabilities = [
            'Long-term Liability' => ($accounts['longterm_liability']['credit'] ?? 0) - ($accounts['longterm_liability']['debit'] ?? 0),
            'VAT Payable' => ($accounts['vat_payable']['credit'] ?? 0) - ($accounts['vat_payable']['debit'] ?? 0),
        ];

        // Equity
        $equity = [
            'Owner Account' => ($accounts['owner_account']['credit'] ?? 0) - ($accounts['owner_account']['debit'] ?? 0),
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
