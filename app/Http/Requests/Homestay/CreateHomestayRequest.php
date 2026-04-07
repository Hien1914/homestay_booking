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
            'title'               => 'required|string|min:5|max:200',
            'type'                => 'required|string|max:100',
            'description'         => 'required|string|min:100',
            'address'             => 'required|string|max:255',
            'province'            => 'required|string|max:100',
            'ward'                => 'required|string|max:100',
            'price_per_night'     => 'required|numeric|min:0',
            'max_guests'          => 'required|integer|min:1|max:50',
            'cover_image'         => 'required|image|max:5120',
            'room_images'         => 'nullable|array',
            'room_images.*'       => 'image|max:5120',
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'integer|exists:amenities,id',
        ];
    }

    public function messages(): array
    {
        return [
            'price_per_night.min' => 'Giá tối thiểu là 0 VND/đêm',
            'title.min'           => 'Tên homestay phải có ít nhất 5 ký tự',
            'title.required'      => 'Vui lòng nhập tiêu đề',
            'description.min'     => 'Mô tả phải có ít nhất 100 ký tự',
            'description.required' => 'Vui lòng nhập mô tả',
            'cover_image.required' => 'Vui lòng tải lên ảnh đại diện',
            'cover_image.max'     => 'Ảnh không vượt quá 5MB',
            'room_images.*.max'   => 'Mỗi ảnh không vượt quá 5MB',
        ];
    }
}
