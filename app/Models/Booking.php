<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'homestay_id',
        'check_in',
        'check_out',
        'num_guests',
        'total_amount',
        'promotion_id',
        'status',
        'special_requests',
        'payment_method',
        'payment_status',
        'transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'paid_at' => 'datetime',
    ];

    public function guest()
    {
        return $this->belongsTo(User::class , 'guest_id');
    }

    public function homestay()
    {
        return $this->belongsTo(Homestay::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
