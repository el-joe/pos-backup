<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Tenant\CashRegister;
use Illuminate\Support\Carbon;


class CashRegisterReport extends Component
{
    public $from_date;
    public $to_date;
    public $branch_id;
    public $admin_id;
    public $registers = [];
    public $totals = [];

    public function mount()
    {
        $this->from_date = now()->startOfDay()->toDateString();
        $this->to_date = now()->endOfDay()->toDateString();
        $this->branch_id = null;
        $this->admin_id = null;
        $this->loadRegisters();
    }
    public function updatedAdminId()
    {
        $this->loadRegisters();
    }

    public function updatedFromDate()
    {
        $this->loadRegisters();
    }

    public function updatedToDate()
    {
        $this->loadRegisters();
    }

    public function updatedBranchId()
    {
        $this->loadRegisters();
    }

    public function applyFilter()
    {
        $this->loadRegisters();
    }

    public function resetFilters()
    {
        $this->from_date = now()->startOfDay()->toDateString();
        $this->to_date = now()->endOfDay()->toDateString();
        $this->branch_id = null;
        $this->admin_id = null;
        $this->loadRegisters();
    }

    protected function baseQuery()
    {
        $query = CashRegister::query();
        if ($this->from_date && $this->to_date) {
            $query->whereDate('opened_at', '>=', $this->from_date)
                  ->whereDate('opened_at', '<=', $this->to_date);
        }
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        if ($this->admin_id) {
            $query->where('admin_id', $this->admin_id);
        }

        return $query;
    }

    public function loadRegisters()
    {
        $this->registers = $this->baseQuery()->with(['branch', 'admin'])->orderBy('opened_at', 'desc')->get();

        $sumColumns = [
            'opening_balance', 'total_sales', 'total_sale_refunds', 'total_purchases',
            'total_purchase_refunds', 'total_expenses', 'total_expense_refunds',
            'total_deposits', 'total_withdrawals', 'closing_balance', 'discrepancy',
        ];
        $this->totals = $this->baseQuery()->selectRaw(
            implode(',', array_map(fn($c) => "COALESCE(SUM($c),0) as $c", $sumColumns))
        )->first()?->toArray() ?? [];
    }

    public function render()
    {
        $admins = app(\App\Services\AdminService::class)->activeList();
        $branches = app(\App\Services\BranchService::class)->activeList();
        return layoutView('reports.cash-register-report', get_defined_vars());
    }
}
