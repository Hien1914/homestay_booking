<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'type'        => $this->type,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            'booking_id'  => $this->booking_id,
            'created_at'  => $this->created_at->format('d/m/Y H:i'),
        ];
    }
}
