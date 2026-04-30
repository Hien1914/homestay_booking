<?php

namespace App\Http\Requests\Host;

use Illuminate\Foundation\Http\FormRequest;

class PayoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'host';
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|integer|min:100000',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Vui lòng nhập số tiền muốn rút',
            'amount.integer'  => 'Số tiền phải là số nguyên',
            'amount.min'      => 'Số tiền tối thiểu là 100.000 VND',
        ];
    }
}
