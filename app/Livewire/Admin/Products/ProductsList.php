<?php

namespace App\Livewire\Admin\Products;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\BranchService;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsList extends Component
{

    use LivewireOperations,WithPagination;
    private $productService, $branchService,$brandService,$categoryService;
    public $current;

    public $filters = [];
    public $collapseFilters = false;
    public $export = null;

    function boot() {
        $this->productService = app(ProductService::class);
        $this->branchService = app(BranchService::class);
        $this->brandService = app(BrandService::class);
        $this->categoryService = app(CategoryService::class);
    }

    function setCurrent($id) {
        $this->current = $this->productService->find($id);
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        AuditLog::log(AuditLogActionEnum::DELETE_PRODUCT_TRY, ['id' => $id]);

        $this->confirm('delete','warning','Are you sure?','You want to delete this product','Yes, delete it!');
    }

    function delete() {
        if(!$this->current) {
            $this->popup('error','Product not found');
            return;
        }

        $this->productService->delete($this->current->id);

        AuditLog::log(AuditLogActionEnum::DELETE_PRODUCT, ['id' => $this->current->id]);

        $this->popup('success','Product deleted successfully');

        $this->dismiss();

        $this->reset('current');
    }

    public function render()
    {

        if ($this->export == 'excel') {
            $qData = $this->productService->list(
                orderByDesc : 'products.created_at',
                filter : $this->filters
            );

            $data = $qData->map(function ($product, $loop) {
                return [
                    'loop' => $loop + 1,
                    'sku' => $product->sku,
                    'code' => $product->code,
                    'name' => $product->name,
                    'branch' => $product->stocks->first()?->branch?->name ?? 'All',
                    'category' => $product->category?->name,
                    'brand' => $product->brand?->name,
                    'unit' => $product->unit?->name,
                    'weight' => $product->weight,
                    'alert_qty' => $product->alert_qty,
                    'active' => $product->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'sku', 'code', 'name', 'branch', 'category', 'brand', 'unit', 'weight', 'alert_qty', 'active'];
            $headers = ['#', 'SKU', 'Code', 'Name', 'Branch', 'Category', 'Brand', 'Unit', 'Weight', 'Alert Quantity', 'Status'];
            $fullPath = exportToExcel($data, $columns, $headers, 'branches');

            AuditLog::log(AuditLogActionEnum::EXPORT_PRODUCTS, ['url' => $fullPath]);

            $this->redirectToDownload($fullPath);

            $this->export = null;
        }

        $products = $this->productService->list(filter : $this->filters, perPage : 10 , orderByDesc : 'id');

        $branches = $this->branchService->activeList();
        $brands = $this->brandService->activeList();
        $categories = $this->categoryService->activeList();

        return layoutView('products.products-list', get_defined_vars())
            ->title(__('general.titles.products'));
    }
}
