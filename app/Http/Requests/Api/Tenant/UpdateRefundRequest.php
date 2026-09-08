<?php

namespace App\Http\Requests\Api\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Only non-financial metadata is editable here. Refund amounts/items are posted
            // to the ledger at creation time (via SellService::refundSaleItem() /
            // PurchaseService::refundPurchaseItem()); there is no service-level reversal for
            // a refund's ledger entries, so qty/items are intentionally not editable through
            // this endpoint (see RefundsApiController::destroy() for why destroy is not offered).
            'reason' => 'nullable|string|max:255',
        ];
    }
}
