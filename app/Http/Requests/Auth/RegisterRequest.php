<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|min:2|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|regex:/^[0-9]{10,11}$/|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge(['phone' => preg_replace('/\s+/', '', $this->phone)]);
        }
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);
        
        // Map to database column names
        return [
            'full_name'     => $data['name'],
            'email'         => $data['email'],
            'phone'         => $data['phone'],
            'password_hash' => bcrypt($data['password']),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Vui lòng nhập họ tên',
            'email.required'     => 'Vui lòng nhập email',
            'email.unique'       => 'Email này đã được đăng ký',
            'phone.required'     => 'Vui lòng nhập số điện thoại',
            'phone.regex'        => 'Số điện thoại không hợp lệ',
            'phone.unique'       => 'Số điện thoại này đã được đăng ký',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ];
    }
}
