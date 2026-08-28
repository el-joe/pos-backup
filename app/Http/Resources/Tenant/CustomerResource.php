<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
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
