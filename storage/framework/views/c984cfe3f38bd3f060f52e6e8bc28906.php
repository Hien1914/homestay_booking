<?php $__env->startSection('title', 'Tổng quát hệ thống'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <h1 class="admin-page-title h3 fw-bold mb-1"><?php echo $__env->yieldContent('title'); ?></h1>
            <p class="admin-page-subtitle text-muted small mb-0">Theo dõi số liệu tổng hợp và hoạt động mới nhất của hệ
                thống.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('admin.dashboard')); ?>" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="dashboard_from_date" class="form-label small fw-bold text-secondary">Từ ngày</label>
                    <input type="date" id="dashboard_from_date" name="from_date" class="form-control border-light-subtle"
                        value="<?php echo e($fromDate); ?>">
                </div>
                <div class="col-md-4">
                    <label for="dashboard_to_date" class="form-label small fw-bold text-secondary">Đến ngày</label>
                    <input type="date" id="dashboard_to_date" name="to_date" class="form-control border-light-subtle"
                        value="<?php echo e($toDate); ?>">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="admin-filter-btn">
                        <i class="bi bi-filter"></i> Lọc dữ liệu
                    </button>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="admin-filter-clear-btn">Xóa lọc</a>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-primary">
                <i class="bi bi-people"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e(number_format($totalUsers)); ?></div>
                <div class="admin-stat-label">Người dùng</div>
            </div>
        </div>
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
                <div
                    class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold h6" style="letter-spacing: 0.5px;">Doanh thu và lượng đặt phòng</h5>
                </div>
                <div class="card-body p-4">
                    <div class="admin-chart-container" style="height: 350px;">
                        <canvas id="revenueBookingChart"></canvas>
                    </div>
                    <div class="d-flex flex-wrap gap-3 justify-content-center small mt-4">
                        <span><span class="admin-dot d-inline-block me-1 rounded-circle"
                                style="background:#1d4ed8; width:10px; height:10px;"></span>Doanh thu</span>
                        <span><span class="admin-dot d-inline-block me-1 rounded-circle"
                                style="background:#f97316; width:10px; height:10px;"></span>Lượng đặt phòng</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-light-subtle">
                    <h5 class="card-title mb-0 fw-bold h6" style="letter-spacing: 0.5px;">Trạng thái đặt phòng</h5>
                </div>
                <div class="card-body p-4">
                    <div class="admin-chart-container" style="height: 350px;">
                        <canvas id="bookingStatusChart"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                <div
                    class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold h6" style="letter-spacing: 0.5px;">Chỗ nghỉ mới nhất</h5>
                    <a href="<?php echo e(route('admin.homestays')); ?>"
                        class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Tên chỗ nghỉ</th>
                                    <th>Mã</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentHomestays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $homestay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark small text-truncate" style="max-width: 200px;">
                                                <?php echo e($homestay->title); ?></div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-light text-dark border small fw-medium"><?php echo e($homestay->room_code); ?></span>
                                        </td>
                                        <td>
                                            <?php if($homestay->status === 'available'): ?>
                                                <span class="admin-badge admin-badge-success">Còn trống</span>
                                            <?php else: ?>
                                                <span class="admin-badge admin-badge-warning">Bận</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(optional($homestay->created_at)->format('d/m/Y')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5 small">Chưa có dữ liệu.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                <div
                    class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold h6" style="letter-spacing: 0.5px;">Người dùng mới nhất</h5>
                    <a href="<?php echo e(route('admin.users')); ?>"
                        class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark small"><?php echo e($user->full_name); ?></div>
                                        </td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td><?php echo e(optional($user->created_at)->format('d/m/Y')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-5 small">Chưa có dữ liệu.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold h6" style="letter-spacing: 0.5px;">Top 5 homestay được săn đón nhất</h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Top</th>
                            <th>Chỗ nghỉ</th>
                            <th>Điểm đến</th>
                            <th>Lượt đặt</th>
                            <th>Lượt thích</th>
                            <th>Đánh giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $popularHomestays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $homestay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <span
                                        class="badge <?php echo e($index == 0 ? 'bg-warning' : ($index == 1 ? 'bg-secondary-subtle text-secondary' : ($index == 2 ? 'bg-danger-subtle text-danger' : 'bg-light text-muted'))); ?> rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 24px; height: 24px; font-size: 10px;"><?php echo e($index + 1); ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small"><?php echo e($homestay->title); ?></div>
                                </td>
                                <td><?php echo e($homestay->destination->name ?? $homestay->province ?? '-'); ?></td>
                                <td>
                                    <span
                                        class="admin-badge admin-badge-success"><?php echo e(number_format($homestay->bookings_count)); ?></span>
                                </td>
                                <td>
                                    <span
                                        class="admin-badge admin-badge-danger"><?php echo e(number_format($homestay->favorites_count)); ?></span>
                                </td>
                                <td>
                                    <div class="text-warning small fw-bold">
                                        <i
                                            class="bi bi-star-fill me-1"></i><?php echo e(number_format((float) ($homestay->reviews_avg_rating ?? 0), 1)); ?>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5 small">Chưa có dữ liệu.</td>
                            </tr>
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
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>