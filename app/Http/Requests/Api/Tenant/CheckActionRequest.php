<?php

namespace App\Http\Requests\Api\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class CheckActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => 'nullable|integer|exists:accounts,id',
            'note' => 'nullable|string|max:255',
            'bank_charge' => 'nullable|numeric|min:0.01',
            'bank_charge_account_id' => 'nullable|integer|exists:accounts,id',
            'replaced_by_check_id' => 'nullable|integer|exists:checks,id',
        ];
    }
}
