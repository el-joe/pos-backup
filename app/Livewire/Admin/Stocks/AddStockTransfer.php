<?php

namespace App\Livewire\Admin\Stocks;

use App\Enums\AuditLogActionEnum;
use App\Enums\StockTransferStatusEnum;
use App\Models\Tenant\Admin;
use App\Models\Tenant\AuditLog;
use App\Services\BranchService;
use App\Services\ProductService;
use App\Services\StockService;
use App\Services\StockTransferService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class AddStockTransfer extends Component
{
    use LivewireOperations;

    private $stockTransferService, $branchService, $productService, $stockService;
    public $product_search = '';
    public $data = [
        'expenses' => []
    ];

    public $items = [];

    public $rules = [
        'from_branch_id' => 'required|exists:branches,id',
        'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
        'transfer_date' => 'required|date',
        'ref_no' => 'nullable|string|max:50|unique:stock_transfers,ref_no',
        'status' => 'required|in:pending,completed,cancelled,in_transit',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.unit_id' => 'required|exists:units,id',
        'items.*.qty' => 'required|numeric|min:1', // check if qty is <= max_stock
    ];

    function boot() {
        $this->stockTransferService = app(StockTransferService::class);
        $this->branchService = app(BranchService::class);
        $this->productService = app(ProductService::class);
        $this->stockService = app(StockService::class);
    }

    function mount(){
        if(admin()->branch_id){
            $this->data['from_branch_id'] = admin()->branch_id;
        }
    }

    function resetSearchInput() {
        $this->dispatch('reset-search-input');
        $this->product_search = '';
    }

    public function updatingProductSearch($value)
    {
        $product = $this->productService->search($value);
        if(!($this->data['from_branch_id']??null)) {
            $this->resetSearchInput();
            $this->popup('warning','Please select From Branch first');
            return;
        }
        if(!$product) {
            $this->alert('warning','Product not found');
            return;
        }

        $productDetails = $this->refactorProduct($product);

        $this->items[] = $productDetails;

        $this->resetSearchInput();
    }

    function updatingItems($value,$key){
        $parts = explode('.',$key);
        if(count($parts) != 2) return;
        $index = $parts[0];
        $productId = $this->items[$index]['id'] ?? null;
        if(!$productId) return;
        $product = $this->productService->find($productId);
        $key = $parts[1];
        $this->items[$index] = $this->refactorProduct($product,$index,$key,$value);
    }


    function refactorProduct($product,$index = null,$key = null,$value = null) : array {
        $orderProductData = $this->items[$index] ?? [];
        if($key){
            $orderProductData[$key] = $value;
        }

        $stock = $this->stockService->first([],[
            'branch_id' => $this->data['from_branch_id'] ?? 'N/A',
            'unit_id' => $orderProductData['unit_id'] ?? $product->unit_id,
            'product_id' => $product->id
        ]);

        $newArr = [
            'id' => $product->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'unit_id' => $orderProductData['unit_id'] ?? $product->unit_id,
            'units' => $product->units() ?? [],
            'qty' => $orderProductData['qty'] ?? 1,
            'unit_cost' => $stock->unit_cost ?? 0,
            'sell_price' => $stock->sell_price ?? 0,
            'max_stock' => $stock->qty ?? 0,
        ];

        return $newArr;
    }

    function delete($index) {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->alert('success','Product removed from the list');
    }

    public function addExpense()
    {
        $this->data['expenses'][] = [
            'description' => '',
            'amount' => 0,
            'expense_date' => null,
        ];
    }

    function updatingData($value,$key) {
        if($key == 'from_branch_id') {
            // reset items if from_branch_id changes
            $this->items = [];
            $this->resetSearchInput();
        }
    }

    function removeExpense($index) {
        if(isset($this->data['expenses'][$index])){
            unset($this->data['expenses'][$index]);
            $this->data['expenses'] = array_values($this->data['expenses']);
        }
        $this->alert('success','Expense removed from the list');
    }

    function save() {
        $dataToSave = [
            ...($this->data??[]),
            'items' => $this->items,
            'created_by' => admin()->id,
        ];

        if(!$this->validator($dataToSave))return;

        foreach ($dataToSave['items'] as $item) {
            if($item['qty'] > $item['max_stock']) {
                $this->alert('warning',"Insufficient stock for product {$item['name']}. Available stock: {$item['max_stock']}");
                return;
            }
        }

        $stockTransfer = $this->stockTransferService->save($dataToSave);

        Admin::whereType('super_admin')->each(function($admin) use ($stockTransfer) {
            $admin->notifyNewStockTransfer($stockTransfer);
        });

        AuditLog::log(AuditLogActionEnum::CREATE_STOCK_TRANSFER, ['id' => $stockTransfer->id]);

        $this->alert('success','Stock Transfer created successfully');

        return $this->redirectWithTimeout(route('admin.stocks.transfers.details', $stockTransfer),1500);
    }

    #[On('re-render')]
    public function render()
    {
        $branches = $this->branchService->activeList();
        $statuses = StockTransferStatusEnum::cases();
        $selectedBranches = $branches->whereIn('id',[$this->data['from_branch_id']??0,$this->data['to_branch_id']??0]);

        return layoutView('stocks.add-stock-transfer', get_defined_vars());
    }
}
