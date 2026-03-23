<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomestayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'description'         => $this->description,
            'address'             => $this->address,
            'province'            => $this->province,
            'price_per_night'     => $this->price_per_night,
            'max_guests'          => $this->max_guests,
            'num_bedrooms'        => $this->num_bedrooms,
            'num_beds'            => $this->num_beds,
            'num_bathrooms'       => $this->num_bathrooms,
            'check_in_time'       => $this->check_in_time,
            'check_out_time'      => $this->check_out_time,
            'amenities'           => $this->amenities ?? [],
            'images'              => $this->images ?? [],
            'cancellation_policy' => $this->cancellation_policy,
            'status'              => $this->status,
            'avg_rating'          => $this->avg_rating,
            'created_at'          => $this->created_at->format('d/m/Y'),
        ];
    }
}
