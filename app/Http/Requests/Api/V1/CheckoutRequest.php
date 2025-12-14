<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Settings\AdvancedSettings;
use App\Enums\PaymentTypeEnum;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
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
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_address' => ['required', 'string'],
            'shipping_phone' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'in:' . PaymentTypeEnum::cash->value],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'shipping_name.required' => 'Shipping name is required.',
            'shipping_address.required' => 'Shipping address is required.',
            'shipping_phone.required' => 'Shipping phone is required.',
        ];
    }
}

