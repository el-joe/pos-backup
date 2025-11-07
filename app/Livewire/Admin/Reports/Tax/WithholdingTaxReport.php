<?php

namespace App\Livewire\Admin\Reports\Tax;

use App\Enums\AccountTypeEnum;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Sale;

class WithholdingTaxReport extends Component
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
        $this->report = $this->getWithholdingReport();

        return layoutView('reports.tax.withholding-tax-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }

    public function applyFilter()
    {
        // no-op, Livewire will re-render on interaction
    }

    public function resetDates()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    protected function getWithholdingReport()
    {
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');

    // Aggregate transaction_lines for accounts that look like withholding tax accounts
    // Include purchases (suppliers) and sales (customers) so both supplier and customer withholding are reported
        $query = DB::table('transaction_lines')
            ->join('transactions', 'transaction_lines.transaction_id', '=', 'transactions.id')
            ->join('accounts', 'transaction_lines.account_id', '=', 'accounts.id')
            ->leftJoin('purchases', function ($join) {
                $join->on('transactions.reference_id', '=', 'purchases.id')
                    ->where('transactions.reference_type', '=', Purchase::class);
            })
            ->leftJoin('users as suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->leftJoin('sales', function ($join) {
                $join->on('transactions.reference_id', '=', 'sales.id')
                    ->where('transactions.reference_type', '=', Sale::class);
            })
            ->leftJoin('users as customers', 'sales.customer_id', '=', 'customers.id')
            ->select([
                'accounts.id',
                'accounts.type as account_name',
                DB::raw('CASE WHEN suppliers.id IS NOT NULL THEN "supplier" WHEN customers.id IS NOT NULL THEN "customer" ELSE "unknown" END as party_type'),
                DB::raw('COALESCE(suppliers.name, customers.name, accounts.name) as party_name'),
                DB::raw('SUM(IF(transaction_lines.type = "credit", transaction_lines.amount, (transaction_lines.amount * -1))) as withholding_amount'),
            ])
            ->whereIn('accounts.type',[
                AccountTypeEnum::VAT_PAYABLE->value,
                AccountTypeEnum::VAT_RECEIVABLE->value,
            ])
            ->whereBetween('transactions.date', [$this->from_date, $toDate])
            ->groupBy('accounts.type', DB::raw('COALESCE(suppliers.name, customers.name, accounts.name)'), DB::raw('accounts.id'), DB::raw('CASE WHEN suppliers.id IS NOT NULL THEN "supplier" WHEN customers.id IS NOT NULL THEN "customer" ELSE "unknown" END'))
            ->orderBy('withholding_amount', 'desc');

        return $query->get();
    }
}
