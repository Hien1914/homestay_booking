

<?php $__env->startSection('title', 'Doanh thu & rút tiền'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Quản lý doanh thu và yêu cầu rút tiền</p>
    </div>
</div>

<!-- Thống kê doanh thu -->
<div class="admin-stats-grid admin-stats-grid-3">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e(number_format($totalEarnings)); ?> đ</div>
            <div class="admin-stat-label">Tổng doanh thu của bạn</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-warning">
            <i class="bi bi-arrow-up-circle"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e(number_format($totalWithdrawn)); ?> đ</div>
            <div class="admin-stat-label">Đã rút</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary">
            <i class="bi bi-wallet2"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e(number_format($availableBalance)); ?> đ</div>
            <div class="admin-stat-label">Số dư khả dụng</div>
        </div>
    </div>
</div>

<!-- Form yêu cầu rút tiền -->
<div class="admin-card mb-4">
    <div class="admin-card-header">
        <h3><i class="bi bi-cash"></i> Yêu cầu rút tiền</h3>
    </div>
    <div class="admin-card-body">
        <form action="<?php echo e(route('host.payouts.store')); ?>" method="POST" class="admin-filters-form">
            <?php echo csrf_field(); ?>
            <div class="admin-filters-row align-items-end">
                <div class="admin-form-group mb-0" style="flex: 1; min-width: 200px;">
                    <label for="amount">Số tiền muốn rút (VNĐ)</label>
                    <input type="number" name="amount" id="amount" class="admin-form-control" placeholder="Tối thiểu 100.000đ" required min="100000">
                </div>
                <div class="admin-form-group mb-0">
                    <button type="submit" class="admin-btn admin-btn-primary">Gửi yêu cầu rút tiền</button>
                </div>
            </div>
        </form>
        <div class="mt-2">
            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Số tiền rút tối thiểu 100.000đ. Hệ thống sẽ xử lý và chuyển khoản cho bạn trong vòng 24h.</small>
        </div>
    </div>
</div>



<!-- Lịch sử rút tiền -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-clock-history"></i> Lịch sử rút tiền</h3>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Số tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày yêu cầu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>#<?php echo e($payout->id); ?></td>
                            <td><?php echo e(number_format($payout->amount)); ?> đ</td>
                            <td>
                                <?php if($payout->status == 'pending'): ?>
                                    <span class="admin-badge admin-badge-warning">Chờ xử lý</span>
                                <?php elseif($payout->status == 'completed'): ?>
                                    <span class="admin-badge admin-badge-success">Đã chuyển</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge-danger">Thất bại</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($payout->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4">Chưa có yêu cầu rút tiền nào</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($payouts->links()); ?>

    </div>
</div>

<!-- Danh sách booking doanh thu -->
<div class="admin-card mt-4">
    <div class="admin-card-header">
        <h3><i class="bi bi-receipt"></i> Chi tiết doanh thu</h3>
    </div>
    <div class="admin-card-body">
        <form method="GET" action="<?php echo e(route('host.earnings.index')); ?>" class="admin-filters-form mb-3">
            <div class="admin-filters-row align-items-end">
                <div class="admin-form-group">
                    <label for="from_date">Từ ngày</label>
                    <input type="date" name="from_date" id="from_date" class="admin-form-control" value="<?php echo e(request('from_date')); ?>">
                </div>
                <div class="admin-form-group">
                    <label for="to_date">Đến ngày</label>
                    <input type="date" name="to_date" id="to_date" class="admin-form-control" value="<?php echo e(request('to_date')); ?>">
                </div>
                <div class="admin-form-group">
                    <label class="d-none d-md-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="admin-btn admin-btn-primary">Lọc</button>
                        <a href="<?php echo e(route('host.earnings.index')); ?>" class="admin-btn admin-btn-secondary">Xóa lọc</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Homestay</th>
                        <th>Khách</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>#<?php echo e($booking->id); ?></td>
                            <td><?php echo e($booking->homestay->title); ?></td>
                            <td><?php echo e($booking->user->full_name); ?></td>
                            <td><?php echo e(number_format($booking->total_amount)); ?> đ</td>
                            <td><?php echo e($booking->created_at->format('d/m/Y')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5">Chưa có booking nào</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($bookings->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .admin-filters-row .admin-btn {
        height: 44px;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/earnings/index.blade.php ENDPATH**/ ?>