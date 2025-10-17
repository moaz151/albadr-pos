<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

use \App\Enums\PaymentTypeEnum;
use Illuminate\Validation\Rule;


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
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'sale_date' => ['required', 'date'],
            'invoice_number' => ['required', 'unique:sales,invoice_number'],
            'safe_id' => ['required', 'exists:safes,id'],
            'discount_type' => ['required'],
            'discount' => ['nullable', 'numeric'],
            'payment_type' => ['required', Rule::enum(PaymentTypeEnum::class)],
            'payment_amount' => ['required_if:payment_type,'.PaymentTypeEnum::debt->value,'numeric'],
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:items,id'],
            'items.*.qty' => ['required', 'numeric', 'min:1'],
            'items.*.notes' => ['nullable', 'string'],
        ];
    }
}
