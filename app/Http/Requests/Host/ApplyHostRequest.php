<?php

namespace App\Http\Requests\Host;

use Illuminate\Foundation\Http\FormRequest;

class ApplyHostRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Chỉ user đã đăng nhập và chưa phải host
        return auth()->check() && auth()->user()->role === 'user';
    }

    public function rules(): array
    {
        return [
            'id_card'     => 'required|string|max:50',
            'bank_acc'    => 'required|string|max:50',
            'bank_name'   => 'required|string|max:100',
            'bank_holder' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'id_card.required'     => 'Vui lòng nhập số CMND/CCCD',
            'bank_acc.required'    => 'Vui lòng nhập số tài khoản ngân hàng',
            'bank_name.required'   => 'Vui lòng nhập tên ngân hàng',
            'bank_holder.required' => 'Vui lòng nhập tên chủ tài khoản',
        ];
    }
}
