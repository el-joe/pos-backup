<?php

namespace App\Livewire\Admin\Products;

use App\Enums\AuditLogActionEnum;
use App\Models\Subscription;
use App\Models\Tenant\AuditLog;
use App\Services\BranchService;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\TaxService;
use App\Services\UnitService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class AddEditProduct extends Component
{
    use LivewireOperations;

    public $id, $product;

    private $productService, $branchService, $categoryService, $brandService, $unitService;

    public $data = [
        'active' => false,
    ];

    public $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:500',
        'sku' => 'required|string|max:255|unique:products,deleted_at,NULL',
        'unit_id' => 'required|exists:units,id',
        'brand_id' => 'required|exists:brands,id',
        'branch_id' => 'nullable|exists:branches,id',
        'category_id' => 'required|exists:categories,id',
        'weight' => 'nullable|numeric',
        'alert_qty' => 'required|integer',
        'active' => 'boolean',
        'taxable' => 'boolean',
        // 'tax_id' => 'required_if:taxable,true|exists:taxes,id',
        'code' => 'required|string|max:100',
        // 'sell_price' => 'required|numeric',
        'image' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048',
        'gallery' => 'nullable|array',
        'gallery.*' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048',
    ];

    function boot() {
        $this->productService = app(ProductService::class);
        $this->branchService = app(BranchService::class);
        $this->categoryService = app(CategoryService::class);
        $this->brandService = app(BrandService::class);
        // $this->taxService = app(TaxService::class);
        $this->unitService = app(UnitService::class);
    }


    function mount() {
        $this->product = $this->productService->find($this->id);
        if($this->product) {
            $this->retriveData();
        }
    }

    function retriveData() {
        if(!$this->product) return;
        $product = $this->product;
        $this->data = $this->product->toArray();
        $this->data['active'] = (bool)$this->data['active'];
        $this->data['taxable'] = (bool)$this->data['taxable'];
        $this->data['image_path'] = $product->image_path;
        $this->data['gallery_path'] = $product->gallery_path;
        unset($this->data['image'], $this->data['gallery']);
    }

    function save() {
        $rules = $this->rules;

        if($this->product){
            $action = AuditLogActionEnum::UPDATE_PRODUCT;
        }else{
            $action = AuditLogActionEnum::CREATE_PRODUCT;
        }

        if(!$this->product) {
            $rules['image'] = str_replace('nullable','required',$rules['image'] ?? '');
            $rules['gallery'] = str_replace('nullable','required',$rules['gallery'] ?? '');
            $rules['gallery.*'] = str_replace('nullable','required',$rules['gallery.*'] ?? '');

            $currentSubscription = Subscription::currentTenantSubscriptions()->first();
            $limit = $currentSubscription?->plan?->features['products']['limit'] ?? 999999;
            $totalProducts = $this->productService->count();

            if($totalProducts >= $limit){
                $this->popup('error','Product limit reached. Please upgrade your subscription to add more products.');
                return;
            }

        }else{
            $rules['sku'] = str_replace('unique:products,sku,deleted_at,NULL','unique:products,sku,'.$this->product->id.',id,deleted_at,NULL',$rules['sku'] ?? '');
        }

        if(!$this->validator($this->data,$rules))return;

        try{
            DB::beginTransaction();
            $product = $this->productService->save($this->product?->id,$this->data);
            AuditLog::log($action,  ['id' => $product->id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->popup('error','Error occurred while saving product: '.$e->getMessage());
            return;
        }

        $this->popup('success','Product saved successfully');

        $this->redirectWithTimeout(route('admin.products.add-edit',$product->id),2000);
    }

    function removeImage() {
        if(!$this->product) {
            unset($this->data['image']);
            return;
        }

        $this->productService->removeImage($this->product->id);

        $this->popup('success','Product image removed successfully');

        $this->retriveData();

        $this->reset('rules');
    }

    function removeGalleryImage($index) {
        if(isset($this->data['gallery'][$index])) {
            unset($this->data['gallery'][$index]);
            $this->data['gallery'] = array_values($this->data['gallery']);
        }elseif(isset($this->data['gallery_path'][$index]) && $this->product) {
            $this->productService->removeGalleryImageByPath($this->data['gallery_path'][$index]);
            $this->popup('success','Product gallery image removed successfully');
            $this->retriveData();
        }
    }

    #[On('re-render')]
    public function render()
    {
        $branches = $this->branchService->activeList();
        $categories = $this->categoryService->activeList();
        $brands = $this->brandService->activeList();
        // $taxes = $this->taxService->list();
        $units = $this->unitService->parentUnitsOnly();
        // if($this->data['image'] ?? false){
        //     dd($this->data['image']);
        // }

        return layoutView('products.add-edit-product', get_defined_vars())
            ->title(__('general.titles.'.($this->product ? 'edit' : 'add').'_product'));
    }
}
