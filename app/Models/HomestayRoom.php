<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomestayRoom extends Model
{
    use HasFactory;

    protected $fillable = ['homestay_id', 'feature_type', 'quantity'];

    const ROOM_TYPES = [
        'bedroom' => 'Phòng ngủ',
        'bathroom' => 'Phòng tắm',
        'kitchen' => 'Phòng bếp',
        'living_room' => 'Phòng khách',
        'pool' => 'Hồ bơi',
        'garden' => 'Sân vườn',
        'laundry' => 'Phòng giặt',
        'parking' => 'Bãi đỗ xe',
        'balcony' => 'Ban công',
    ];

    public function homestay(): BelongsTo
    {
        return $this->belongsTo(Homestay::class);
    }
}