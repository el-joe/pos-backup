<?php

namespace App\Livewire\Admin\Reports\Performance;


use App\Models\Tenant\User;
use App\Models\Tenant\Account;
use App\Models\Tenant\TransactionLine;
use App\Enums\AccountTypeEnum;
use App\Enums\UserTypeEnum;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


#[Layout('layouts.admin')]
class SupplierPayableReport extends Component
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

        $totalDebit = "SUM(CASE WHEN transaction_lines.type = 'debit' THEN transaction_lines.amount ELSE 0 END)";
        $totalCredit = "SUM(CASE WHEN transaction_lines.type = 'credit' THEN transaction_lines.amount ELSE 0 END)";

        $this->report = DB::table('transaction_lines')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->join('users', function($join) {
                $join->on('users.id', '=', 'accounts.model_id')
                     ->where('accounts.model_type', User::class)
                     ->where('users.type','supplier');
            })
            ->select(
                'accounts.id as account_id',
                DB::raw("CONCAT(users.name, ' (', accounts.code, ')') as supplier_name"),
                DB::raw("$totalDebit as total_debit"),
                DB::raw("$totalCredit as total_credit"),
                DB::raw("($totalCredit - $totalDebit) as balance")
            )
            ->where('accounts.type', AccountTypeEnum::SUPPLIER)
            ->whereBetween('transaction_lines.created_at', [$fromDate, $toDate])
            ->groupBy('accounts.id', 'users.name')
            ->havingRaw('balance > 0') // Only suppliers you owe money to
            ->orderByDesc('balance')
            ->get();

            // dd($this->report);
    }

    public function render()
    {
        if (empty($this->report)) {
            $this->loadReport();
        }
        return view('livewire.admin.reports.performance.supplier-payable-report');
    }
}
