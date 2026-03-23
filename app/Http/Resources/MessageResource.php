<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'sender_type' => $this->sender_type,
            'sender'      => $this->sender_type !== 'bot'
                ? ['id' => $this->sender->id, 'name' => $this->sender->name]
                : ['name' => 'Bot hỗ trợ'],
            'content'     => $this->content,
            'is_read'     => $this->is_read,
            'created_at'  => $this->created_at->format('H:i d/m/Y'),
        ];
    }
}
