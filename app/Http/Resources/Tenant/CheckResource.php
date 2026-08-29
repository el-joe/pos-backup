<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'branchId'    => $this->branch_id,
            'direction'   => $this->direction,
            'status'      => $this->status,
            'amount'      => (float) $this->amount,
            'checkNumber' => $this->check_number,
            'bankName'    => $this->bank_name,
            'checkDate'   => optional($this->check_date)->format('Y-m-d'),
            'dueDate'     => optional($this->due_date)->format('Y-m-d'),
            'note'        => $this->note,
            'customerId'  => $this->customer_id,
            'supplierId'  => $this->supplier_id,
            'createdAt'   => $this->created_at?->format('Y-m-d\TH:i:sP'),
        ];
    }
}
