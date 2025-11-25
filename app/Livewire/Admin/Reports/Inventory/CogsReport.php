<?php


namespace App\Livewire\Admin\Reports\Inventory;

use App\Enums\AccountTypeEnum;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class CogsReport extends Component
{
    public $report = [];

    public function render()
    {
        $this->report = $this->getCogsReport();

        return layoutView('reports.inventory.cogs-report', [
            'report' => $this->report,
        ]);
    }

    protected function getCogsReport()
    {
        // Aggregate COGS per branch from transaction_lines and accounts
    $query = DB::table('transaction_lines')
            ->join('accounts', 'transaction_lines.account_id', '=', 'accounts.id')
            ->join('transactions', 'transaction_lines.transaction_id', '=', 'transactions.id')
            ->join('branches', 'transactions.branch_id', '=', 'branches.id')
            ->select([
                'branches.name as branch_name',
                DB::raw('SUM(IF(transaction_lines.type = "credit", (transaction_lines.amount * -1), transaction_lines.amount)) as cogs_amount'),
            ])
            ->where('accounts.type', AccountTypeEnum::COGS->value)
            ->groupBy('branches.id', 'branches.name', 'accounts.id', 'accounts.name')
            ->orderByDesc('cogs_amount');

        return $query->get();
    }
}
