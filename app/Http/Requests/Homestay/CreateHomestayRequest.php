<?php

namespace App\Http\Requests\Homestay;

use Illuminate\Foundation\Http\FormRequest;

class CreateHomestayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                => 'required|string|min:5|max:200',
            'description'         => 'required|string|min:20',
            'address'             => 'required|string',
            'province'            => 'required|string|max:100',
            'price_per_night'     => 'required|numeric|min:50000',
            'max_guests'          => 'required|integer|min:1|max:50',
            'num_bedrooms'        => 'required|integer|min:0',
            'num_beds'            => 'required|integer|min:1',
            'num_bathrooms'       => 'required|integer|min:1',
            'check_in_time'       => 'required|date_format:H:i',
            'check_out_time'      => 'required|date_format:H:i',
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'string',
            'cancellation_policy' => 'required|in:flexible,moderate,strict',
        ];
    }

    public function messages(): array
    {
        return [
            'price_per_night.min' => 'Giá tối thiểu là 50,000 VND/đêm',
            'name.min'            => 'Tên homestay phải có ít nhất 5 ký tự',
            'description.min'     => 'Mô tả phải có ít nhất 20 ký tự',
        ];
    }
}
