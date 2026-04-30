<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'homestay_id'      => 'required|integer|exists:homestays,id',
            'check_in'         => 'required|date|after_or_equal:today',
            'check_out'        => 'required|date|after:check_in',
            'num_guests'       => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'check_in.after_or_equal'      => 'Ngày check-in phải từ hôm nay trở đi',
            'check_out.after'             => 'Ngày check-out phải sau ngày check-in',
            'homestay_id.exists'           => 'Homestay không tồn tại',
        ];
    }
}
