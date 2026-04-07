<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'homestay_id',
        'title',
        'discount_percent',
        'discount_amount',
        'start_date',
        'end_date',
        'min_nights',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function homestay()
    {
        return $this->belongsTo(Homestay::class);
    }
}
