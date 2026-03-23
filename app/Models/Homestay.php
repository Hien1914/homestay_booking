<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Homestay extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id','name','description','address','province',
        'price_per_night','max_guests','num_bedrooms','num_beds','num_bathrooms',
        'check_in_time','check_out_time','amenities','images',
        'cancellation_policy','status','avg_rating',
    ];

    protected $casts = [
        'amenities'       => 'array',
        'images'          => 'array',
        'price_per_night' => 'decimal:2',
        'avg_rating'      => 'decimal:2',
    ];

    public function admin()         { return $this->belongsTo(User::class, 'admin_id'); }
    public function bookings()      { return $this->hasMany(Booking::class); }
    public function reviews()       { return $this->hasMany(Review::class); }
    public function wishlists()     { return $this->hasMany(Wishlist::class); }
    public function faqs()          { return $this->hasMany(Faq::class); }
    public function conversations() { return $this->hasMany(Conversation::class); }

    public function isAvailable(string $checkIn, string $checkOut): bool
    {
        return ! $this->bookings()
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->where('check_in_date', '<', $checkOut)
            ->where('check_out_date', '>', $checkIn)
            ->exists();
    }

    public function updateAvgRating(): void
    {
        $avg = $this->reviews()->avg('rating');
        $this->update(['avg_rating' => $avg ? round($avg, 2) : null]);
    }
}
