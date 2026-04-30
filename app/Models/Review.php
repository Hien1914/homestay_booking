<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = ['booking_id', 'user_id', 'homestay_id', 'rating', 'comment'];
    protected $casts = ['rating' => 'integer'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function homestay(): BelongsTo
    {
        return $this->belongsTo(Homestay::class);
    }
}