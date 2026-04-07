<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Homestay extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_code',
        'title',
        'slug',
        'type',
        'description',
        'address',
        'province',
        'ward',
        'destination_id',
        'price_per_night',
        'max_guests',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($homestay) {
            if (empty($homestay->room_code)) {
                $homestay->room_code = self::generateRoomCode();
            }
            if (empty($homestay->slug)) {
                $homestay->slug = self::generateSlug($homestay->title);
            }
        });

        static::updating(function ($homestay) {
            if ($homestay->isDirty('title')) {
                $homestay->slug = self::generateSlug($homestay->title);
            }
        });
    }

    public static function generateRoomCode(): string
    {
        do {
            $code = 'HT' . now()->format('ymd') . strtoupper(Str::random(4));
        } while (self::where('room_code', $code)->exists());

        return $code;
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'homestay_amenities', 'homestay_id', 'amenity_id');
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->hasMany(HomestayImage::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function isPublished()
    {
        return $this->status === 'published';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalBookingsAttribute()
    {
        return $this->bookings()->count();
    }
}
