<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\UserStatusEnum;

class UserRequest extends FormRequest
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
            'username' => 'required|string|max:255|unique:users,username,' . $this->route('user'),
            'email' => 'nullable|email|max:255|unique:users,email,' . $this->route('user'),
            'password' => ($this->isMethod('post') ? 'required|' : 'nullable|') . 'string|min:6|confirmed',
            'full_name' => 'required|string|max:255',
            'status' => ['required', Rule::enum(UserStatusEnum::class)],
            'roles' => 'required|array',
            'roles.*' => 'required|exists:roles,id',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'required|exists:permissions,id',
        ];
    }
}
