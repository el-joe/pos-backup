<?php

namespace App\Livewire\Admin\PurchaseRequests;

use App\Enums\PurchaseRequestStatusEnum;
use App\Models\Tenant\Stock;
use App\Services\BranchService;
use App\Services\ProductService;
use App\Services\PurchaseRequestService;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Livewire\Component;

class AddPurchaseRequest extends Component
{
    use LivewireOperations;

    private PurchaseRequestService $purchaseRequestService;
    private BranchService $branchService;
    private UserService $userService;
    private ProductService $productService;

    public array $data = [];
    public array $orderProducts = [];
    public string $product_search = '';

    public function boot(): void
    {
        $this->purchaseRequestService = app(PurchaseRequestService::class);
        $this->branchService = app(BranchService::class);
        $this->userService = app(UserService::class);
        $this->productService = app(ProductService::class);
    }

    public function mount(): void
    {
        $this->data['request_date'] = $this->data['request_date'] ?? now()->format('Y-m-d');
        $this->data['status'] = $this->data['status'] ?? PurchaseRequestStatusEnum::DRAFT->value;
        $this->data['request_number'] = $this->data['request_number'] ?? \App\Models\Tenant\PurchaseRequest::generateRequestNumber();

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

        $this->orderProducts[] = $this->refactorProduct($product);
        $this->reset('product_search');
        $this->dispatch('reset-search-input');
    }

    public function updatingOrderProducts($value, $key): void
    {
        $parts = explode('.', $key);
        if (count($parts) !== 2) {
            return;
        }

        $index = (int) $parts[0];
        $productId = $this->orderProducts[$index]['id'] ?? null;
        if (!$productId) {
            return;
        }

        $product = $this->productService->find($productId);
        if (!$product) {
            return;
        }

        $this->orderProducts[$index] = $this->refactorProduct($product, $index, $parts[1], $value);
    }

    private function refactorProduct($product, $index = null, $key = null, $value = null): array
    {
        $existing = $this->orderProducts[$index] ?? [];
        if ($key) {
            $existing[$key] = $value;
        }

        $branchId = $this->data['branch_id'] ?? null;
        $unitId = (int) ($existing['unit_id'] ?? $product->unit_id);

        $stock = null;
        if ($branchId) {
            $stock = Stock::where('product_id', $product->id)
                ->where('unit_id', $unitId)
                ->where('branch_id', $branchId)
                ->first();
        }

        $purchasePrice = (float) ($existing['purchase_price'] ?? ($stock?->unit_cost ?? 0));
        $discountPercentage = (float) ($existing['discount_percentage'] ?? 0);
        $taxPercentage = (float) ($existing['tax_percentage'] ?? 0);
        $qty = (float) ($existing['qty'] ?? 1);
        $xMargin = (float) ($existing['x_margin'] ?? 0);

        $unitCostAfterDiscount = $purchasePrice - ($purchasePrice * $discountPercentage / 100);
        $totalNetCost = $unitCostAfterDiscount * $qty;
        $taxAmount = $totalNetCost * ($taxPercentage / 100);
        $subtotalInclTax = $totalNetCost + $taxAmount;
        $sellPrice = (float) ($existing['sell_price'] ?? ($stock?->sell_price ?? ($unitCostAfterDiscount * (1 + ($xMargin / 100)))));

        return [
            'id' => $product->id,
            'name' => $product->name,
            'unit_id' => $unitId,
            'qty' => $qty,
            'purchase_price' => $purchasePrice,
            'discount_percentage' => $discountPercentage,
            'tax_percentage' => $taxPercentage,
            'x_margin' => $xMargin,
            'unit_cost_after_discount' => $unitCostAfterDiscount,
            'total_net_cost' => $totalNetCost,
            'tax_amount' => $taxAmount,
            'subtotal_incl_tax' => $subtotalInclTax,
            'sell_price' => $sellPrice,
            'units' => $product->units(),
        ];
    }

    public function deleteProduct($index): void
    {
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts);
    }

    public function saveRequest(): void
    {
        if(!$this->validator($this->data,[
            'branch_id' => 'required|integer',
            'request_date' => 'required|date',
            'request_number' => 'required|string|max:255',
            'status' => 'required|string',
        ]))return;

        if(!$this->validator(['orderProducts' => $this->orderProducts],[
            'orderProducts' => 'required|array|min:1',
            'orderProducts.*.id' => 'required|integer',
            'orderProducts.*.unit_id' => 'required|integer',
            'orderProducts.*.qty' => 'required|numeric|min:1',
        ]))return;

        $request = $this->purchaseRequestService->save(null, [
            'created_by' => admin()->id ?? null,
            'supplier_id' => $this->data['supplier_id'] ?? null,
            'branch_id' => $this->data['branch_id'],
            'request_number' => $this->data['request_number'],
            'request_date' => $this->data['request_date'],
            'status' => $this->data['status'],
            'tax_percentage' => $this->data['tax_percentage'] ?? 0,
            'discount_type' => $this->data['discount_type'] ?? null,
            'discount_value' => $this->data['discount_value'] ?? 0,
            'note' => $this->data['note'] ?? null,
            'orderProducts' => $this->orderProducts,
        ]);

        $this->alert('success', 'Purchase request saved.');
        $this->redirect(route('admin.purchase-requests.details', $request->id));
    }

    public function render()
    {
        $branches = $this->branchService->activeList();
        $suppliers = $this->userService->suppliersList();
        $statuses = PurchaseRequestStatusEnum::cases();

        return layoutView('purchase-requests.add-purchase-request', get_defined_vars())
            ->title('Create Purchase Request');
    }
}
