<?php

namespace App\Livewire\Admin\Categories;

use App\Services\CategoryService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesList extends Component
{
    use LivewireOperations, WithPagination;
    private $categoryService;
    public $current;

    public $export = null;
    public $collapseFilters = false;
    public $filters = [];

    function boot() {
        $this->categoryService = app(CategoryService::class);
    }

    function setCurrent($id) {
        $this->current = $this->categoryService->find($id);

        $this->dispatch('iCheck-load');
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

        $this->reset('current');
    }

    #[On('re-render')]
    public function render()
    {

        if ($this->export == 'excel') {
            $qData = $this->categoryService->list(
                orderByDesc: 'categories.created_at',
                filter: $this->filters
            );

            $data = $qData->map(function ($category, $loop) {
                return [
                    'loop' => $loop + 1,
                    'name' => $category->name,
                    'parent_category' => $category->parent ? $category->parent->name : 'N/A',
                    'active' => $category->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'name', 'parent_category', 'active'];
            $headers = ['#', 'Name', 'Parent Category', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'categories');

            $this->redirectToDownload($fullPath);
        }

        $categories = $this->categoryService->list(
            perPage: 10,
            orderByDesc: 'categories.created_at',
            filter: $this->filters
        );

        $allCategories = $this->categoryService->list();

        return layoutView('categories.categories-list', get_defined_vars())
            ->title(__('general.titles.categories'));
    }
}
