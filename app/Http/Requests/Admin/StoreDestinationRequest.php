<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:170', 'alpha_dash', 'unique:destinations,slug'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên điểm đến.',
            'slug.required' => 'Slug chưa được tạo. Vui lòng kiểm tra lại tên điểm đến.',
            'slug.alpha_dash' => 'Slug chỉ được chứa chữ thường không dấu, số và dấu gạch ngang.',
            'slug.unique' => 'Slug này đã tồn tại, vui lòng chọn tên khác.',
            'image.image' => 'Ảnh đại diện phải là tệp hình ảnh hợp lệ.',
            'image.max' => 'Ảnh đại diện không được vượt quá 5MB.',
        ];
    }
}
