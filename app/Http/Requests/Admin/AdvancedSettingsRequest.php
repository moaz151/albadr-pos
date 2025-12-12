<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\PaymentTypeEnum;

class AdvancedSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paymentTypeValues = array_map(fn($case) => $case->name, PaymentTypeEnum::cases());
        
        return [
            'allow_decimal_quantities' => ['sometimes', 'boolean'],
            'default_discount_method' => ['required', 'in:percentage,fixed_amount'],
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => ['required', 'string', Rule::in($paymentTypeValues)],
        ];
    }
}


