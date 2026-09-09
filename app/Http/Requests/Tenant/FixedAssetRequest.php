<?php

namespace App\Http\Requests\Tenant;

use App\Models\Tenant\FixedAsset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FixedAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::buildRules($this->route('id') ?? $this->input('id'));
    }

    /**
     * Shared with Livewire so the create/edit form and any future API entry point enforce the
     * same invariants (prompt 16 item 2): exactly one depreciation basis, unique code ignoring
     * soft-deleted rows.
     */
    public static function buildRules($assetId = null): array
    {
        return [
            'data.branch_id' => 'nullable|integer',
            'data.code' => [
                'required', 'string', 'max:255',
                Rule::unique('fixed_assets', 'code')->whereNull('deleted_at')->ignore($assetId),
            ],
            'data.name' => 'required|string|max:255',
            'data.purchase_date' => 'nullable|date',
            'data.cost' => 'required|numeric|min:0',
            'data.salvage_value' => 'nullable|numeric|min:0',
            'data.depreciation_basis' => 'required_unless:data.status,'.FixedAsset::STATUS_UNDER_CONSTRUCTION.'|nullable|in:useful_life,rate',
            'data.useful_life_months' => 'nullable|integer|min:0|required_if:data.depreciation_basis,useful_life',
            'data.depreciation_rate' => 'nullable|numeric|min:0|max:100|required_if:data.depreciation_basis,rate',
            'data.depreciation_method' => 'required|in:straight_line,declining_balance,double_declining_balance',
            'data.depreciation_start_date' => 'nullable|date',
            'data.status' => 'required|in:active,under_construction,disposed,sold',
            'data.note' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'data.useful_life_months.required_if' => __('general.pages.fixed_assets.depreciation_basis_required'),
            'data.depreciation_rate.required_if' => __('general.pages.fixed_assets.depreciation_basis_required'),
            'data.depreciation_basis.required_unless' => __('general.pages.fixed_assets.depreciation_basis_required'),
        ];
    }
}
