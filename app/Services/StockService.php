<?php

namespace App\Services;

use App\Models\Tenant\Stock;
use App\Repositories\StockRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    public function __construct(private StockRepository $repo,private ProductService $productService) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function activeList($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter + [
            'active' => 1
        ], $perPage, $orderByDesc);
    }


    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function first($relations = [], $filter = [])
    {
        return $this->repo->first($relations, $filter);
    }

    function save($id = null,$data) {
        if($id) {
            $branch = $this->repo->find($id);
            if($branch) {
                $branch->update($data);
                return $branch;
            }
        }

        return $this->repo->create($data);
    }

    /**
     * Receives stock at $unitCost and folds it into the existing weighted-average cost.
     * A $unitCost of 0 never overwrites the existing average — it is treated as "unknown"
     * and the receipt is valued at the current average instead (so the average is unaffected
     * by e.g. stock returns or zero-priced purchase rows).
     */
    function addStock($productId, $unitId, $qty,$sellPrice = 0,$unitCost = 0, $branchId = null) {
        $product = $this->productService->find($productId, ['units']);

        if(!$product) {
            return null;
        }

        $branchId = $branchId ?: 0;

        return DB::transaction(function () use ($productId, $unitId, $qty, $sellPrice, $unitCost, $branchId) {
            $stock = Stock::forProduct($productId, $unitId, $branchId)->lockForUpdate()->first();

            $qty = (float) $qty;

            if($stock) {
                $incomingUnitCost = (float) $unitCost == 0.0 ? (float) $stock->unit_cost : (float) $unitCost;

                $newTotalValue = (float) $stock->total_value + ($qty * $incomingUnitCost);
                $newQty = (float) $stock->qty + $qty;
                $newUnitCost = $newQty > 0 ? round($newTotalValue / $newQty, 4) : $incomingUnitCost;

                $stock->update([
                    'qty' => $newQty,
                    'total_value' => round($newTotalValue, 4),
                    'sell_price' => (float) $sellPrice == 0.0 ? $stock->sell_price : $sellPrice,
                    'unit_cost' => $newUnitCost,
                ]);
            }else{
                $totalValue = round($qty * (float) $unitCost, 4);
                $stock = Stock::create([
                    'product_id' => $productId,
                    'unit_id' => $unitId,
                    'qty' => $qty,
                    'sell_price' => $sellPrice,
                    'unit_cost' => $unitCost,
                    'total_value' => $totalValue,
                    'branch_id' => $branchId,
                ]);
            }

            return $stock;
        });
    }

    /**
     * Issues stock at the current weighted-average cost, decrementing total_value by
     * qty * unit_cost (the true issue value). The returned Stock's unit_cost is unchanged
     * by the removal — callers use it to post COGS/inventory lines at the real cost, never
     * the client-supplied price.
     */
    function removeFromStock($productId, $unitId, $qty, $branchId = null) {
        $product = $this->productService->find($productId, ['units']);

        if(!$product) {
            return null;
        }

        $branchId = $branchId ?: 0;

        return DB::transaction(function () use ($productId, $unitId, $qty, $branchId) {
            $stock = Stock::forProduct($productId, $unitId, $branchId)->lockForUpdate()->first();

            if(!$stock) {
                Log::warning('removeFromStock: stock row not found', [
                    'product_id' => $productId,
                    'unit_id' => $unitId,
                    'branch_id' => $branchId,
                ]);
                return null;
            }

            $qty = (float) $qty;
            $newQty = (float) $stock->qty - $qty;

            if($newQty < 0 && !tenantSetting('allow_negative_stock', false)) {
                throw new \RuntimeException(__('general.messages.negative_stock_not_allowed', [
                    'available' => $stock->qty,
                    'requested' => $qty,
                ]));
            }

            $issueUnitCost = (float) $stock->unit_cost;
            $issueValue = $issueUnitCost * $qty;
            $newTotalValue = (float) $stock->total_value - $issueValue;

            $stock->update([
                'qty' => $newQty,
                'total_value' => round($newTotalValue, 4),
                // unit_cost is unchanged by an issue under weighted-average costing — recompute
                // only to absorb rounding drift, falling back to the issue cost when qty hits 0.
                'unit_cost' => $newQty > 0 ? round($newTotalValue / $newQty, 4) : $issueUnitCost,
            ]);

            return $stock;
        });
    }

    function delete($id) {
        $branch = $this->repo->find($id);
        if($branch) {
            return $branch->delete();
        }

        return false;
    }
}
