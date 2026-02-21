<?php

namespace App\Livewire\Admin\Hrm\Claims;

use App\Services\Hrm\ExpenseClaimCategoryService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class ClaimCategoryModal extends Component
{
    use LivewireOperations;

    private ExpenseClaimCategoryService $categoryService;

    public $current;
    public array $data = [
        'name' => null,
    ];

    public function boot(): void
    {
        $this->categoryService = app(ExpenseClaimCategoryService::class);
    }

    #[On('hrm-claim-category-set-current')]
    public function setCurrent($id = null): void
    {
        $this->current = $this->categoryService->find($id);
        if ($this->current) {
            $this->data = [
                'name' => $this->current->name,
            ];
        } else {
            $this->reset('current', 'data');
        }
    }

    public function save(): void
    {
        $isUpdate = (bool) $this->current?->id;
        if ($isUpdate && !adminCan('hrm_claims.update')) {
            abort(403);
        }
        if (!$isUpdate && !adminCan('hrm_claims.create')) {
            abort(403);
        }

        $this->validate([
            'data.name' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            if ($isUpdate) {
                $this->categoryService->update($this->current->id, $this->data);
            } else {
                $this->categoryService->create($this->data);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->popup('error', 'Error while saving claim category: ' . $e->getMessage());
            return;
        }

        $this->popup('success', 'Claim category saved successfully');
        $this->dismiss();
        $this->reset('current', 'data');
        $this->dispatch('re-render');
    }

    public function render()
    {
        return view('livewire.admin.hrm.claims.claim-category-modal');
    }
}
