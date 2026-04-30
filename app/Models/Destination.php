<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'description', 'homestay_count', 'is_approved', 'created_by'];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($destination) {
            if (empty($destination->slug)) {
                $destination->slug = Str::slug($destination->name);
            }
        });
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function homestays(): HasMany
    {
        return $this->hasMany(Homestay::class);
    }
}