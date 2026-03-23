<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'homestay_id',
        'rating',
        'comment',
        'images',
        'admin_reply',
    ];

    protected $casts = ['images' => 'array'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function homestay()
    {
        return $this->belongsTo(Homestay::class);
    }
}
