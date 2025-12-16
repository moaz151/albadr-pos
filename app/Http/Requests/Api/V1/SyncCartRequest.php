<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Settings\AdvancedSettings;

class SyncCartRequest extends FormRequest
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

        if (!$advancedSettings->allow_decimal_quantities) {
            $quantityRules[] = 'integer';
        }

        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.quantity' => $quantityRules,
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $advancedSettings = app(AdvancedSettings::class);

        return [
            'items.required' => 'Items array is required.',
            'items.array' => 'Items must be an array.',
            'items.min' => 'At least one item is required to sync the cart.',
            'items.*.item_id.required' => 'Each item must have an item_id.',
            'items.*.item_id.exists' => 'One or more selected items do not exist.',
            'items.*.quantity.required' => 'Each item must have a quantity.',
            'items.*.quantity.numeric' => 'Item quantities must be numeric values.',
            'items.*.quantity.min' => 'Item quantities must be at least 0.01.',
            'items.*.quantity.integer' => $advancedSettings->allow_decimal_quantities
                ? ''
                : 'Quantities must be whole numbers only.',
        ];
    }
}



