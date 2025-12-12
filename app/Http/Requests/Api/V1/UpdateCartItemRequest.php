<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Settings\AdvancedSettings;

class UpdateCartItemRequest extends FormRequest
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
        $advancedSettings = app(AdvancedSettings::class);
        $quantityRules = ['required', 'numeric', 'min:0.01'];
        
        // If decimal quantities are not allowed, add integer validation
        if (!$advancedSettings->allow_decimal_quantities) {
            $quantityRules[] = 'integer';
        }

        return [
            'quantity' => $quantityRules,
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        $advancedSettings = app(AdvancedSettings::class);
        
        return [
            'quantity.required' => 'Quantity is required.',
            'quantity.numeric' => 'Quantity must be a number.',
            'quantity.min' => 'Quantity must be at least 0.01.',
            'quantity.integer' => $advancedSettings->allow_decimal_quantities 
                ? '' 
                : 'Quantities must be whole numbers only.',
        ];
    }
}

