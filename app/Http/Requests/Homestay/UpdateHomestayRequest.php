<?php

namespace App\Http\Requests\Homestay;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomestayRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        if (!$user || !$user->isHost()) {
            return false;
        }
        $homestay = $this->route('homestay');
        return $homestay && $homestay->owner_id === $user->id;
    }

    public function rules(): array
    {
        return [
            'title'               => 'required|string|min:5|max:200',
            'description'         => 'required|string|min:50',
            'destination_id'      => 'nullable|exists:destinations,id',
            'address'             => 'required|string|max:255',
            'province'            => 'required|string|max:100',
            'ward'                => 'nullable|string|max:100',
            'price_per_night'     => 'required|integer|min:0',
            'max_guests'          => 'required|integer|min:1|max:50',
            'cover_image'         => 'nullable|image|mimes:jpeg,png,webp|max:5120',
            'room_images'         => 'nullable|array',
            'room_images.*'       => 'image|mimes:jpeg,png,webp|max:5120',
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'integer|exists:amenities,id',
            'amenity_quantities'  => 'nullable|array',
            'rooms'               => 'nullable|array',
            'delete_images'       => 'nullable|array',
            'delete_images.*'     => 'integer|exists:homestay_images,id',
        ];
    }

    public function messages(): array
    {
        return [
            'price_per_night.min' => 'Giá tối thiểu là 0 VND/đêm',
            'title.min'           => 'Tên homestay phải có ít nhất 5 ký tự',
            'title.required'      => 'Vui lòng nhập tiêu đề',
            'description.min'     => 'Mô tả phải có ít nhất 50 ký tự',
            'description.required' => 'Vui lòng nhập mô tả',
            'cover_image.max'     => 'Ảnh không vượt quá 5MB',
            'room_images.*.max'   => 'Mỗi ảnh không vượt quá 5MB',
        ];
    }
}
