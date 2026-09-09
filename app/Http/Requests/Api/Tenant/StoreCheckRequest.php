<?php

namespace App\Http\Requests\Api\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'direction' => 'required|in:received,issued',
            'amount' => 'required|numeric|min:0.01',
            'check_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'check_date' => 'nullable|date',
            'due_date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'customer_id' => 'required_if:direction,received|nullable|exists:users,id',
            'supplier_id' => 'required_if:direction,issued|nullable|exists:users,id',
        ];
    }
}
