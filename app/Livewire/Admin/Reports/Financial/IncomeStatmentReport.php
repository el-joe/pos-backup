<?php

namespace App\Livewire\Admin\Reports\Financial;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
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
        // 🧠 Quick Summary
        //     | Section          | Formula                                                 | Notes                            |
        //     | ---------------- | ------------------------------------------------------- | -------------------------------- |
        //     | **Revenue**      | Sales – Returns – Discounts                             | From credits/debits              |
        //     | **COGS**         | COGS + Shortage – Purchase Returns – Purchase Discounts | Don’t include inventory directly |
        //     | **Gross Profit** | Revenue – COGS                                          |                                  |
        //     | **Expenses**     | Expenses + VAT Payable – VAT Receivable + Liabilities   | Optional to separate VAT         |
        //     | **Net Profit**   | Gross Profit – Expenses                                 | Final profit or loss             |

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


        $revenue = ($accounts['sales']['credit'] ?? 0)
            - ($accounts['sales_discount']['debit'] ?? 0)
            - ($accounts['sales_return']['debit'] ?? 0);

        $cogs = ($accounts['cogs']['debit'] ?? 0)
            + ($accounts['inventory_shortage']['debit'] ?? 0)
            - ($accounts['purchase_discount']['credit'] ?? 0)
            - ($accounts['purchase_return']['credit'] ?? 0);

        $gross_profit = $revenue - $cogs;

        $expenses = ($accounts['expense']['debit'] ?? 0)
            + ($accounts['vat_payable']['debit'] ?? 0)
            - ($accounts['vat_receivable']['credit'] ?? 0)
            + ($accounts['longterm_liability']['debit'] ?? 0);

        $net_profit = $gross_profit - $expenses;

        $this->report = [
            'accounts' => $accounts,
            'revenue' => $revenue,
            'cogs' => $cogs,
            'gross_profit' => $gross_profit,
            'expenses' => $expenses,
            'net_profit' => $net_profit,
        ];
    }

    public function render()
    {
        return view('livewire.admin.reports.financial.income-statment-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
