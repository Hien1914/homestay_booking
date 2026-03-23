<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class CreateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => 'required|integer|exists:bookings,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|min:10|max:2000',
            'images'     => 'nullable|array|max:5',
            'images.*'   => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'rating.min'    => 'Điểm đánh giá tối thiểu là 1',
            'rating.max'    => 'Điểm đánh giá tối đa là 5',
            'comment.min'   => 'Nội dung đánh giá phải có ít nhất 10 ký tự',
        ];
    }
}
