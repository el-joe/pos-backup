<?php

namespace App\Livewire\Admin\Checks;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Check;
use App\Services\CheckService;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithPagination;

class ChecksList extends Component
{
    use LivewireOperations, WithPagination;

    private CheckService $checkService;

    public array $filters = [
        'direction' => null,
        'status' => null,
        'branch_id' => null,
        'search' => null,
    ];

    public function boot(): void
    {
        $this->checkService = app(CheckService::class);
    }

    public function collect(int $id): void
    {
        if (!adminCan('checks.collect')) {
            $this->popup('error', __('general.messages.unauthorized'));
            return;
        }

        try {
            $this->checkService->collect($id);
            $this->alert('success', __('general.messages.check_collected_successfully'));
        } catch (\Throwable $e) {
            $this->alert('error', $e->getMessage());
        }
    }

    public function bounce(int $id): void
    {
        if (!adminCan('checks.bounce')) {
            $this->popup('error', __('general.messages.unauthorized'));
            return;
        }

        try {
            $this->checkService->bounce($id);
            $this->alert('success', __('general.messages.check_bounced_successfully'));
        } catch (\Throwable $e) {
            $this->alert('error', $e->getMessage());
        }
    }

    public function clearIssued(int $id): void
    {
        if (!adminCan('checks.clear')) {
            $this->popup('error', __('general.messages.unauthorized'));
            return;
        }

        try {
            $this->checkService->clearIssued($id);
            $this->alert('success', __('general.messages.issued_check_cleared_successfully'));
        } catch (\Throwable $e) {
            $this->alert('error', $e->getMessage());
        }
    }

    public function render()
    {
        if (!adminCan('checks.list')) {
            abort(403);
        }

        $query = Check::query()
            ->with(['branch', 'customer', 'supplier'])
            ->when($this->filters['direction'] ?? null, fn($q) => $q->where('direction', $this->filters['direction']))
            ->when($this->filters['status'] ?? null, fn($q) => $q->where('status', $this->filters['status']))
            ->when($this->filters['branch_id'] ?? null, fn($q) => $q->where('branch_id', $this->filters['branch_id']))
            ->when($this->filters['search'] ?? null, function ($q) {
                $s = trim((string)$this->filters['search']);
                if ($s === '') return;
                $q->where(function ($qq) use ($s) {
                    $qq->where('check_number', 'like', "%{$s}%")
                        ->orWhere('bank_name', 'like', "%{$s}%");
                });
            })
            ->orderByDesc('id');

        $checks = $query->paginate(15);

        $branches = Branch::orderBy('name')->get();
        $cashAccounts = Account::where('type', AccountTypeEnum::BRANCH_CASH->value)
            ->orderBy('name')
            ->get();

        return layoutView('checks.checks-list', get_defined_vars());
    }
}
