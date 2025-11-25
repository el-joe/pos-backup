<?php


namespace App\Livewire\Admin\Reports\Purchases;

use App\Enums\AccountTypeEnum;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class PurchasesVatReport extends Component
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
        $this->report = $this->getPurchasesVatReport();

        return layoutView('reports.purchases.purchases-vat-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }

    protected function getPurchasesVatReport()
    {
        // Aggregate VAT receivable from transaction_lines and accounts by account type
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');
        $query = DB::table('transaction_lines')
            ->join('transactions', 'transaction_lines.transaction_id', '=', 'transactions.id')
            ->join('accounts', 'transaction_lines.account_id', '=', 'accounts.id')
            ->select([
                DB::raw('DATE(transaction_lines.created_at) as vat_date'),
                DB::raw('SUM(IF(transaction_lines.type = "credit", (transaction_lines.amount * -1), transaction_lines.amount)) as vat_amount'),
            ])
            ->where('accounts.type', AccountTypeEnum::VAT_RECEIVABLE->value)
            ->whereBetween('transactions.date', [$this->from_date, $toDate])
            ->groupBy(DB::raw('DATE(transaction_lines.created_at)'))
            ->orderBy(DB::raw('DATE(transaction_lines.created_at)'), 'desc');

        return $query->get();
    }
}
