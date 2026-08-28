<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * NOTE: the tenant `products` table only has a single `name` column
     * (no name_en/name_ar bilingual columns exist anywhere on this model).
     * nameEn/nameAr both mirror that single column so API consumers get a
     * consistent shape regardless of locale.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nameEn' => $this->name,
            'nameAr' => $this->name,
            'code' => $this->code,
            'sku' => $this->sku,
            'sellPrice' => (float) ($this->stock_sell_price ?: 0),
            'qtyInStock' => (float) $this->all_stock,
            'active' => (bool) $this->active,
            'category' => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null),
            'brand' => $this->whenLoaded('brand', fn () => $this->brand ? [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
            ] : null),
            'unit' => $this->whenLoaded('unit', fn () => $this->unit ? [
                'id' => $this->unit->id,
                'name' => $this->unit->name,
            ] : null),
            'createdAt' => $this->created_at?->format('Y-m-d\TH:i:sP'),
            'updatedAt' => $this->updated_at?->format('Y-m-d\TH:i:sP'),
        ];
    }
}
