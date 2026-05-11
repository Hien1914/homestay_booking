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
            self::STATUS_PENDING => 'Đang chờ thanh toán',
            self::STATUS_SUCCESS => 'Đã thanh toán',
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
            self::STATUS_SUCCESS => 'admin-badge-confirmed',
            self::STATUS_PENDING => 'admin-badge-pending',
            self::STATUS_FAILED => 'admin-badge-cancelled',
            default => 'admin-badge-secondary',
        };
    }

    public function isPendingApproval(): bool
    {
        return $this->payment_status === self::STATUS_SUCCESS;
    }

    public function displayStatusLabel(): string
    {
        return $this->statusLabel();
    }

    public function displayStatusBadgeClass(): string
    {
        if ($this->payment_status === self::STATUS_PENDING && $this->paid_at === null) {
            return 'admin-badge-pending';
        }

        return $this->statusBadgeClass();
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
