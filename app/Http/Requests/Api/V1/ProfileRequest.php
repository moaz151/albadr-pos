<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $clientId = auth('api')->id();
        
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:clients,email,' . $clientId,
            'phone' => 'nullable|string|max:20|unique:clients,phone,' . $clientId,
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'nullable|string|max:500',
        ];
    }
}
