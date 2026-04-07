<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'homestay_id',
        'message',
        'status',
        'host_reply',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public $timestamps = false;

    // Automatically manage created_at
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function guest()
    {
        return $this->belongsTo(User::class , 'guest_id');
    }

    public function homestay()
    {
        return $this->belongsTo(Homestay::class);
    }
}
