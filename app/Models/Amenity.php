<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    public $timestamps = false;

    // Automatically manage created_at
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function homestays()
    {
        return $this->belongsToMany(Homestay::class , 'homestay_amenities', 'amenity_id', 'homestay_id');
    }
}
