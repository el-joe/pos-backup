<?php

namespace App\Livewire\Admin\Reports\Financial;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CashFlowStatementReport extends Component
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
        $toDate = Carbon::parse($this->to_date)->endOfDay()->format('Y-m-d H:i:s');

        $cashAccounts = [
            'branch_cash',
            'current_asset',
            'bank', // if exists
        ];

        $rows = DB::table('transaction_lines')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->leftJoin('branches', 'branches.id', '=', 'accounts.branch_id')
            ->select(
                DB::raw('CONCAT(COALESCE(branches.name, "General"), " - ", accounts.type) as account_label'),
                DB::raw('SUM(CASE WHEN transaction_lines.type = "debit" THEN transaction_lines.amount ELSE 0 END) as inflow'),
                DB::raw('SUM(CASE WHEN transaction_lines.type = "credit" THEN transaction_lines.amount ELSE 0 END) as outflow')
            )
            ->whereIn('accounts.type', $cashAccounts)
            ->whereBetween('transaction_lines.created_at', [$this->from_date, $toDate])
            ->groupBy(DB::raw('CONCAT(COALESCE(branches.name, "General"), " - ", accounts.type)'))
            ->get();

        $cashFlows = [];
        $total_inflow = 0;
        $total_outflow = 0;

        foreach ($rows as $row) {
            $inflow = $row->inflow;
            $outflow = $row->outflow;

            $cashFlows[$row->account_label] = [
                'inflow' => $inflow,
                'outflow' => $outflow,
                'net' => $inflow - $outflow,
            ];

            $total_inflow += $inflow;
            $total_outflow += $outflow;
        }

        $this->report = [
            'cash_flows' => $cashFlows,
            'total_inflow' => $total_inflow,
            'total_outflow' => $total_outflow,
            'net_cash_flow' => $total_inflow - $total_outflow,
        ];
    }

    public function render()
    {
        return layoutView('reports.financial.cash-flow-statement-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
