<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'google_id',
        'phone',
        'password',
        'avatar',
        'role',
        'is_active',
        'id_card_number',
        'id_card_front',
        'id_card_back',
        'is_id_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'id_card_number',
        'id_card_front',
        'id_card_back',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
        'is_id_verified'    => 'boolean',
        'password'          => 'hashed',
    ];

    public function homestays()
    {
        return $this->hasMany(Homestay::class, 'admin_id');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'sender_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
