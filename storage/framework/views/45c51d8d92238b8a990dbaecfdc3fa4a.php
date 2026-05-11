<?php $__env->startSection('title', 'Tổng quát kênh Host'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header mb-4">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title h3 fw-bold mb-1"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle text-muted small mb-0">Theo dõi hoạt động kinh doanh của các chỗ nghỉ bạn đang quản lý.</p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
    <div class="card-body p-4">
        <form method="GET" action="<?php echo e(route('host.dashboard')); ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="host_dashboard_from_date" class="form-label small fw-bold text-secondary">Từ ngày</label>
                <input type="date" id="host_dashboard_from_date" name="from_date" class="form-control" value="<?php echo e($fromDate); ?>">
            </div>
            <div class="col-md-4">
                <label for="host_dashboard_to_date" class="form-label small fw-bold text-secondary">Đến ngày</label>
                <input type="date" id="host_dashboard_to_date" name="to_date" class="form-control" value="<?php echo e($toDate); ?>">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                <a href="<?php echo e(route('host.dashboard')); ?>" class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="admin-stats-grid mb-4">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-house-door"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e(number_format($totalHomestays)); ?></div>
            <div class="admin-stat-label">Chỗ nghỉ</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-warning">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e(number_format($totalBookings)); ?></div>
            <div class="admin-stat-label">Đặt phòng</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-danger">
            <i class="bi bi-cash-coin"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value text-nowrap"><?php echo e(number_format((float) $totalRevenue, 0, ',', '.')); ?> đ</div>
            <div class="admin-stat-label">Doanh thu</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold h6 text-secondary" style="letter-spacing: 0.5px;">Doanh thu và lượng đặt phòng</h5>
            </div>
            <div class="card-body p-4">
                <div class="admin-chart-container" style="height: 350px;">
                    <canvas id="revenueBookingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-light-subtle">
                <h5 class="mb-0 fw-bold h6 text-secondary" style="letter-spacing: 0.5px;">Trạng thái đặt phòng</h5>
            </div>
            <div class="card-body p-4">
                <div class="admin-chart-container" style="height: 350px;">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="mb-0 fw-bold h6 text-secondary">Chỗ nghỉ mới cập nhật</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light-subtle">
                    <tr>
                        <th class="ps-4 py-3 small text-secondary fw-bold" style="font-size: 11px;">Tên chỗ nghỉ</th>
                        <th class="text-center py-3 small text-secondary fw-bold" style="font-size: 11px;">Điểm đến</th>
                        <th class="text-center py-3 small text-secondary fw-bold" style="font-size: 11px;">Trạng thái</th>
                        <th class="pe-4 text-end py-3 small text-secondary fw-bold" style="font-size: 11px;">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentHomestays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $homestay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4 py-3 fw-bold small"><?php echo e($homestay->title); ?></td>
                            <td class="text-center py-3 small"><?php echo e($homestay->destination->name ?? $homestay->province ?? '-'); ?></td>
                            <td class="text-center py-3">
                                <span class="badge <?php echo e($homestay->status === 'available' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'); ?> rounded-pill small" style="font-size: 10px;"><?php echo e($homestay->status === 'available' ? 'Còn trống' : 'Bận'); ?></span>
                            </td>
                            <td class="pe-4 text-end py-3 small text-muted"><?php echo e(optional($homestay->created_at)->format('d/m/Y')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted small">Chưa có dữ liệu.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="mb-0 fw-bold h6 text-secondary">Đặt phòng gần đây</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light-subtle">
                    <tr>
                        <th class="ps-4 py-3 small text-secondary fw-bold" style="font-size: 11px;">Khách hàng</th>
                        <th class="py-3 small text-secondary fw-bold" style="font-size: 11px;">Chỗ nghỉ</th>
                        <th class="text-center py-3 small text-secondary fw-bold" style="font-size: 11px;">Nhận/Trả phòng</th>
                        <th class="pe-4 text-end py-3 small text-secondary fw-bold" style="font-size: 11px;">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4 py-3 fw-bold small"><?php echo e($booking->user?->full_name ?? 'Khách'); ?></td>
                            <td class="py-3 small"><?php echo e($booking->homestay?->title ?? '-'); ?></td>
                            <td class="text-center py-3 small">
                                <span class="text-dark"><?php echo e(optional($booking->check_in)->format('d/m/Y')); ?></span>
                                <span class="text-muted ms-1">- <?php echo e(optional($booking->check_out)->format('d/m/Y')); ?></span>
                            </td>
                            <td class="pe-4 text-end py-3">
                                <span class="badge bg-light text-dark border small rounded-pill px-2 py-1" style="font-size: 10px;"><?php echo e($booking->statusLabel()); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted small">Chưa có dữ liệu.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.dashboardChartsData = {
    chartLabels: <?php echo json_encode($chartLabels, 15, 512) ?>,
    scatterRevenue: <?php echo json_encode($scatterRevenue, 15, 512) ?>,
    scatterBookings: <?php echo json_encode($scatterBookings, 15, 512) ?>,
    chartTickLimit: <?php echo json_encode($chartTickLimit, 15, 512) ?>,
    bookingStatusLabels: <?php echo json_encode($bookingStatusLabels, 15, 512) ?>,
    bookingStatusData: <?php echo json_encode($bookingStatusData, 15, 512) ?>,
    bookingStatusColors: <?php echo json_encode($bookingStatusColors, 15, 512) ?>,
};
</script>
<script src="<?php echo e(asset('js/admin/dashboard-charts.js')); ?>"></script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/dashboard.blade.php ENDPATH**/ ?>