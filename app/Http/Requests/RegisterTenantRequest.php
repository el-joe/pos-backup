<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // i want check if domain is valid domain name or subdomain to current domain
        return [
            'id'=>'required|string|unique:tenants|regex:/^[a-zA-Z0-9_]+$/',
            'phone'=>'required|string',
            'email'=>'required|email',
            'password'=>'required|string|confirmed|min:8',
            'country_id'=>'required|exists:countries,id',
            'domain'=>'required|string|unique:domains,domain|regex:/^(?!-)(?:[A-Za-z0-9-]{1,63}(?<!-)\.)+[A-Za-z]{2,}$/' // Validate domain name
        ];
    }

    function messages() {
        return [
            'id.regex'=>'The id may only contain letters, numbers, and underscores.',
            'domain.regex'=>'The domain format is invalid.'
        ];
    }
}
