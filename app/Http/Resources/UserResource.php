<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'avatar'         => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'role'           => $this->role,
            'email_verified' => ! is_null($this->email_verified_at),
            'created_at'     => $this->created_at->format('d/m/Y'),
        ];
    }
}
