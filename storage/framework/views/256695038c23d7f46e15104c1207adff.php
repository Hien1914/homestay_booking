
<?php $__env->startSection('title', 'Lịch sử đặt phòng'); ?>
<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/clients/profile.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/clients/booking-history.css')); ?>">

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

      <div class="alert alert-warning mb-4 rounded-4" style="border: 1px solid #fcd34d; background-color: #fffbeb; color: #b45309; padding: 16px; font-size: 0.95rem;">
        <i class="fa-solid fa-circle-exclamation me-2 fs-5 align-middle"></i> 
        <strong>Lưu ý quan trọng:</strong> Quý khách <b>BẮT BUỘC 100%</b> phải thực hiện thao tác <strong style="color: #0d6efd;"><i class="fa-solid fa-key"></i> Nhận phòng</strong> và <strong style="color: #6366f1;"><i class="fa-solid fa-door-closed"></i> Trả phòng</strong> trực tiếp trên hệ thống tại trang Lịch sử đặt phòng này ngay khi đến nhận và rời khỏi chỗ nghỉ. Việc này là bắt buộc để hệ thống ghi nhận chính xác thời gian lưu trú, đảm bảo quyền lợi của quý khách và tuân thủ các chính sách của NestAway.
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
                            <span class="admin-badge <?php echo e($booking->statusBadgeClass()); ?>"><?php echo e($booking->statusLabel()); ?></span>
                        </td>
                        <td class="text-center">
                            <?php if($booking->payment && $booking->payment->payment_status === \App\Models\Payment::STATUS_PENDING && !$booking->payment->paid_at && in_array($booking->status, [\App\Models\Booking::STATUS_PENDING, \App\Models\Booking::STATUS_CONFIRMED])): ?>
                                <a href="<?php echo e(route('payment.show', ['booking' => $booking->id])); ?>" class="booking-history-pay-link">
                                    <i class="bi bi-credit-card-2-front-fill me-1"></i> Thanh toán ngay
                                </a>
                            <?php elseif($booking->payment): ?>
                                <span class="admin-badge <?php echo e($booking->payment->displayStatusBadgeClass()); ?>"><?php echo e($booking->payment->displayStatusLabel()); ?></span>
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