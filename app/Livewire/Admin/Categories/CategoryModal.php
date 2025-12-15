<?php

namespace App\Livewire\Admin\Categories;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\CategoryService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryModal extends Component
{
    use LivewireOperations, WithPagination;
    private $categoryService;
    public $current;
    public $data = [
        'active' => false
    ];

    public $rules = [
        'name' => 'required|string|max:255',
        'parent_id' => 'nullable|integer',
        'icon' => 'nullable|string|max:255',
        'active' => 'boolean',
    ];

    function boot() {
        $this->categoryService = app(CategoryService::class);
    }

    #[On('category-set-current')]
    function setCurrent($id = null) {
        $this->current = $this->categoryService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];

        }else{
            $this->reset('data');
        }

        $this->dispatch('changeSelect', $this->data['icon'] ?? null);
        $this->dispatch('iCheck-load');
    }

    function save() {
        if (!$this->validator()) return;

        if($this->current){
            $action = AuditLogActionEnum::UPDATE_CATEGORY;
        }else{
            $action = AuditLogActionEnum::CREATE_CATEGORY;
        }

        $category = $this->categoryService->save($this->current?->id, $this->data);

        AuditLog::log($action, ['id' => $category->id]);

        $this->popup('success', 'Category saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
        $this->dispatch('re-render');
    }


    public function render()
    {
        $allCategories = $this->categoryService->list();

        $bootstrapIcons = config('icons.fontawesome_icons');

        return view('livewire.admin.categories.category-modal', get_defined_vars());
    }
}
