<?php

namespace App\Livewire\Admin\Reports;

use App\Enums\CheckStatusEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Check;
use App\Models\Tenant\TransactionLine;
use Livewire\Component;

class CheckAgingReport extends Component
{
    public $direction = 'received';
    public $branch_id = '';

    public function applyFilter()
    {
        //
    }

    public function resetFilters()
    {
        $this->direction = 'received';
        $this->branch_id = '';
    }

    private function bucket($dueDate): string
    {
        if (!$dueDate) {
            return 'no_due_date';
        }

        $days = now()->startOfDay()->diffInDays(\Illuminate\Support\Carbon::parse($dueDate)->startOfDay(), false);

        if ($days < 0) {
            return 'overdue';
        }
        if ($days <= 7) {
            return 'due_0_7';
        }
        if ($days <= 30) {
            return 'due_8_30';
        }
        if ($days <= 60) {
            return 'due_31_60';
        }

        return 'due_60_plus';
    }

    public function render()
    {
        $openStatuses = $this->direction === 'received'
            ? [CheckStatusEnum::UNDER_COLLECTION->value]
            : [CheckStatusEnum::ISSUED->value];

        $checks = Check::query()
            ->with(['customer', 'supplier', 'branch'])
            ->where('direction', $this->direction)
            ->whereIn('status', $openStatuses)
            ->when($this->branch_id, fn ($q) => $q->where('branch_id', $this->branch_id))
            ->orderBy('due_date')
            ->get();

        $buckets = [
            'overdue' => collect(),
            'due_0_7' => collect(),
            'due_8_30' => collect(),
            'due_31_60' => collect(),
            'due_60_plus' => collect(),
            'no_due_date' => collect(),
        ];

        foreach ($checks as $check) {
            $buckets[$this->bucket($check->due_date)]->push($check);
        }

        $bucketTotals = collect($buckets)->map(fn ($items) => (float) $items->sum('amount'));
        $grandTotal = (float) $checks->sum('amount');

        $controlAccount = Account::forCheckDirection($this->direction, $this->branch_id ?: null);
        $debit = (float) TransactionLine::where('account_id', $controlAccount->id)->where('type', 'debit')->sum('amount');
        $credit = (float) TransactionLine::where('account_id', $controlAccount->id)->where('type', 'credit')->sum('amount');
        $controlAccountBalance = $this->direction === 'received' ? ($debit - $credit) : ($credit - $debit);

        $branches = Branch::orderBy('name')->get();

        return layoutView('reports.check-aging-report', [
            'buckets' => $buckets,
            'bucketTotals' => $bucketTotals,
            'grandTotal' => $grandTotal,
            'controlAccount' => $controlAccount,
            'controlAccountBalance' => $controlAccountBalance,
            'branches' => $branches,
        ])->title(__('general.titles.check_aging_report'));
    }
}
