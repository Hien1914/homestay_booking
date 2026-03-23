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
            'replies'     => $this->whenLoaded(
                'replies',
                fn() =>
                $this->replies->map(fn($r) => [
                    'id'         => $r->id,
                    'message'    => $r->message,
                    'sender'     => ['id' => $r->sender->id, 'name' => $r->sender->name, 'role' => $r->sender->role],
                    'created_at' => $r->created_at->format('d/m/Y H:i'),
                ])
            ),
            'created_at'  => $this->created_at->format('d/m/Y H:i'),
        ];
    }
}
