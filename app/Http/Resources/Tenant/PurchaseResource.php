<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'refNo' => $this->ref_no,
            'supplier' => $this->whenLoaded('supplier', fn () => $this->supplier ? [
                'id' => $this->supplier->id,
                'name' => $this->supplier->name,
            ] : null),
            'branchId' => $this->branch_id,
            'date' => optional($this->order_date)->format('Y-m-d\TH:i:sP'),
            'status' => $this->status?->value,
            'total' => (float) $this->total_amount,
            'paidAmount' => (float) $this->paid_amount,
            'dueAmount' => (float) $this->due_amount,
            'isDeferred' => (bool) $this->is_deferred,
            'items' => $this->whenLoaded('purchaseItems', fn () => $this->purchaseItems->map(fn ($item) => [
                'id' => $item->id,
                'productId' => $item->product_id,
                'productName' => $item->product?->name,
                'unitId' => $item->unit_id,
                'qty' => (float) $item->qty,
                'purchasePrice' => (float) $item->purchase_price,
                'sellPrice' => (float) $item->sell_price,
            ])),
            'createdAt' => $this->created_at?->format('Y-m-d\TH:i:sP'),
            'updatedAt' => $this->updated_at?->format('Y-m-d\TH:i:sP'),
        ];
    }
}
