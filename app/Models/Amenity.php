<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_by', 'is_approved'];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function homestays(): BelongsToMany
    {
        return $this->belongsToMany(Homestay::class, 'homestay_amenities')
                    ->withPivot('quantity');
    }
}