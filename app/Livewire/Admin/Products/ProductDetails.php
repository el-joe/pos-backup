<?php

namespace App\Livewire\Admin\Products;

use App\Models\Tenant\Product;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\SaleItem;
use App\Models\Tenant\StockTakingProduct;
use App\Models\Tenant\StockTransferItem;
use App\Traits\LivewireOperations;
use Livewire\Component;

class ProductDetails extends Component
{
    use LivewireOperations;

    public Product $product;

    public string $tab = 'overview';

    public function mount(int $id): void
    {
        $this->product = Product::with([
            'unit',
            'category',
            'brand',
            'stocks.branch',
            'stocks.unit',
            'image',
            'gallery',
        ])->findOrFail($id);
    }

    public function setTab(string $tab): void
    {
        $allowed = ['overview', 'stock', 'sales', 'purchases', 'transfers', 'adjustments'];
        $this->tab = in_array($tab, $allowed, true) ? $tab : 'overview';
    }

    public function render()
    {
        $stocks = $this->product->stocks
            ->sortBy(fn($s) => ($s->branch?->name ?? '').'|'.($s->unit?->name ?? ''))
            ->values();

        $recentSalesItems = SaleItem::query()
            ->with(['sale.customer', 'sale.branch', 'unit'])
            ->where('product_id', $this->product->id)
            ->latest('id')
            ->limit(10)
            ->get();

        $recentPurchaseItems = PurchaseItem::query()
            ->with(['purchase.supplier', 'purchase.branch', 'unit'])
            ->where('product_id', $this->product->id)
            ->latest('id')
            ->limit(10)
            ->get();

        $recentTransferItems = StockTransferItem::query()
            ->with(['stockTransfer.fromBranch', 'stockTransfer.toBranch', 'unit'])
            ->where('product_id', $this->product->id)
            ->latest('id')
            ->limit(10)
            ->get();

        $recentAdjustments = StockTakingProduct::query()
            ->with(['stockTaking.branch', 'stockTaking.user', 'stock.unit'])
            ->where('product_id', $this->product->id)
            ->latest('id')
            ->limit(10)
            ->get();

        return layoutView('products.product-details', get_defined_vars())
            ->title($this->product->name);
    }
}
