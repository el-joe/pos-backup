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
        return [
            'id'=>'required|string|unique:tenants',
            'phone'=>'required|string',
            'email'=>'required|email',
            'password'=>'required|string|confirmed|min:8',
            'domain'=>'required|string|unique:domains,domain'
        ];
    }
}
