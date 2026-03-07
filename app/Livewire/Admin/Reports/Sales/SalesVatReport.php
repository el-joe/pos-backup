<?php


namespace App\Livewire\Admin\Reports\Sales;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Sale;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class SalesVatReport extends Component
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
        $this->report = $this->getVatPayableReport();

        return layoutView('reports.sales.sales-vat-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }

    protected function getVatPayableReport()
    {
        $fromDate = carbon($this->from_date)->startOfDay()->format('Y-m-d H:i:s');
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');

        $query = DB::table('transaction_lines')
            ->join('transactions', 'transaction_lines.transaction_id', '=', 'transactions.id')
            ->join('sales', function($join) {
                $join->on('transactions.reference_id', '=', 'sales.id')
                     ->where('transactions.reference_type', '=', Sale::class);
            })
            ->join('users', 'sales.customer_id', '=', 'users.id')
            ->join('accounts', function($join) {
                $join->on('accounts.id', '=', 'transaction_lines.account_id')
                     ->where('accounts.type', '=', AccountTypeEnum::VAT_PAYABLE->value);
            })
            ->select(
                'sales.invoice_number',
                'users.name as customer_name',
                DB::raw('SUM(IF(transaction_lines.type = "credit" , transaction_lines.amount, (transaction_lines.amount*-1))) as vat_payable'),
                DB::raw('MAX(transactions.date) as sale_date')
            )
            ->whereBetween('transactions.date', [$fromDate, $toDate])
            ->groupBy('sales.id', 'sales.invoice_number', 'users.name', 'sales.order_date')
            ->orderBy('sales.order_date', 'desc');

        return $query->get();
    }
}
