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
            'check_in_date'    => 'required|date|after_or_equal:today',
            'check_out_date'   => 'required|date|after:check_in_date',
            'num_guests'       => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:500',
            'payment_method'   => 'required|in:vnpay,momo',
        ];
    }

    public function messages(): array
    {
        return [
            'check_in_date.after_or_equal' => 'Ngày check-in phải từ hôm nay trở đi',
            'check_out_date.after'         => 'Ngày check-out phải sau ngày check-in',
            'homestay_id.exists'           => 'Homestay không tồn tại',
        ];
    }
}
