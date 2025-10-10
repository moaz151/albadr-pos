<?php

namespace App\Http\Requests\admin;

use App\Enums\ItemStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
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
            'name' => 'required|string|unique:items,name,' . $this->route('item'),
            'item_code' => 'nullable|unique:items,item_code,' . $this->route('item'),
            'quantity' => 'required|numeric|min:0|max:999999999.99',
            'price' => 'required|numeric|min:0|max:999999999.99',
            'category_id' => 'required|exists:categories,id', // integer
            'unit_id' => 'required|exists:units,id',         // integer
            'status'  => ['Required', Rule::enum(ItemStatusEnum::class)],
            // 'status'  => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'minimum_stock' => 'nullable|numeric|min:0|max:999999999.99',
        ];
    }
}
