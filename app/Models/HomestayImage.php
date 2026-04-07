<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomestayImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'homestay_id',
        'image_url',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public $timestamps = false;

    // Automatically manage created_at
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function homestay()
    {
        return $this->belongsTo(Homestay::class);
    }
}
