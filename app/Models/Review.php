<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'reviewer_id',
        'homestay_id',
        'rating',
        'comment',
    ];

    public $timestamps = false;

    // Automatically manage created_at
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class , 'reviewer_id');
    }

    public function homestay()
    {
        return $this->belongsTo(Homestay::class);
    }
}
