<?php $__env->startSection('title', 'Lịch sử đặt phòng'); ?>
<?php $__env->startSection('content'); ?>
<style>
  @import url('<?php echo e(asset('css/clients/profile.css')); ?>');

  .booking-history-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.025em;
  }

  .booking-history-table th,
  .booking-history-table td {
    vertical-align: middle;
    padding: 16px 14px;
    border-bottom: 1px solid #f1f5f9;
  }

  .admin-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
  }

  .admin-badge-warning { background: #fef3c7; color: #92400e; }
  .admin-badge-success { background: #dcfce7; color: #166534; }
  .admin-badge-info { background: #e0f2fe; color: #075985; }
  .admin-badge-primary { background: #dbeafe; color: #1e40af; }
  .admin-badge-danger { background: #fee2e2; color: #991b1b; }
  .admin-badge-secondary { background: #f1f5f9; color: #475569; }

  .booking-history-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .booking-history-icon-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #475569;
    transition: all 0.2s ease;
  }

  .booking-history-icon-btn:hover {
    background: #f8fafc;
    color: #1e293b;
    border-color: #cbd5e1;
  }

  .booking-history-icon-btn.is-danger {
    color: #ef4444;
  }

  .booking-history-icon-btn.is-danger:hover {
    background: #fef2f2;
    border-color: #fecaca;
  }

  .booking-history-pay-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 8px;
    background: #166534;
    color: #fff;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
  }

  .booking-history-pay-link:hover {
    background: #14532d;
    color: #fff;
    transform: translateY(-1px);
  }
</style>

<section class="profile-page section-py" style="min-height: 60vh;">
  <div class="container-setting my-4">
    <div class="profile-detail-card" style="width: 100%;">
      <div class="profile-card-head" style="margin-bottom: 24px;">
        <div>
          <p class="profile-eyebrow">Quản lý đặt phòng</p>
          <h2 class="profile-card-title">Lịch sử đặt phòng của tôi</h2>
        </div>
        <button type="button" class="profile-ghost-btn" onclick="window.location.reload()">
          <i class="fa-solid fa-rotate-right"></i>
          Tải lại
        </button>
      </div>

      <div class="table-responsive">
        <table class="table booking-history-table">
            <thead>
                <tr>
                    <th class="text-center">Mã đơn</th>
                    <th>Chỗ nghỉ</th>
                    <th class="text-center">Ngày nhận/trả</th>
                    <th class="text-center">Tổng tiền</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thanh toán</th>

                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-center"><strong>#<?php echo e($booking->id); ?></strong></td>
                        <td>
                            <a href="<?php echo e(route('homestay.show', $booking->homestay->slug)); ?>" class="text-decoration-none text-dark fw-bold">
                                <?php echo e(Str::limit($booking->homestay->title, 35)); ?>

                            </a>
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-column align-items-center">
                                <span><?php echo e(\Carbon\Carbon::parse($booking->check_in)->format('d/m/Y')); ?></span>
                                <small class="text-muted">đến <?php echo e(\Carbon\Carbon::parse($booking->check_out)->format('d/m/Y')); ?></small>
                            </div>
                        </td>
                        <td class="text-center"><strong><?php echo e(number_format($booking->total_amount, 0, ',', '.')); ?> đ</strong></td>
                        <td class="text-center">
                            <?php
                                $statusClass = match($booking->status) {
                                    \App\Models\Booking::STATUS_PENDING => 'admin-badge-warning',
                                    \App\Models\Booking::STATUS_CONFIRMED => 'admin-badge-success',
                                    \App\Models\Booking::STATUS_CHECKED_IN => 'admin-badge-info',
                                    \App\Models\Booking::STATUS_COMPLETED => 'admin-badge-primary',
                                    \App\Models\Booking::STATUS_CANCELLED => 'admin-badge-danger',
                                    default => 'admin-badge-secondary',
                                };
                            ?>
                            <span class="admin-badge <?php echo e($statusClass); ?>"><?php echo e($booking->statusLabel()); ?></span>
                        </td>
                        <td class="text-center">
                            <?php if($booking->payment && $booking->payment->payment_status === \App\Models\Payment::STATUS_SUCCESS): ?>
                                <span class="text-success fw-bold" style="font-size: 0.85rem;"><i class="bi bi-check-circle-fill me-1"></i> Đã thanh toán</span>
                            <?php elseif($booking->payment && $booking->payment->paid_at): ?>
                                <span class="admin-badge admin-badge-info">Đã thanh toán</span>
                            <?php elseif($booking->status === \App\Models\Booking::STATUS_PENDING || $booking->status === \App\Models\Booking::STATUS_CONFIRMED): ?>
                                <a href="<?php echo e(route('payment.show', ['booking' => $booking->id])); ?>" class="booking-history-pay-link">
                                    <i class="bi bi-credit-card-2-front-fill me-1"></i> Thanh toán ngay
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <div class="booking-history-actions">
                                <a href="<?php echo e(route('homestay.show', $booking->homestay->slug)); ?>" class="booking-history-icon-btn" title="Xem phòng">
                                    <i class="fa-solid fa-eye"></i>
                                </a>



                                <?php if(in_array($booking->status, [\App\Models\Booking::STATUS_PENDING, \App\Models\Booking::STATUS_CONFIRMED])): ?>
                                    <?php
                                        $checkIn = \Carbon\Carbon::parse($booking->check_in)->startOfDay();
                                        $daysUntil = now()->diffInDays($checkIn, false);
                                    ?>
                                    <?php if($daysUntil > 0): ?>
                                        <form action="<?php echo e(route('bookings.cancel', $booking->id)); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đặt phòng này?');">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="booking-history-icon-btn is-danger" title="Hủy phòng">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if($booking->status === \App\Models\Booking::STATUS_CONFIRMED): ?>
                                    <form action="<?php echo e(route('bookings.checkin', $booking->id)); ?>" method="POST" onsubmit="return confirm('Bạn muốn nhận phòng ngay bây giờ?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <button type="submit" class="booking-history-icon-btn" title="Nhận phòng" style="color: #0d6efd; border-color: #0d6efd;">
                                            <i class="fa-solid fa-key"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php if($booking->status === \App\Models\Booking::STATUS_CHECKED_IN): ?>
                                    <form action="<?php echo e(route('bookings.checkout', $booking->id)); ?>" method="POST" onsubmit="return confirm('Bạn muốn trả phòng ngay bây giờ?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <button type="submit" class="booking-history-icon-btn" title="Trả phòng" style="color: #6366f1; border-color: #6366f1;">
                                            <i class="fa-solid fa-door-closed"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php if($booking->status === \App\Models\Booking::STATUS_COMPLETED): ?>
                                    <a href="<?php echo e(route('homestay.show', $booking->homestay->slug)); ?>#danh-gia" class="booking-history-icon-btn" title="Đánh giá">
                                        <i class="fa-regular fa-star"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-calendar2-x mb-2" style="font-size: 2rem;"></i>
                                <span>Chưa có lịch sử đặt phòng nào.</span>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($bookings->links()); ?>

        </div>
      </div>

    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/booking-history.blade.php ENDPATH**/ ?>