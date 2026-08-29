<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'requestNumber' => $this->request_number,
            'status'        => $this->status?->value,
            'supplierId'    => $this->supplier_id,
            'supplier'      => $this->whenLoaded('supplier', fn () => $this->supplier ? ['id' => $this->supplier->id, 'name' => $this->supplier->name] : null),
            'branchId'      => $this->branch_id,
            'requestDate'   => optional($this->request_date)->format('Y-m-d'),
            'note'          => $this->note,
            'items'         => $this->whenLoaded('items', fn () =>
                $this->items->map(fn ($item) => [
                    'id'        => $item->id,
                    'productId' => $item->product_id,
                    'unitId'    => $item->unit_id,
                    'qty'       => (float) $item->qty,
                ])
            ),
            'createdAt'     => $this->created_at?->format('Y-m-d\TH:i:sP'),
        ];
    }
}
