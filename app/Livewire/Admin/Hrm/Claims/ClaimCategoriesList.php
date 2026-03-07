<?php

namespace App\Livewire\Admin\Hrm\Claims;

use App\Services\Hrm\ExpenseClaimCategoryService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ClaimCategoriesList extends Component
{
    use LivewireOperations, WithPagination;

    private ExpenseClaimCategoryService $categoryService;

    public array $filters = [];
    public bool $collapseFilters = false;
    public $current;

    public function boot(): void
    {
        $this->categoryService = app(ExpenseClaimCategoryService::class);
    }

    public function setCurrent($id): void
    {
        $this->current = $this->categoryService->find($id);
    }

    public function deleteAlert($id): void
    {
        if (!adminCan('hrm_claims.delete')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('delete', 'warning', __('general.messages.are_you_sure'), __('general.messages.hrm.confirm_delete_claim_category'), __('general.messages.yes_delete_it'));
    }

    public function delete(): void
    {
        if (!adminCan('hrm_claims.delete')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.claim_category_not_found'));
            return;
        }
        $this->categoryService->delete($this->current->id);
        $this->popup('success', __('general.messages.hrm.claim_category_deleted_successfully'));
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
        $this->resetPage();
    }

    #[On('re-render')]
    public function render()
    {
        $categories = $this->categoryService->list([], $this->filters, 10, 'id');

        return layoutView('hrm.claims.claim-categories-list', get_defined_vars())
            ->title(__('general.titles.hrm_claim_categories'));
    }
}
