<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

use App\Enums\UnitStatusEnum;
use Illuminate\Validation\Rule;

class UnitRequest extends FormRequest
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
            'name' => 'required|string|unique:units,name,' . $this->route('unit'),
            'status' => ['required', Rule::enum(UnitStatusEnum::class)]
        ];
    }
}
