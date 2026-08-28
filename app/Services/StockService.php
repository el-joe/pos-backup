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

    function addStock($productId, $unitId, $qty,$sellPrice = 0,$unitCost = 0, $branchId = null) {
        $product = $this->productService->find($productId, ['units']);

        if(!$product) {
            return null;
        }

        $branchId = $branchId ?: 0;

        return DB::transaction(function () use ($productId, $unitId, $qty, $sellPrice, $unitCost, $branchId) {
            $stock = Stock::forProduct($productId, $unitId, $branchId)->lockForUpdate()->first();

            if($stock) {
                $stock->update([
                    'qty' => $stock->qty + $qty,
                    'sell_price' => $sellPrice == 0 ? $stock->sell_price : $sellPrice,
                    'unit_cost' => $unitCost == 0 ? $stock->unit_cost : $unitCost,
                ]);
            }else{
                $stock = Stock::create([
                    'product_id' => $productId,
                    'unit_id' => $unitId,
                    'qty' => $qty,
                    'sell_price' => $sellPrice,
                    'unit_cost' => $unitCost,
                    'branch_id' => $branchId,
                ]);
            }

            return $stock;
        });
    }

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

            $stock->decrement('qty', $qty);
            return $stock;
        });
    }

    function reduceStock($productId, $unitId, $qty, $branchId = null) {
        $product = $this->productService->find($productId, ['units']);

        if(!$product) {
            return null;
        }

        $branchId = $branchId ?: 0;

        return DB::transaction(function () use ($productId, $unitId, $qty, $branchId) {
            $stock = Stock::forProduct($productId, $unitId, $branchId)->lockForUpdate()->first();

            if(!$stock) {
                Log::warning('reduceStock: stock row not found', [
                    'product_id' => $productId,
                    'unit_id' => $unitId,
                    'branch_id' => $branchId,
                ]);
                return null;
            }

            $stock->decrement('qty', $qty);
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
