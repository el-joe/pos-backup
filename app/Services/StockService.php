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

    function addStock($productId, $unitId, $qty,$sellPrice = 0) {
        $product = $this->productService->find($productId, ['units']);

        if($product) {
            $unitStock = $product->stocks()->firstWhere('unit_id', $unitId);
            if($unitStock) {
                $unitStock->update([
                    'qty' => $unitStock->qty + $qty,
                    'sell_price' => $sellPrice
                ]);
                return $unitStock;
            }else{
                return $product->stocks()->create([
                    'product_id' => $productId,
                    'unit_id' => $unitId,
                    'qty' => $qty,
                    'sell_price' => $sellPrice
                ]);
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
