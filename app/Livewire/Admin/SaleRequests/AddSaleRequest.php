<?php

namespace App\Livewire\Admin\SaleRequests;

use App\Enums\SaleRequestStatusEnum;
use App\Models\Tenant\Stock;
use App\Services\BranchService;
use App\Services\ProductService;
use App\Services\SaleRequestService;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Livewire\Component;

class AddSaleRequest extends Component
{
    use LivewireOperations;

    private SaleRequestService $saleRequestService;
    private BranchService $branchService;
    private UserService $userService;
    private ProductService $productService;

    public array $data = [];
    public array $products = [];
    public string $product_search = '';

    public function boot(): void
    {
        $this->saleRequestService = app(SaleRequestService::class);
        $this->branchService = app(BranchService::class);
        $this->userService = app(UserService::class);
        $this->productService = app(ProductService::class);
    }

    public function mount(): void
    {
        $this->data['request_date'] = $this->data['request_date'] ?? now()->format('Y-m-d');
        $this->data['valid_until'] = $this->data['valid_until'] ?? now()->addDays(7)->format('Y-m-d');
        $this->data['status'] = $this->data['status'] ?? SaleRequestStatusEnum::DRAFT->value;
        $this->data['quote_number'] = $this->data['quote_number'] ?? \App\Models\Tenant\SaleRequest::generateQuoteNumber();
        $this->data['tax_percentage'] = $this->data['tax_percentage'] ?? 0;
        $this->data['discount_value'] = $this->data['discount_value'] ?? 0;

        if (admin()->branch_id) {
            $this->data['branch_id'] = admin()->branch_id;
        }
    }

    public function updatingProductSearch($value): void
    {
        if (empty($value)) {
            return;
        }

        $product = $this->productService->search($value);
        if (!$product) {
            return;
        }

        $this->products[] = $this->refactorProduct($product);
        $this->reset('product_search');
        $this->dispatch('reset-search-input');
    }

    public function updatingProducts($value, $key): void
    {
        $parts = explode('.', $key);
        if (count($parts) !== 2) {
            return;
        }

        $index = (int) $parts[0];
        $productId = $this->products[$index]['id'] ?? null;
        if (!$productId) {
            return;
        }

        $product = $this->productService->find($productId);
        if (!$product) {
            return;
        }

        $this->products[$index] = $this->refactorProduct($product, $index, $parts[1], $value);
    }

    private function refactorProduct($product, $index = null, $key = null, $value = null): array
    {
        $existing = $this->products[$index] ?? [];
        if ($key) {
            $existing[$key] = $value;
        }

        $branchId = $this->data['branch_id'] ?? null;
        $stock = null;
        if ($branchId) {
            $stock = Stock::where('product_id', $product->id)
                ->where('unit_id', $existing['unit_id'] ?? $product->unit_id)
                ->where('branch_id', $branchId)
                ->first();
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'unit_id' => (int) ($existing['unit_id'] ?? $product->unit_id),
            'qty' => (float) ($existing['qty'] ?? 1),
            'taxable' => (int) ($existing['taxable'] ?? ($product->taxable ?? 1)),
            'unit_cost' => (float) ($existing['unit_cost'] ?? ($stock?->unit_cost ?? 0)),
            'sell_price' => (float) ($existing['sell_price'] ?? ($stock?->sell_price ?? 0)),
            'units' => $product->units(),
        ];
    }

    public function deleteProduct($index): void
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }

    public function saveRequest(): void
    {
        $this->validate([
            'data.branch_id' => 'required|integer',
            'data.customer_id' => 'required|integer',
            'data.request_date' => 'required|date',
            'data.valid_until' => 'nullable|date|after_or_equal:data.request_date',
            'data.quote_number' => 'required|string|max:255',
            'data.status' => 'required|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer',
            'products.*.unit_id' => 'required|integer',
            'products.*.qty' => 'required|numeric|min:1',
            'products.*.sell_price' => 'required|numeric|min:0',
        ]);

        $request = $this->saleRequestService->save(null, [
            'created_by' => admin()->id ?? null,
            'customer_id' => $this->data['customer_id'],
            'branch_id' => $this->data['branch_id'],
            'quote_number' => $this->data['quote_number'],
            'request_date' => $this->data['request_date'],
            'valid_until' => $this->data['valid_until'] ?? null,
            'status' => $this->data['status'],
            'tax_percentage' => $this->data['tax_percentage'] ?? 0,
            'discount_type' => $this->data['discount_type'] ?? null,
            'discount_value' => $this->data['discount_value'] ?? 0,
            'note' => $this->data['note'] ?? null,
            'products' => $this->products,
        ]);

        $this->alert('success', 'Sale request saved.');
        $this->redirect(route('admin.sale-requests.details', $request->id));
    }

    public function render()
    {
        $branches = $this->branchService->activeList();
        $customers = $this->userService->customersList();
        $statuses = SaleRequestStatusEnum::cases();

        return layoutView('sale-requests.add-sale-request', get_defined_vars())
            ->title('Create Sale Request');
    }
}
