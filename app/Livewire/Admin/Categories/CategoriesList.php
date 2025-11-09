<?php

namespace App\Livewire\Admin\Categories;

use App\Services\CategoryService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesList extends Component
{
    use LivewireOperations, WithPagination;
    private $categoryService;
    public $current;
    public $data = [];

    public $rules = [
        'name' => 'required|string|max:255',
        'parent_id' => 'nullable|integer',
        'icon' => 'nullable|string|max:255',
        'active' => 'boolean',
    ];

    function boot() {
        $this->categoryService = app(CategoryService::class);
    }

    function setCurrent($id) {
        $this->current = $this->categoryService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }else{
            $this->data = [];
        }

        $this->dispatch('iCheck-load');
        $this->dispatch('changeSelect', $this->data['icon'] ?? null);
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this category', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'Category not found');
            return;
        }

        $this->categoryService->delete($this->current->id);

        $this->popup('success', 'Category deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save() {
        if (!$this->validator()) return;

        $this->categoryService->save($this->current?->id, $this->data);

        $this->popup('success', 'Category saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $categories = $this->categoryService->list(
            perPage: 10,
            orderByDesc: 'categories.created_at'
        );

        $allCategories = $this->categoryService->list();

        $bootstrapIcons = config('icons.fontawesome_icons');

        return layoutView('categories.categories-list', get_defined_vars())
            ->title(__('general.titles.categories'));
    }
}
