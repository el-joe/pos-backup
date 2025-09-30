<?php

namespace App\Services;

use App\Repositories\StockRepository;

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

        if($product) {
            $unitStock = $product->stocks()->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            },function ($q) {
                $q->where(function($q) {
                    $q->whereNull('branch_id')->orWhere('branch_id', 0);
                });
            })->firstWhere('unit_id', $unitId);

            if($unitStock) {
                $unitStock->update([
                    'qty' => $unitStock->qty + $qty,
                    'sell_price' => $sellPrice,
                    'unit_cost' => $unitCost,
                ]);
                return $unitStock;
            }else{
                return $product->stocks()->create([
                    'product_id' => $productId,
                    'unit_id' => $unitId,
                    'qty' => $qty,
                    'sell_price' => $sellPrice,
                    'unit_cost' => $unitCost,
                    'branch_id' => $branchId,
                ]);
            }
        }

        return null;
    }

    function removeFromStock($productId, $unitId, $qty, $branchId = null) {
        $product = $this->productService->find($productId, ['units']);

        if($product) {
            $unitStock = $product->stocks()->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            },function ($q) {
                $q->where(function($q) {
                    $q->whereNull('branch_id')->orWhere('branch_id', 0);
                });
            })->firstWhere('unit_id', $unitId);

            if($unitStock) {
                // decrease stock qty
                $unitStock->decrement('qty', $qty);
                return $unitStock;
            }
        }

        return null;
    }

    function reduceStock($productId, $unitId, $qty, $branchId = null) {
        $product = $this->productService->find($productId, ['units']);

        if($product) {
            $unitStock = $product->stocks()->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            },function ($q) {
                $q->where(function($q) {
                    $q->whereNull('branch_id')->orWhere('branch_id', 0);
                });
            })->firstWhere('unit_id', $unitId);

            if($unitStock) {
                // decrease stock qty
                $unitStock->decrement('qty', $qty);
                return $unitStock;
            }
        }

        return null;
    }

    function delete($id) {
        $branch = $this->repo->find($id);
        if($branch) {
            return $branch->delete();
        }

        return false;
    }
}
