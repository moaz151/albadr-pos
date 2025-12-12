<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\PaymentTypeEnum;
use App\Settings\AdvancedSettings;

class SaleRequest extends FormRequest
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
        $quantityRules = ['required', 'numeric'];
        
        // If decimal quantities are not allowed, add integer validation
        if (!$advancedSettings->allow_decimal_quantities) {
            $quantityRules = ['required', 'numeric','min:1','integer'];
        }

        return [
            'client_id' => ['required', 'exists:clients,id'],
            'sale_date' => ['required', 'date'],
            'invoice_number' => ['required', 'unique:sales,invoice_number'],
            'safe_id' => ['required', 'exists:safes,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'discount_type' => ['required'],
            'discount_value' => ['nullable', 'numeric'],
            'payment_type' => ['required', Rule::enum(PaymentTypeEnum::class)],
            'payment_amount' => ['required_if:payment_type,'.PaymentTypeEnum::debit->value,'numeric'],
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:items,id'],
            'items.*.qty' => $quantityRules,
            'items.*.notes' => ['nullable', 'string'],
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
            'items.*.qty.integer' => $advancedSettings->allow_decimal_quantities 
                ? '' 
                : 'Quantities must be whole numbers only.',
        ];
    }
}
