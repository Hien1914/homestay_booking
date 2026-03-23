<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'        => 'required|in:homestay_issue,payment_issue,account_issue,other',
            'title'       => 'required|string|min:5|max:200',
            'description' => 'required|string|min:10',
            'booking_id'  => 'nullable|integer|exists:bookings,id',
        ];
    }
}
