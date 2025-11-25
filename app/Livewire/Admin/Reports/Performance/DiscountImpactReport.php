<?php

namespace App\Livewire\Admin\Reports\Performance;


use App\Enums\AccountTypeEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;


class DiscountImpactReport extends Component
{
    public $report = [];
    public $from_date;
    public $to_date;

    public function mount()
    {
        $this->from_date = Carbon::now()->startOfMonth()->toDateString();
        $this->to_date = Carbon::now()->endOfDay()->toDateString();
    }

    public function updated($property)
    {
        if (in_array($property, ['from_date', 'to_date'])) {
            $this->loadReport();
        }
    }

    public function loadReport()
    {
        $fromDate = Carbon::parse($this->from_date)->startOfDay();
        $toDate = Carbon::parse($this->to_date)->endOfDay();

        $salesType = AccountTypeEnum::SALES->value;
        $salesDiscountType = AccountTypeEnum::SALES_DISCOUNT->value;

        $creditSales = 'SUM(CASE WHEN accounts.type = "' . $salesType . '" AND transaction_lines.type = "credit" THEN transaction_lines.amount ELSE 0 END)';
        $debitSales = 'SUM(CASE WHEN accounts.type = "' . $salesType . '" AND transaction_lines.type = "debit" THEN transaction_lines.amount ELSE 0 END)';
        $creditDiscount = 'SUM(CASE WHEN accounts.type = "' . $salesDiscountType . '" AND transaction_lines.type = "credit" THEN transaction_lines.amount ELSE 0 END)';
        $debitDiscount = 'SUM(CASE WHEN accounts.type = "' . $salesDiscountType . '" AND transaction_lines.type = "debit" THEN transaction_lines.amount ELSE 0 END)';

        $this->report = DB::table('transaction_lines')
            ->join('transactions', 'transactions.id', '=', 'transaction_lines.transaction_id')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->join('branches', 'branches.id', '=', 'transactions.branch_id')
            ->select(
                'branches.id as branch_id',
                'branches.name as branch_name',
                // Net sales = credit - debit for sales
                DB::raw('(' . $creditSales . ' - ' . $debitSales . ') as total_revenue'),
                // Net discount = credit - debit for sales_discount
                DB::raw('(' . $creditDiscount . ' - ' . $debitDiscount . ') as total_discount'),
                // Discount %
                DB::raw('ROUND((
                    (' . $creditDiscount . ' - ' . $debitDiscount . ')
                    /
                    NULLIF((' . $creditSales . ' - ' . $debitSales . ')
                    + (' . $creditDiscount . ' - ' . $debitDiscount . '), 0)
                ) * 100, 2) as discount_percentage')
            )
            ->whereIn('accounts.type', [$salesType, $salesDiscountType])
            ->whereBetween('transaction_lines.created_at', [$fromDate, $toDate])
            ->groupBy('branches.id', 'branches.name')
            ->orderByDesc('discount_percentage')
            ->get();
    }

    public function render()
    {
        if (empty($this->report)) {
            $this->loadReport();
        }

        return layoutView('reports.performance.discount-impact-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
