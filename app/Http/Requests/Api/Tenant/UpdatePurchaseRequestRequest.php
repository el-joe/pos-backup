<?php

namespace App\Http\Requests\Api\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'nullable|exists:users,id',
            'branch_id' => 'sometimes|required|exists:branches,id',
            'request_number' => 'sometimes|required|string|max:255',
            'request_date' => 'nullable|date',
            'status' => 'nullable|string',
            'tax_id' => 'nullable|exists:taxes,id',
            'tax_percentage' => 'nullable|numeric',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable|numeric',
            'note' => 'nullable|string',
            'orderProducts' => 'sometimes|required|array|min:1',
            'orderProducts.*.id' => 'required_with:orderProducts|exists:products,id',
            'orderProducts.*.unit_id' => 'required_with:orderProducts|exists:units,id',
            'orderProducts.*.qty' => 'required_with:orderProducts|numeric|min:0.001',
            'orderProducts.*.purchase_price' => 'required_with:orderProducts|numeric',
            'orderProducts.*.discount_percentage' => 'nullable|numeric',
            'orderProducts.*.tax_percentage' => 'nullable|numeric',
            'orderProducts.*.x_margin' => 'nullable|numeric',
            'orderProducts.*.sell_price' => 'required_with:orderProducts|numeric',
        ];
    }
}
