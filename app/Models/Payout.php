<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = ['host_id', 'amount', 'status'];
    protected $casts = ['amount' => 'integer'];

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }
}