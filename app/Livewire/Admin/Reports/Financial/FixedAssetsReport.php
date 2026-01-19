<?php

namespace App\Livewire\Admin\Reports\Financial;

use App\Models\Tenant\FixedAsset;
use Livewire\Component;

class FixedAssetsReport extends Component
{
    public string $from_date;
    public string $to_date;

    public $branch_id = null;
    public $status = null;

    public $report;

    public function mount(): void
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->loadReport();
    }

    public function updatedFromDate(): void
    {
        $this->loadReport();
    }

    public function updatedToDate(): void
    {
        $this->loadReport();
    }

    public function updatedBranchId(): void
    {
        $this->loadReport();
    }

    public function updatedStatus(): void
    {
        $this->loadReport();
    }

    public function resetFilters(): void
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->branch_id = null;
        $this->status = null;

        $this->loadReport();
    }

    public function loadReport(): void
    {
        $query = FixedAsset::query()
            ->with(['branch'])
            ->withSum('expenses', 'amount');

        if ($this->from_date) {
            $query->whereDate('purchase_date', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $query->whereDate('purchase_date', '<=', $this->to_date);
        }

        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $this->report = $query->orderByDesc('id')->get();
    }

    public function render()
    {
        $branches = app(\App\Services\BranchService::class)->activeList();

        return layoutView('reports.financial.fixed-assets-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'branch_id' => $this->branch_id,
            'status' => $this->status,
            'branches' => $branches,
        ]);
    }
}
