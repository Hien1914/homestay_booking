<?php

namespace App\Http\Requests\Host;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class PromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'host';
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'discount_percent' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $startDate = $this->input('start_date');

                    if (!$startDate) {
                        return;
                    }

                    $minEndDate = Carbon::parse($startDate)->addDay()->startOfDay();

                    if (Carbon::parse($value)->lt($minEndDate)) {
                        $fail('Ngày kết thúc phải sau ngày bắt đầu ít nhất 1 ngày');
                    }
                },
            ],
            'min_nights' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề khuyến mãi',
            'discount_percent.required' => 'Vui lòng nhập phần trăm giảm giá',
            'discount_percent.min' => 'Giảm giá phải từ 1%',
            'discount_percent.max' => 'Giảm giá tối đa 100%',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu ít nhất 1 ngày',
        ];
    }
}
