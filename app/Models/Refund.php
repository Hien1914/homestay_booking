<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'amount', 'status'];
    protected $casts = ['amount' => 'integer'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}