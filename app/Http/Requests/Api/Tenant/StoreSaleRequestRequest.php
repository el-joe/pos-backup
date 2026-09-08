<?php

namespace App\Http\Requests\Api\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'quote_number' => 'required|string|max:255',
            'request_date' => 'nullable|date',
            'valid_until' => 'nullable|date',
            'status' => 'nullable|string',
            'tax_id' => 'nullable|exists:taxes,id',
            'tax_percentage' => 'nullable|numeric',
            'discount_id' => 'nullable|exists:discounts,id',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable|numeric',
            'max_discount_amount' => 'nullable|numeric',
            'note' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.unit_id' => 'required|exists:units,id',
            'products.*.qty' => 'required|numeric|min:0.001',
            'products.*.taxable' => 'nullable|boolean',
            'products.*.unit_cost' => 'nullable|numeric',
            'products.*.sell_price' => 'required|numeric',
        ];
    }
}
