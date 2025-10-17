<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Enums\ClientStatusEnum;
use App\Enums\ClientregistrationEnum;

class ClientRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:clients,name,' . $this->route('client'),
            'email' => 'required|email|unique:clients,email,' . $this->route('client'),
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'balance' => 'nullable|numeric|min:0',
            'status' => ['required', Rule::enum(ClientStatusEnum::class)],
            'registered_via' => ['required', Rule::enum(ClientregistrationEnum::class)]
        ];
    }
}
