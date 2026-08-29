<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'branchId'  => $this->branch_id,
            'orderType' => class_basename($this->order_type),
            'orderId'   => $this->order_id,
            'reason'    => $this->reason,
            'total'     => (float) $this->total,
            'items'     => $this->whenLoaded('items', fn () =>
                $this->items->map(fn ($item) => [
                    'id'        => $item->id,
                    'productId' => $item->product_id,
                    'unitId'    => $item->unit_id,
                    'qty'       => (float) $item->qty,
                ])
            ),
            'createdAt' => $this->created_at?->format('Y-m-d\TH:i:sP'),
        ];
    }
}
