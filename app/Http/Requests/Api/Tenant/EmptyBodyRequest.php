<?php

namespace App\Http\Requests\Api\Tenant;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Used for action endpoints that take no meaningful body (deliver-deferred, receive-inventory,
 * approve/reject) but still route through a FormRequest for consistency and so throttling /
 * future body validation has a natural place to live.
 */
class EmptyBodyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note' => 'nullable|string|max:255',
        ];
    }
}
