<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    public $timestamps = false;

    protected $fillable = ['ticket_id', 'sender_id', 'message'];

    protected $casts = ['created_at' => 'datetime'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
