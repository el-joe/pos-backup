<?php

namespace App\Http\Requests\Api\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|exists:users,id',
            'branch_id' => 'sometimes|required|exists:branches,id',
            'quote_number' => 'sometimes|required|string|max:255',
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
            'products' => 'sometimes|required|array|min:1',
            'products.*.id' => 'required_with:products|exists:products,id',
            'products.*.unit_id' => 'required_with:products|exists:units,id',
            'products.*.qty' => 'required_with:products|numeric|min:0.001',
            'products.*.taxable' => 'nullable|boolean',
            'products.*.unit_cost' => 'nullable|numeric',
            'products.*.sell_price' => 'required_with:products|numeric',
        ];
    }
}
