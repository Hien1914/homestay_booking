<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'booking_id', 'amount', 'payment_method',
        'payment_status', 'paid_at'
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Chờ duyệt',
            self::STATUS_SUCCESS => 'Đã xác nhận',
            self::STATUS_FAILED => 'Đã hủy',
        ];
    }

    public function statusLabel(): string
    {
        return self::statuses()[$this->payment_status] ?? $this->payment_status;
    }

    public function statusBadgeClass(): string
    {
        return match($this->payment_status) {
            self::STATUS_SUCCESS => 'admin-badge-success',
            self::STATUS_PENDING => 'admin-badge-warning',
            self::STATUS_FAILED => 'admin-badge-danger',
            default => 'admin-badge-secondary',
        };
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}