<?php

namespace App\Livewire\Admin\Reports\Tax;

use Livewire\Component;
use App\Enums\AccountTypeEnum;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class VatSummaryReport extends Component
{
    public $from_date;
    public $to_date;
    public $report = [];

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    public function render()
    {
        $this->report = $this->getVatSummary();
        return view('livewire.admin.reports.tax.vat-summary-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }

    // Triggered by the UI when the user applies the date filter
    public function applyFilter()
    {
        // no-op: Livewire will re-render and call render(), which refreshes the report
    }

    // Reset dates to defaults
    public function resetDates()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    protected function getVatSummary()
    {
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');

        // Use local variables for account types to keep DB::raw simple
        $vatPayableType = AccountTypeEnum::VAT_PAYABLE->value;
        $vatReceivableType = AccountTypeEnum::VAT_RECEIVABLE->value;

        // Sum amounts for VAT Payable (credit positive) and VAT Receivable (debit positive)
        $base = DB::table('transaction_lines')
            ->join('transactions', 'transaction_lines.transaction_id', '=', 'transactions.id')
            ->join('accounts', 'transaction_lines.account_id', '=', 'accounts.id')
            ->select([
                DB::raw("SUM(IF(accounts.type = '" . $vatPayableType . "', IF(transaction_lines.type = 'credit', transaction_lines.amount, (transaction_lines.amount * -1)), 0)) as vat_payable"),
                DB::raw("SUM(IF(accounts.type = '" . $vatReceivableType . "', IF(transaction_lines.type = 'debit', transaction_lines.amount, (transaction_lines.amount * -1)), 0)) as vat_receivable"),
            ])
            ->whereBetween('transactions.date', [$this->from_date, $toDate]);

        $row = $base->first();

        $vat_payable = $row->vat_payable ?? 0;
        $vat_receivable = $row->vat_receivable ?? 0;
        $net = $vat_payable - $vat_receivable;

        return [
            'vat_payable' => $vat_payable,
            'vat_receivable' => $vat_receivable,
            'net' => $net,
        ];
    }
}
