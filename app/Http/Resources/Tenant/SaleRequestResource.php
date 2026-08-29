<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'quoteNumber' => $this->quote_number,
            'status'      => $this->status?->value,
            'customerId'  => $this->customer_id,
            'customer'    => $this->whenLoaded('customer', fn () => $this->customer ? ['id' => $this->customer->id, 'name' => $this->customer->name] : null),
            'branchId'    => $this->branch_id,
            'requestDate' => optional($this->request_date)->format('Y-m-d'),
            'validUntil'  => optional($this->valid_until)->format('Y-m-d'),
            'note'        => $this->note,
            'items'       => $this->whenLoaded('items', fn () =>
                $this->items->map(fn ($item) => [
                    'id'        => $item->id,
                    'productId' => $item->product_id,
                    'unitId'    => $item->unit_id,
                    'qty'       => (float) $item->qty,
                    'sellPrice' => (float) $item->sell_price,
                ])
            ),
            'createdAt'   => $this->created_at?->format('Y-m-d\TH:i:sP'),
        ];
    }
}
