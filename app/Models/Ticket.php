<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'booking_id',
        'assigned_to',
        'type',
        'title',
        'description',
        'status',
        'closed_at',
    ];

    protected $casts = ['closed_at' => 'datetime'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
}
