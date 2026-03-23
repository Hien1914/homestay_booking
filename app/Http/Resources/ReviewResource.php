<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'rating'      => $this->rating,
            'comment'     => $this->comment,
            'images'      => $this->images ?? [],
            'admin_reply' => $this->admin_reply,
            'reviewer'    => $this->whenLoaded('user', fn() => [
                'id'     => $this->user->id,
                'name'   => $this->user->name,
                'avatar' => $this->user->avatar ? asset('storage/' . $this->user->avatar) : null,
            ]),
            'created_at'  => $this->created_at->format('d/m/Y'),
        ];
    }
}
