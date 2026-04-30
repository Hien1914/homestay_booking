<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomestayImage extends Model
{
    use HasFactory;

    protected $fillable = ['homestay_id', 'image_url', 'is_primary'];
    protected $casts = ['is_primary' => 'boolean'];

    public function homestay(): BelongsTo
    {
        return $this->belongsTo(Homestay::class);
    }
}