<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code','user_id','homestay_id',
        'check_in_date','check_out_date','num_nights','num_guests',
        'price_per_night','service_fee','total_amount',
        'status','payment_status','special_requests','cancellation_reason',
        'actual_check_in','actual_check_out',
    ];

    protected $casts = [
        'check_in_date'    => 'date',
        'check_out_date'   => 'date',
        'actual_check_in'  => 'datetime',
        'actual_check_out' => 'datetime',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function homestay() { return $this->belongsTo(Homestay::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function review()   { return $this->hasOne(Review::class); }
    public function ticket()   { return $this->hasOne(Ticket::class); }

    public static function generateCode(): string
    {
        do {
            $code = 'HS-' . date('Y') . '-' . strtoupper(Str::random(5));
        } while (self::where('booking_code', $code)->exists());
        return $code;
    }

    public function canReview(): bool
    {
        return $this->status === 'completed'
            && ! $this->review()->exists()
            && $this->actual_check_out?->diffInDays(now()) <= 14;
    }
}