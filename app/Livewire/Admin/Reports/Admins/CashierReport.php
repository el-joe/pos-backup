<?php

namespace App\Livewire\Admin\Reports\Admins;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\TransactionLine;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class CashierReport extends Component
{

    public $report = [];
    public $from_date;
    public $to_date;

    public function mount() {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->loadReport();
    }

    function loadReport() {
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');
        $report = TransactionLine::query()
            ->select([
                'admins.name as cashier',
                DB::raw("
                    SUM(CASE
                        WHEN accounts.type = '" . AccountTypeEnum::SALES->value . "'
                            AND transaction_lines.type = 'credit'
                        THEN transaction_lines.amount ELSE 0 END
                    ) AS total_sales"),
                // sales -> debit mean refunds
                DB::raw("
                    SUM(CASE
                        WHEN accounts.type = '" . AccountTypeEnum::SALES->value . "'
                            AND transaction_lines.type = 'debit'
                        THEN transaction_lines.amount ELSE 0 END
                    ) AS total_refunds"),
                // total discounts = debit - credit
                DB::raw("
                    SUM(CASE
                        WHEN accounts.type = '" . AccountTypeEnum::SALES_DISCOUNT->value . "'
                        THEN IF(transaction_lines.type = 'debit', transaction_lines.amount, (transaction_lines.amount * -1)) ELSE 0 END
                    ) AS total_discounts"),

                DB::raw("
                    SUM(CASE
                        WHEN accounts.type = '" . AccountTypeEnum::SALES->value . "'
                            AND transaction_lines.type = 'credit'
                        THEN transaction_lines.amount ELSE 0 END
                    )
                    - SUM(CASE
                        WHEN accounts.type = '" . AccountTypeEnum::SALES->value . "'
                            AND transaction_lines.type = 'debit'
                        THEN transaction_lines.amount ELSE 0 END
                    )
                    - SUM(CASE
                        WHEN accounts.type = '" . AccountTypeEnum::SALES_DISCOUNT->value . "'
                        THEN IF(transaction_lines.type = 'debit', transaction_lines.amount, (transaction_lines.amount * -1)) ELSE 0 END
                    )
                    + SUM(CASE
                        WHEN accounts.type = '" . AccountTypeEnum::VAT_PAYABLE->value . "'
                            AND transaction_lines.type = 'credit'
                        THEN transaction_lines.amount ELSE 0 END
                    )
                    AS net_sales")
            ])
            ->join('accounts', 'transaction_lines.account_id', '=', 'accounts.id')
            ->join('transactions', 'transaction_lines.transaction_id', '=', 'transactions.id')
            ->join('admins', 'transaction_lines.created_by', '=', 'admins.id')
            ->whereBetween('transactions.date', [$this->from_date, $toDate])
            ->groupBy('admins.name')
            ->orderByDesc('net_sales')
            ->get();

        // store on the component so the view can access it
        $this->report = $report;
    }

    public function updatedFromDate() { $this->loadReport(); }
    public function updatedToDate() { $this->loadReport(); }

    public function applyFilter()
    {
        $this->loadReport();
    }

    public function resetDates()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->loadReport();
    }

    public function render()
    {
        return view('livewire.admin.reports.admins.cashier-report',[
            'report' => $this->report,
        ]);
    }
}
