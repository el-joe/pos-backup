<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoiceNumber' => $this->invoice_number,
            'customer' => $this->whenLoaded('customer', fn () => $this->customer ? [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
            ] : null),
            'branchId' => $this->branch_id,
            'date' => optional($this->order_date)->format('Y-m-d\TH:i:sP'),
            'subTotal' => (float) $this->sub_total,
            'discountAmount' => (float) $this->discount_amount,
            'taxAmount' => (float) $this->tax_amount,
            'total' => (float) $this->grand_total_amount,
            'paidAmount' => (float) $this->paid_amount,
            'dueAmount' => (float) $this->due_amount,
            'status' => $this->payment_status,
            'isDeferred' => (bool) $this->is_deferred,
            'items' => $this->whenLoaded('saleItems', fn () => $this->saleItems->map(fn ($item) => [
                'id' => $item->id,
                'productId' => $item->product_id,
                'productName' => $item->product?->name,
                'unitId' => $item->unit_id,
                'qty' => (float) $item->qty,
                'sellPrice' => (float) $item->sell_price,
                'taxable' => (bool) $item->taxable,
            ])),
            'createdAt' => $this->created_at?->format('Y-m-d\TH:i:sP'),
            'updatedAt' => $this->updated_at?->format('Y-m-d\TH:i:sP'),
        ];
    }
}
