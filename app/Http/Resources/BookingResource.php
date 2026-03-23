<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'booking_code'     => $this->booking_code,
            'check_in_date'    => $this->check_in_date->format('d/m/Y'),
            'check_out_date'   => $this->check_out_date->format('d/m/Y'),
            'num_nights'       => $this->num_nights,
            'num_guests'       => $this->num_guests,
            'price_per_night'  => $this->price_per_night,
            'service_fee'      => $this->service_fee,
            'total_amount'     => $this->total_amount,
            'status'           => $this->status,
            'payment_status'   => $this->payment_status,
            'special_requests' => $this->special_requests,
            'actual_check_in'  => $this->actual_check_in?->format('d/m/Y H:i'),
            'actual_check_out' => $this->actual_check_out?->format('d/m/Y H:i'),
            'homestay'         => $this->whenLoaded('homestay', fn() => [
                'id'   => $this->homestay->id,
                'name' => $this->homestay->name,
            ]),
            'created_at'       => $this->created_at->format('d/m/Y H:i'),
        ];
    }
}
