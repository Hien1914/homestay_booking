<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Homestay extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id', 'destination_id', 'promotion_id', 'title', 'slug', 'description',
        'address', 'ward', 'province', 'price_per_night', 'max_guests',
        'status', 'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'price_per_night' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($homestay) {
            if (empty($homestay->slug)) {
                $homestay->slug = Str::slug($homestay->title);
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'homestay_amenities')
                    ->withPivot('quantity');
    }

    public function images(): HasMany
    {
        return $this->hasMany(HomestayImage::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(HomestayRoom::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function getActivePromotionAttribute()
    {
        $promotion = $this->promotion;
        if ($promotion && $promotion->is_active) {
            $now = now();
            $start = \Carbon\Carbon::parse($promotion->start_date)->startOfDay();
            $end = \Carbon\Carbon::parse($promotion->end_date)->endOfDay();
            
            if ($now->between($start, $end)) {
                return $promotion;
            }
        }
        return null;
    }

    public function getDiscountedPriceAttribute()
    {
        $promotion = $this->active_promotion;
        if (!$promotion) {
            return $this->price_per_night;
        }
        return (int) round($this->price_per_night * (1 - $promotion->discount_percent / 100));
    }

    public function getRoomsArrayAttribute()
    {
        return $this->rooms->pluck('quantity', 'feature_type')->toArray();
    }
}