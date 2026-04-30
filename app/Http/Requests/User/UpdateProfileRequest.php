<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'sometimes|string|min:2|max:100',
            'phone' => 'sometimes|nullable|string|max:15|unique:users,phone,' . auth()->id(),
        ];
    }

    public function messages(): array
    {
        return ['phone.unique' => 'Số điện thoại này đã được dùng bởi tài khoản khác'];
    }
}
