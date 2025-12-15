<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Tenant\AuditLog;
use App\Services\AdminService;
use Livewire\Component;
use Livewire\WithPagination;

class AuditReport extends Component
{
    use WithPagination;

    public $from_date;
    public $to_date;
    public $admin_id = '';
    public $action = '';

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    public function applyFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->admin_id = '';
        $this->action = '';
        $this->resetPage();
    }

    public function render()
    {
        $audits = AuditLog::with('admin')
            ->when($this->from_date, function($q) {
                $q->whereDate('created_at', '>=', $this->from_date);
            })
            ->when($this->to_date, function($q) {
                $q->whereDate('created_at', '<=', $this->to_date);
            })
            ->when($this->admin_id, function($q) {
                $q->where('admin_id', $this->admin_id);
            })
            ->when($this->action, function($q) {
                $q->where('action', $this->action);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $admins = app(AdminService::class)->activeList();

        return layoutView('reports.audit-report', [
            'audits' => $audits,
            'admins' => $admins,
        ])->title(__('general.titles.audit_report'));
    }
}
