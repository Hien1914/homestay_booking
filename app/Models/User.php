<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name', 'email', 'password', 'phone', 'gender', 'birthday',
        'role', 'auth_provider', 'google_id', 'avatar_url', 'bank_name', 'bank_account_number'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'birthday' => 'date',
    ];

    public function homestays(): HasMany
    {
        return $this->hasMany(Homestay::class, 'owner_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function favoriteHomestays(): BelongsToMany
    {
        return $this->belongsToMany(Homestay::class, 'favorites')->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function hostApplication(): HasMany
    {
        return $this->hasMany(HostApplication::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class, 'host_id');
    }

    public function createdAmenities(): HasMany
    {
        return $this->hasMany(Amenity::class, 'created_by');
    }

    public function createdDestinations(): HasMany
    {
        return $this->hasMany(Destination::class, 'created_by');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'host_id');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    /* ── Role helpers ── */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHost(): bool
    {
        return $this->role === 'host';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function getAgeAttribute()
    {
        return $this->birthday ? $this->birthday->age : null;
    }
}
