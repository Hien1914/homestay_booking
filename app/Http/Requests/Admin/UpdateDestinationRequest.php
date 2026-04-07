<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $destinationId = $this->route('destination')?->id;

        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'required',
                'string',
                'max:170',
                'alpha_dash',
                Rule::unique('destinations', 'slug')->ignore($destinationId),
            ],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên điểm đến.',
            'slug.required' => 'Vui lòng nhập slug cho điểm đến.',
            'slug.alpha_dash' => 'Slug chỉ được chứa chữ thường không dấu, số và dấu gạch ngang.',
            'slug.unique' => 'Slug này đã tồn tại, vui lòng chọn slug khác.',
            'image.image' => 'Ảnh đại diện phải là tệp hình ảnh hợp lệ.',
            'image.max' => 'Ảnh đại diện không được vượt quá 5MB.',
        ];
    }
}
