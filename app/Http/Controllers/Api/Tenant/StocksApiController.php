<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Resources\Tenant\StockResource;
use App\Models\Tenant\Stock;
use Illuminate\Http\Request;

class StocksApiController extends ApiController
{
    protected function permission(): string
    {
        return 'stocks.list';
    }

    public function index(Request $request)
    {
        $stocks = Stock::with(['product', 'branch'])
            ->filter([
                'branch_id' => $request->query('branch_id'),
                'product_id' => $request->query('product_id'),
                'unit_id' => $request->query('unit_id'),
            ])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($stocks, StockResource::class);
    }

    public function show(int $id)
    {
        $stock = Stock::with(['product', 'branch'])->find($id);
        if (!$stock) {
            return $this->error('Not Found', 404);
        }

        return $this->success(new StockResource($stock));
    }
}
