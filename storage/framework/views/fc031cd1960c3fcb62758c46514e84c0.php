<?php $__env->startSection('title', 'Chi tiết thanh toán'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Xem thông tin thanh toán và đơn đặt phòng liên quan theo luồng quản lý đã được tối giản.</p>
    </div>
    <div class="admin-page-actions">
        <a href="<?php echo e(route('admin.payments')); ?>" class="admin-btn admin-btn-secondary">
            Quay lại
        </a>
    </div>
</div>

<div class="admin-grid admin-grid-2">
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Thông tin thanh toán</h3>
        </div>
        <div class="admin-card-body">
            <?php if($payment->payment_status === \App\Models\Payment::STATUS_PENDING && $payment->paid_at && $payment->booking): ?>
                <form action="<?php echo e(route('admin.payments.confirm', $payment->booking->id)); ?>" method="POST" class="mb-4">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <button type="submit" class="admin-btn admin-btn-primary" onclick="return confirm('Xác nhận admin đã nhận được khoản chuyển này?');">
                        Xác nhận đã nhận tiền
                    </button>
                </form>
            <?php endif; ?>
            <div class="admin-detail-list">
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Mã thanh toán</span>
                    <span class="admin-detail-value"><?php echo e($payment->id); ?></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Mã đơn hàng</span>
                    <span class="admin-detail-value admin-code-text">#<?php echo e($payment->booking?->id ?? '-'); ?></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Số tiền</span>
                    <span class="admin-detail-value"><?php echo e(number_format((float) $payment->amount, 0, ',', '.')); ?> đ</span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Trạng thái</span>
                    <span class="admin-detail-value">
                        <span class="admin-badge <?php echo e($payment->statusBadgeClass()); ?>"><?php echo e($payment->statusLabel()); ?></span>
                    </span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Thời điểm thanh toán</span>
                    <span class="admin-detail-value"><?php echo e(optional($payment->paid_at)->format('d/m/Y H:i') ?? 'Chưa thanh toán'); ?></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Ngày tạo</span>
                    <span class="admin-detail-value"><?php echo e(optional($payment->created_at)->format('d/m/Y H:i')); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Thông tin đơn đặt phòng</h3>
        </div>
        <div class="admin-card-body">
            <div class="admin-detail-list">
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Người dùng</span>
                    <span class="admin-detail-value"><?php echo e($payment->booking?->user?->full_name ?? 'Chưa xác định'); ?></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Email</span>
                    <span class="admin-detail-value"><?php echo e($payment->booking?->user?->email ?? '-'); ?></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Homestay</span>
                    <span class="admin-detail-value"><?php echo e($payment->booking?->homestay?->title ?? 'Chưa xác định'); ?></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Mã homestay</span>
                    <span class="admin-detail-value admin-code-text"><?php echo e($payment->booking?->homestay?->room_code ?? '-'); ?></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Ngày ở</span>
                    <span class="admin-detail-value">
                        <?php echo e(optional($payment->booking?->check_in)->format('d/m/Y') ?? '-'); ?>

                        -
                        <?php echo e(optional($payment->booking?->check_out)->format('d/m/Y') ?? '-'); ?>

                    </span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Trạng thái booking</span>
                    <span class="admin-detail-value"><?php echo e($payment->booking?->status ?? '-'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/payments/show.blade.php ENDPATH**/ ?>