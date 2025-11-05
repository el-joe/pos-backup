<?php

namespace App\Livewire\Admin\Reports\Financial;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class GeneralLedgerReport extends Component
{
    public $from_date;
    public $to_date;
    public $report = [];

    #[Url]
    public $active_account = null;
    public $accounts = [];

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->loadAccounts($this->active_account);
    }

    public function updatedFromDate() { $this->loadAccounts($this->active_account); }
    public function updatedToDate() { $this->loadAccounts($this->active_account); }

    public function loadAccounts($accountParam = null)
    {
        $toDate = Carbon::parse($this->to_date)->endOfDay()->format('Y-m-d H:i:s');
        $rows = DB::table('accounts')
            ->leftJoin('branches', 'branches.id', '=', 'accounts.branch_id')
            ->select(
                'accounts.code as account_code',
                'accounts.name as account_name',
                'accounts.type as account_type',
                DB::raw('COALESCE(branches.name, "General") as branch_name')
            )
            ->orderBy('accounts.code')
            ->get();

        $accounts = [];
        foreach ($rows as $row) {
            $key = $row->account_code . ' - ' . $row->account_name . ' - ' . $row->branch_name;
            $accounts[] = $key;
        }

        $this->accounts = $accounts;
        $this->report = ['ledger' => []];
        if ($accountParam && in_array($accountParam, $accounts)) {
            $this->active_account = $accountParam;
        } else {
            $this->active_account = $accounts[0] ?? null;
        }
        if ($this->active_account) {
            $this->loadAccountLedger($this->active_account);
        }
    }

    public function loadAccountLedger($accountKey)
    {
        $toDate = Carbon::parse($this->to_date)->endOfDay()->format('Y-m-d H:i:s');
        // Parse accountKey to get code, name, branch
        preg_match('/^(.*?) - (.*?) - (.*?)$/', $accountKey, $matches);
        $code = $matches[1] ?? null;
        $name = $matches[2] ?? null;
        $branch = $matches[3] ?? null;

        $rows = DB::table('transaction_lines')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->leftJoin('branches', 'branches.id', '=', 'accounts.branch_id')
            ->leftJoin('transactions', 'transactions.id', '=', 'transaction_lines.transaction_id')
            ->select(
                'accounts.code as account_code',
                'accounts.name as account_name',
                'accounts.type as account_type',
                DB::raw('COALESCE(branches.name, "General") as branch_name'),
                'transaction_lines.id as txn_id',
                'transaction_lines.transaction_id',
                'transaction_lines.type as txn_type',
                'transaction_lines.amount',
                'transaction_lines.created_at',
                'transactions.description'
            )
            ->where('accounts.code', $code)
            ->where('accounts.name', $name)
            ->where(DB::raw('COALESCE(branches.name, "General")'), $branch)
            ->whereBetween('transaction_lines.created_at', [$this->from_date, $toDate])
            ->orderBy('transaction_lines.created_at')
            ->get();

        $ledger = [];
        foreach ($rows as $row) {
            $key = $row->account_code . ' - ' . $row->account_name . ' - ' . $row->branch_name;
            if (!isset($ledger[$key])) $ledger[$key] = [];
            $ledger[$key][] = [
                'txn_id' => $row->txn_id,
                'transaction_id' => $row->transaction_id,
                'type' => $row->txn_type,
                'amount' => $row->amount,
                'date' => $row->created_at,
                'description' => $row->description,
            ];
        }
        $this->report['ledger'] = $ledger;
        $this->active_account = $accountKey;
    }

    public function render()
    {

        return layoutView('reports.financial.general-ledger-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'active_account' => $this->active_account,
            'accounts' => $this->accounts,
        ]);
    }
}
