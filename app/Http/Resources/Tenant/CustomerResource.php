<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'type' => $this->type?->value,
            'active' => (bool) $this->active,
            'balance' => (float) $this->computeBalance(),
            'salesCount' => (int) ($this->sales_count ?? $this->purchases_count ?? 0),
            'createdAt' => $this->created_at?->format('Y-m-d\TH:i:sP'),
            'updatedAt' => $this->updated_at?->format('Y-m-d\TH:i:sP'),
        ];

        if ($this->relationLoaded('recentSales')) {
            $data['recentSales'] = $this->recentSales->map(fn ($sale) => [
                'id' => $sale->id,
                'invoiceNumber' => $sale->invoice_number ?? $sale->id,
                'total' => (float) $sale->grand_total_amount,
                'paidAmount' => (float) $sale->paid_amount,
                'dueAmount' => (float) $sale->due_amount,
                'date' => optional($sale->order_date)->format('Y-m-d') ?? $sale->created_at?->format('Y-m-d'),
            ])->values();
        }

        if ($this->relationLoaded('recentPurchases')) {
            $data['recentPurchases'] = $this->recentPurchases->map(fn ($purchase) => [
                'id' => $purchase->id,
                'invoiceNumber' => $purchase->ref_no ?? $purchase->id,
                'total' => (float) $purchase->total_amount,
                'paidAmount' => (float) $purchase->paid_amount,
                'dueAmount' => (float) $purchase->due_amount,
                'date' => optional($purchase->order_date)->format('Y-m-d') ?? $purchase->created_at?->format('Y-m-d'),
            ])->values();
        }

        return $data;
    }

    /**
     * Mirrors CustomerPayable/SupplierPayable: balance is the sum of
     * due_amount across the user's non-deferred sales/purchases.
     */
    protected function computeBalance(): float
    {
        if ($this->type?->value === 'supplier') {
            $purchases = $this->relationLoaded('purchases')
                ? $this->purchases
                : $this->purchases()->where('is_deferred', 0)->get();

            return (float) $purchases->where('is_deferred', 0)->sum(fn ($p) => (float) $p->due_amount);
        }

        $sales = $this->relationLoaded('sales')
            ? $this->sales
            : $this->sales()->where('is_deferred', 0)->get();

        return (float) $sales->where('is_deferred', 0)->sum(fn ($s) => (float) $s->due_amount);
    }
}
