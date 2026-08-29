<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'productId' => $this->product_id,
            'product'   => $this->whenLoaded('product', fn () => $this->product ? ['id' => $this->product->id, 'name' => $this->product->name] : null),
            'branchId'  => $this->branch_id,
            'branch'    => $this->whenLoaded('branch', fn () => $this->branch ? ['id' => $this->branch->id, 'name' => $this->branch->name] : null),
            'unitId'    => $this->unit_id,
            'qty'       => (float) $this->qty,
            'unitCost'  => (float) $this->unit_cost,
            'sellPrice' => (float) $this->sell_price,
        ];
    }
}
