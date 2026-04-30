

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
<div class="card border-0 shadow-sm mb-4 rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-cash-coin me-2 text-primary"></i>Yêu cầu rút tiền
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo e(route('host.payouts.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row g-3 align-items-end">
                <div class="col-md-9">
                    <label for="amount" class="form-label small fw-bold">Số tiền muốn rút (VNĐ)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">₫</span>
                        <input type="number" name="amount" id="amount" class="form-control border-start-0 ps-0" placeholder="Tối thiểu 100.000đ" required min="100000">
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="admin-filter-btn w-100 justify-content-center py-2">Gửi yêu cầu</button>
                </div>
            </div>
        </form>
        <div class="mt-3 p-3 bg-light rounded-3 border-start border-4 border-primary">
            <div class="small text-muted d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                Số tiền rút tối thiểu 100.000đ. Hệ thống sẽ xử lý và chuyển khoản cho bạn trong vòng 24h.
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Lịch sử rút tiền -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-light-subtle">
                <h5 class="card-title mb-0 fw-bold h6">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Lịch sử rút tiền
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="admin-table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Số tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày yêu cầu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?php echo e(number_format($payout->amount)); ?>đ</td>
                                    <td>
                                        <?php if($payout->status == 'pending'): ?>
                                            <span class="admin-badge admin-badge-pending">Chờ xử lý</span>
                                        <?php elseif($payout->status == 'completed'): ?>
                                            <span class="admin-badge admin-badge-success">Đã chuyển</span>
                                        <?php else: ?>
                                            <span class="admin-badge admin-badge-cancelled">Thất bại</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small text-muted"><?php echo e($payout->created_at->format('d/m/Y')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="3" class="text-center py-5 text-muted small">Chưa có yêu cầu rút tiền nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($payouts->hasPages()): ?>
                    <div class="p-3 border-top d-flex justify-content-center">
                        <?php echo e($payouts->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Chi tiết doanh thu -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-light-subtle">
                <h5 class="card-title mb-0 fw-bold h6">
                    <i class="bi bi-receipt me-2 text-primary"></i>Chi tiết doanh thu
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="p-4 border-bottom bg-light-subtle">
                    <form method="GET" action="<?php echo e(route('host.earnings.index')); ?>" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Từ ngày</label>
                            <input type="date" name="from_date" class="form-control" value="<?php echo e(request('from_date')); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Đến ngày</label>
                            <input type="date" name="to_date" class="form-control" value="<?php echo e(request('to_date')); ?>">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                            <a href="<?php echo e(route('host.earnings.index')); ?>" class="admin-filter-clear-btn w-100 justify-content-center text-decoration-none d-flex align-items-center">Xóa</a>
                        </div>
                    </form>
                </div>
                <div class="admin-table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Homestay</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="fw-medium text-truncate" style="max-width: 150px;"><?php echo e($booking->homestay->title); ?></td>
                                    <td><?php echo e($booking->user->full_name); ?></td>
                                    <td class="fw-bold text-success"><?php echo e(number_format($booking->total_amount)); ?>đ</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="3" class="text-center py-5 text-muted small">Chưa có dữ liệu doanh thu.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($bookings->hasPages()): ?>
                    <div class="p-3 border-top d-flex justify-content-center">
                        <?php echo e($bookings->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
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
<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/earnings.blade.php ENDPATH**/ ?>