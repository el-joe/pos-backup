<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'branchId' => $this->branch_id,
            'category' => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null),
            'amount' => (float) $this->amount,
            'taxPercentage' => (float) $this->tax_percentage,
            'total' => (float) $this->total,
            'totalPaid' => (float) $this->total_paid,
            'expenseDate' => optional($this->expense_date)->format('Y-m-d'),
            'note' => $this->note,
            'type' => $this->type?->value,
            'createdAt' => $this->created_at?->format('Y-m-d\TH:i:sP'),
            'updatedAt' => $this->updated_at?->format('Y-m-d\TH:i:sP'),
        ];
    }
}
