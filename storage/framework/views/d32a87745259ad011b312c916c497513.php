<div class="booking-detail-popup" id="printableBookingDetail">
    <div class="row g-4">
        <div class="col-md-6">
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Thông tin cơ bản</h6>
            <div class="admin-detail-list">
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Mã booking</span>
                    <span class="admin-detail-value fw-bold">#<?php echo e($booking->id); ?></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Homestay</span>
                    <span class="admin-detail-value"><?php echo e($booking->homestay->title); ?></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Khách hàng</span>
                    <span class="admin-detail-value">
                        <div class="fw-bold"><?php echo e($booking->user->full_name); ?></div>
                        <div class="small text-muted"><?php echo e($booking->user->email); ?></div>
                    </span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Số khách</span>
                    <span class="admin-detail-value"><?php echo e($booking->num_guests); ?> người</span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Nhận phòng</span>
                    <span class="admin-detail-value"><?php echo e($booking->check_in->format('d/m/Y')); ?></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Trả phòng</span>
                    <span class="admin-detail-value"><?php echo e($booking->check_out->format('d/m/Y')); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-credit-card me-2"></i>Thanh toán & Trạng thái</h6>
            <div class="admin-detail-list">
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Tổng tiền</span>
                    <span class="admin-detail-value fw-bold text-success" style="font-size: 1.2rem;"><?php echo e(number_format($booking->total_amount)); ?>đ</span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Trạng thái</span>
                    <span class="admin-detail-value">
                        <span class="admin-badge <?php echo e($booking->statusBadgeClass()); ?>"><?php echo e($booking->statusLabel()); ?></span>
                    </span>
                </div>
                <?php if($booking->cancel_status != 'none' && $booking->cancel_status): ?>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Yêu cầu hủy</span>
                    <span class="admin-detail-value">
                        <?php if($booking->cancel_status == 'pending'): ?> <span class="text-warning fw-bold">Chờ duyệt</span>
                        <?php elseif($booking->cancel_status == 'approved'): ?> <span class="text-success fw-bold">Đã duyệt</span>
                        <?php else: ?> <span class="text-danger fw-bold">Từ chối</span> <?php endif; ?>
                    </span>
                </div>
                <?php endif; ?>
                <?php if($booking->payment): ?>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Thanh toán</span>
                    <span class="admin-detail-value">
                        <span class="admin-badge <?php echo e($booking->payment->displayStatusBadgeClass()); ?>"><?php echo e($booking->payment->displayStatusLabel()); ?></span>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if($booking->review): ?>
    <div class="mt-4 p-3 bg-light rounded-3 border">
        <h6 class="fw-bold mb-2"><i class="bi bi-star-fill text-warning me-2"></i>Đánh giá của khách</h6>
        <div class="mb-2">
            <?php for($i=1; $i<=5; $i++): ?>
                <i class="bi bi-star-fill <?php echo e($i <= $booking->review->rating ? 'text-warning' : 'text-muted'); ?>"></i>
            <?php endfor; ?>
            <span class="ms-2 fw-bold"><?php echo e($booking->review->rating); ?>/5</span>
        </div>
        <p class="mb-0 text-muted fst-italic">"<?php echo e($booking->review->comment); ?>"</p>
    </div>
    <?php endif; ?>

    <div class="mt-4 d-flex justify-content-between align-items-center">
        <div class="d-flex gap-2">
            <?php if($booking->cancel_status == 'pending'): ?>
                <a href="<?php echo e(route('host.bookings.cancel-approve', ['booking' => $booking, 'action' => 'approve'])); ?>" class="admin-create-btn" style="background-color: var(--admin-success);" onclick="return confirm('Duyệt yêu cầu hủy?')">Duyệt hủy</a>
                <a href="<?php echo e(route('host.bookings.cancel-approve', ['booking' => $booking, 'action' => 'reject'])); ?>" class="admin-filter-clear-btn" style="color: var(--admin-danger); border-color: var(--admin-danger);" onclick="return confirm('Từ chối yêu cầu hủy?')">Từ chối hủy</a>
            <?php endif; ?>
        </div>
        <button type="button" class="admin-filter-btn" onclick="window.printBooking()">
            <i class="bi bi-printer me-2"></i>In đơn hàng
        </button>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #printableBookingDetail, #printableBookingDetail * { visibility: visible; }
    #printableBookingDetail {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
    }
    .btn, .admin-filter-btn, .admin-create-btn, .admin-filter-clear-btn { display: none !important; }
}
</style>

<script>
window.printBooking = function() {
    window.print();
}
</script>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/bookings/show_partial.blade.php ENDPATH**/ ?>