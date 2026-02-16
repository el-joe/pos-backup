<?php


namespace App\Livewire\Admin\Reports\Inventory;

use App\Models\Tenant\Product;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class StockMovementReport extends Component
{
    public $report = [];

    public function render()
    {
        $this->report = $this->getStockMovementReport();

        return layoutView('reports.inventory.stock-movement-report', [
            'report' => $this->report,
        ]);
    }

    protected function getStockMovementReport()
    {
        // Aggregate inflow (purchases), outflow (sales), stock taking, and stock transfers per product
        $products = Product::whereHas('stocks')->get();
        $report = [];
        foreach ($products as $product) {
            foreach ($product->units() as $unit) {
                $inflow = DB::table('purchase_items')
                    ->where('product_id', $product->id)
                    ->where('unit_id', $unit->id)
                    ->sum(DB::raw('qty - refunded_qty'));

                $outflow = DB::table('sale_items')
                    ->where('product_id', $product->id)
                    ->where('unit_id', $unit->id)
                    ->sum(DB::raw('qty - refunded_qty'));

                // Stock taking adjustment: actual_qty - current_qty
                $stockTakingAdjustment = DB::table('stock_taking_products')
                    ->join('stocks', 'stock_taking_products.stock_id', '=', 'stocks.id')
                    ->where('stocks.product_id', $product->id)
                    ->where('stocks.unit_id', $unit->id)
                    ->sum(DB::raw('actual_qty - current_qty'));

                // Stock transfers: sum qty sent and received
                $transferSent = DB::table('stock_transfer_items')
                    ->join('stock_transfers', 'stock_transfer_items.stock_transfer_id', '=', 'stock_transfers.id')
                    ->where('stock_transfer_items.product_id', $product->id)
                    ->where('stock_transfer_items.unit_id', $unit->id)
                    ->sum(DB::raw('CASE WHEN stock_transfers.from_branch_id IS NOT NULL THEN stock_transfer_items.qty ELSE 0 END'));

                $transferReceived = DB::table('stock_transfer_items')
                    ->join('stock_transfers', 'stock_transfer_items.stock_transfer_id', '=', 'stock_transfers.id')
                    ->where('stock_transfer_items.product_id', $product->id)
                    ->where('stock_transfer_items.unit_id', $unit->id)
                    ->sum(DB::raw('CASE WHEN stock_transfers.to_branch_id IS NOT NULL THEN stock_transfer_items.qty ELSE 0 END'));

                $report[] = [
                    'product_name' => $product->name . ' (' . $unit->name . ')',
                    'inflow' => $inflow + $transferReceived,
                    'outflow' => $outflow + $transferSent,
                    'adjustment' => $stockTakingAdjustment,
                    'current_stock' => $inflow - $outflow + $stockTakingAdjustment,
                ];
            }
        }
        return $report;
    }
}
