<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CHECKED_IN = 'checked_in';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_META = [
        self::STATUS_PENDING => ['label' => 'Đang duyệt', 'color' => '#d99022'],
        self::STATUS_CONFIRMED => ['label' => 'Đã xác nhận', 'color' => '#169b62'],
        self::STATUS_CHECKED_IN => ['label' => 'Đang ở', 'color' => '#1d4ed8'],
        self::STATUS_COMPLETED => ['label' => 'Hoàn thành', 'color' => '#6366f1'],
        self::STATUS_CANCELLED => ['label' => 'Đã hủy', 'color' => '#d34f5f'],
    ];

    protected $fillable = [
        'user_id', 'homestay_id', 'check_in', 'check_out', 'num_guests',
        'total_amount', 'admin_earn', 'host_earn', 'status', 'cancel_status',
        'cancel_requested_at', 'host_approved'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_amount' => 'integer',
        'admin_earn' => 'integer',
        'host_earn' => 'integer',
        'cancel_requested_at' => 'datetime',
        'host_approved' => 'boolean',
    ];

    public function statusLabel(): string
    {
        return static::labelForStatus($this->status);
    }

    public static function labelForStatus(string $status): string
    {
        return static::STATUS_META[$status]['label'] ?? 'Không xác định';
    }

    public static function chartColorForStatus(string $status): string
    {
        return static::STATUS_META[$status]['color'] ?? '#94a3b8';
    }

    public function statusBadgeClass(): string
    {
        return static::badgeClassForStatus((string) $this->status);
    }

    public static function badgeClassForStatus(string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING => 'admin-badge-pending',
            self::STATUS_CONFIRMED => 'admin-badge-confirmed',
            self::STATUS_CHECKED_IN => 'admin-badge-ongoing',
            self::STATUS_COMPLETED => 'admin-badge-success',
            self::STATUS_CANCELLED => 'admin-badge-cancelled',
            default => 'admin-badge-secondary',
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function homestay(): BelongsTo
    {
        return $this->belongsTo(Homestay::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function getRefundPercentAttribute(): int
    {
        // Mặc định hoàn 100% nếu chưa có logic phức tạp hơn
        return 100;
    }

    public function getRefundAmountAttribute(): int
    {
        return (int) round($this->total_amount * ($this->refund_percent / 100));
    }
}
