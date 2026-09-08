<?php

namespace App\Http\Requests\Api\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class ConvertRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'order_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'ref_no' => 'nullable|string',
            'invoice_number' => 'nullable|string',
        ];
    }
}
