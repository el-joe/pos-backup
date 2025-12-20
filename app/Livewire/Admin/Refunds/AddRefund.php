<?php

namespace App\Livewire\Admin\Refunds;

use App\Models\Tenant\Product;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Refund;
use App\Models\Tenant\RefundItem;
use App\Models\Tenant\Sale;
use App\Services\ProductService;
use App\Traits\LivewireOperations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AddRefund extends Component
{
    use LivewireOperations;

    private $productService;

    public $data = [];

    public $refundItems = [];

    public $product_search = '';

    public $rules = [
        'order_type' => 'required|in:sale,purchase',
        'order_id' => 'required|integer|min:1',
        'total' => 'required|numeric|min:0.01',
        'reason' => 'nullable|string',
        'refundItems' => 'required|array|min:1',
        'refundItems.*.id' => 'required|integer|exists:products,id',
        'refundItems.*.unit_id' => 'required|integer|exists:units,id',
        'refundItems.*.qty' => 'required|numeric|min:0.0001',
    ];

    function boot() {
        $this->productService = app(ProductService::class);
    }

    function mount() {
        $this->data['order_type'] = request()->query('order_type', $this->data['order_type'] ?? null);
        $this->data['order_id'] = request()->query('order_id', $this->data['order_id'] ?? null);
    }

    public function updatingProductSearch($value)
    {
        $product = $this->productService->search($value);
        if(!$product) {
            $this->alert('warning','Product not found');
            return;
        }

        if(empty($value)) return;

        $this->refundItems[] = $this->refactorProduct($product);

        $this->reset('product_search');
        $this->dispatch('reset-search-input');
    }

    function updatingRefundItems($value, $key) {
        $parts = explode('.', $key);
        if(count($parts) != 2) return;
        $index = $parts[0];
        $productId = $this->refundItems[$index]['id'] ?? null;
        if(!$productId) return;
        $product = $this->productService->find($productId);
        if(!$product) return;
        $field = $parts[1];
        $this->refundItems[$index] = $this->refactorProduct($product, $index, $field, $value);
    }

    function refactorProduct($product, $index = null, $key = null, $value = null) : array {
        $existing = $this->refundItems[$index] ?? [];
        if($key){
            $existing[$key] = $value;
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'unit_id' => $existing['unit_id'] ?? '',
            'units' => $product->units() ?? [],
            'qty' => $existing['qty'] ?? 1,
        ];
    }

    function deleteItem($index) {
        unset($this->refundItems[$index]);
        $this->refundItems = array_values($this->refundItems);
        $this->alert('success','Item removed');
    }

    private function resolveOrderModel(): ?Model
    {
        $type = $this->data['order_type'] ?? null;
        $orderId = $this->data['order_id'] ?? null;

        if (!$type || !$orderId) {
            return null;
        }

        return match ($type) {
            'sale' => Sale::query()->where('id', $orderId)->first(),
            'purchase' => Purchase::query()->where('id', $orderId)->first(),
            default => null,
        };
    }

    function saveRefund() {
        if(!$this->validator([
            ...$this->data,
            'refundItems' => $this->refundItems,
        ])) return;

        $order = $this->resolveOrderModel();
        if (!$order) {
            $this->popup('error', 'Order not found');
            return;
        }

        try {
            DB::beginTransaction();

            $refund = new Refund([
                'total' => $this->data['total'],
                'reason' => $this->data['reason'] ?? null,
                'created_by' => admin()->id ?? null,
            ]);
            $refund->order()->associate($order);
            $refund->save();

            foreach ($this->refundItems as $item) {
                RefundItem::create([
                    'refund_id' => $refund->id,
                    'product_id' => $item['id'],
                    'unit_id' => $item['unit_id'],
                    'qty' => $item['qty'],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->popup('error','Error occurred while creating refund: '.$e->getMessage());
            return;
        }

        $this->alert('success','Refund created successfully');

        return $this->redirectWithTimeout(route('admin.refunds.list'), 800);
    }

    public function render()
    {
        $orderTypes = [
            'sale' => 'Sale',
            'purchase' => 'Purchase',
        ];

        return layoutView('refunds.add-refund', get_defined_vars())
            ->title(__('general.titles.add_refund'));
    }
}
