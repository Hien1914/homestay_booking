

<?php $__env->startSection('title', 'Quản lý đặt phòng'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <h1 class="admin-page-title h3 fw-bold mb-1"><?php echo $__env->yieldContent('title'); ?></h1>
            <p class="admin-page-subtitle text-muted small mb-0">Quản lý tất cả đơn đặt phòng trong hệ thống</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-primary">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e($totalBookings); ?></div>
                <div class="admin-stat-label">Tổng đặt phòng</div>
            </div>
        </div>

        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e($confirmedBookings); ?></div>
                <div class="admin-stat-label">Đã xác nhận</div>
            </div>
        </div>

        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-info">
                <i class="bi bi-credit-card"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e(number_format((float) $totalRevenue, 0, ',', '.')); ?>đ</div>
                <div class="admin-stat-label">Tổng doanh thu</div>
            </div>
        </div>

        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-success">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e(number_format($successPayments ?? 0)); ?></div>
                <div class="admin-stat-label">Đã thanh toán</div>
            </div>
        </div>

        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e(number_format($pendingPaymentsCount ?? 0)); ?></div>
                <div class="admin-stat-label">Đang chờ thanh toán</div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="card-title mb-0 fw-bold h6">
                <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Doanh thu theo ngày
            </h5>
        </div>
        <div class="card-body p-4">
            <div style="height: 300px;">
                <canvas id="paymentsRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <div class="row g-4 align-items-end">
                <div class="col-lg-12">
                    <form method="GET" action="<?php echo e(route('admin.bookings')); ?>" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="bookings_from_date" class="form-label small fw-bold text-secondary">Từ ngày</label>
                            <input type="date" id="bookings_from_date" name="from_date" class="form-control"
                                value="<?php echo e($fromDate); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="bookings_to_date" class="form-label small fw-bold text-secondary">Đến ngày</label>
                            <input type="date" id="bookings_to_date" name="to_date" class="form-control"
                                value="<?php echo e($toDate); ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="bookings_payment_status" class="form-label small fw-bold text-secondary">Trạng thái thanh toán</label>
                            <select id="bookings_payment_status" name="payment_status" class="form-select">
                                <option value="">Tất cả</option>
                                <?php $__currentLoopData = ($paymentStatuses ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php echo e(($paymentStatus ?? '') === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                            <a href="<?php echo e(route('admin.bookings')); ?>"
                                class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approval Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold h6 text-secondary" style="letter-spacing: 0.5px;">Đơn cần xử lý</h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Homestay</th>
                            <th>Lịch trình</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pendingApprovals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-status="<?php echo e($booking->status); ?>">
                                <td><span class="fw-bold text-secondary">#<?php echo e($booking->id); ?></span></td>
                                <td><div class="fw-bold text-dark small"><?php echo e($booking->user?->full_name ?? 'Khách'); ?></div></td>
                                <td>
                                    <div class="small fw-semibold text-dark text-truncate mx-auto" style="max-width: 150px;"><?php echo e($booking->homestay?->title ?? '-'); ?></div>
                                    <div class="text-muted" style="font-size: 10px;">Mã: #<?php echo e($booking->homestay_id); ?></div>
                                </td>
                                <td>
                                    <div class="small text-dark fw-medium"><?php echo e(optional($booking->check_in)->format('d/m/Y')); ?></div>
                                    <div class="text-muted" style="font-size: 10px;">đến <?php echo e(optional($booking->check_out)->format('d/m/Y')); ?></div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success small"><?php echo e(number_format((float) $booking->total_amount, 0, ',', '.')); ?>đ</span>
                                    <?php if($booking->status === \App\Models\Booking::STATUS_CANCELLED): ?>
                                        <br>
                                        <span class="badge bg-warning text-dark mt-1" style="font-size: 0.75rem;">Hoàn trả: <?php echo e(number_format((float) $booking->refund_amount, 0, ',', '.')); ?>đ (<?php echo e($booking->refund_percent); ?>%)</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="admin-badge <?php echo e($booking->statusBadgeClass()); ?>"><?php echo e($booking->statusLabel()); ?></span>
                                </td>
                                <td>
                                    <?php if($booking->payment): ?>
                                        <span class="admin-badge <?php echo e($booking->payment->displayStatusBadgeClass()); ?>"><?php echo e($booking->payment->displayStatusLabel()); ?></span>
                                    <?php else: ?>
                                        <span class="admin-badge admin-badge-pending">Đang chờ thanh toán</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <?php if($booking->status === \App\Models\Booking::STATUS_CANCELLED): ?>
                                            <form action="<?php echo e(route('admin.payments.confirm', $booking->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <button type="submit" class="admin-action-btn admin-action-btn-success" title="Xác nhận hoàn tiền/xử lý" onclick="return confirm('Xác nhận đã xử lý hoàn tiền cho đơn hủy này?');">
                                                    <i class="bi bi-check-all"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form action="<?php echo e(route('admin.payments.confirm', $booking->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <button type="submit" class="admin-action-btn admin-action-btn-success" title="Xác nhận đơn đặt phòng" onclick="return confirm('Xác nhận duyệt đơn đặt phòng này?');">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form action="<?php echo e(route('admin.payments.confirm', $booking->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <input type="hidden" name="payment_action" value="reject">
                                                <button type="submit" class="admin-action-btn admin-action-btn-danger" title="Từ chối đơn đặt phòng" onclick="return confirm('Xác nhận từ chối đơn đặt phòng này?');">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <button type="button" class="admin-action-btn" title="Chi tiết đơn đặt phòng" onclick="showBookingDetail(<?php echo e($booking->id); ?>)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5 small">Không có đơn đã thanh toán nào đang chờ duyệt.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <?php echo e($pendingApprovals->links()); ?>

            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold h6 text-secondary" style="letter-spacing: 0.5px;">Đơn chờ thanh toán</h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Homestay</th>
                            <th>Lịch trình</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pendingPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-status="<?php echo e($booking->status); ?>">
                                <td><span class="fw-bold text-secondary">#<?php echo e($booking->id); ?></span></td>
                                <td><div class="fw-bold text-dark small"><?php echo e($booking->user?->full_name ?? 'Khách'); ?></div></td>
                                <td>
                                    <div class="small fw-semibold text-dark text-truncate mx-auto" style="max-width: 150px;"><?php echo e($booking->homestay?->title ?? '-'); ?></div>
                                    <div class="text-muted" style="font-size: 10px;">Mã: #<?php echo e($booking->homestay_id); ?></div>
                                </td>
                                <td>
                                    <div class="small text-dark fw-medium"><?php echo e(optional($booking->check_in)->format('d/m/Y')); ?></div>
                                    <div class="text-muted" style="font-size: 10px;">đến <?php echo e(optional($booking->check_out)->format('d/m/Y')); ?></div>
                                </td>
                                <td><span class="fw-bold text-success small"><?php echo e(number_format((float) $booking->total_amount, 0, ',', '.')); ?>đ</span></td>
                                <td><span class="admin-badge <?php echo e($booking->statusBadgeClass()); ?>"><?php echo e($booking->statusLabel()); ?></span></td>
                                <td>
                                    <?php if($booking->payment): ?>
                                        <span class="admin-badge <?php echo e($booking->payment->displayStatusBadgeClass()); ?>"><?php echo e($booking->payment->displayStatusLabel()); ?></span>
                                    <?php else: ?>
                                        <span class="admin-badge admin-badge-pending">Đang chờ thanh toán</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="admin-action-btn" title="Chi tiết đơn đặt phòng" onclick="showBookingDetail(<?php echo e($booking->id); ?>)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5 small">Không có đơn nào đang chờ thanh toán.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <?php echo e($pendingPayments->links()); ?>

            </div>
        </div>
    </div>

    <!-- General Bookings Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold h6 text-secondary" style="letter-spacing: 0.5px;">Danh sách đặt phòng chung</h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table" id="bookingsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Homestay</th>
                            <th>Lịch trình</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-status="<?php echo e($booking->status); ?>">
                                <td>
                                    <span class="fw-bold text-secondary">#<?php echo e($booking->id); ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small"><?php echo e($booking->user?->full_name ?? 'Khách'); ?></div>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark text-truncate mx-auto" style="max-width: 150px;">
                                        <?php echo e($booking->homestay?->title ?? '-'); ?></div>
                                    <div class="text-muted" style="font-size: 10px;">Mã: #<?php echo e($booking->homestay_id); ?></div>
                                </td>
                                <td>
                                    <div class="small text-dark fw-medium"><?php echo e(optional($booking->check_in)->format('d/m/Y')); ?>

                                    </div>
                                    <div class="text-muted" style="font-size: 10px;">đến
                                        <?php echo e(optional($booking->check_out)->format('d/m/Y')); ?></div>
                                </td>
                                <td>
                                    <span
                                        class="fw-bold text-success small"><?php echo e(number_format((float) $booking->total_amount, 0, ',', '.')); ?>đ</span>
                                </td>
                                <td>
                                    <span class="admin-badge <?php echo e($booking->statusBadgeClass()); ?>"><?php echo e($booking->statusLabel()); ?></span>
                                </td>
                                <td>
                                    <?php if($booking->payment): ?>
                                        <span class="admin-badge <?php echo e($booking->payment->displayStatusBadgeClass()); ?>"><?php echo e($booking->payment->displayStatusLabel()); ?></span>
                                    <?php else: ?>
                                        <span class="admin-badge admin-badge-pending">Đang chờ thanh toán</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">

                                        <button type="button" class="admin-action-btn" title="Chi tiết đơn đặt phòng"
                                            onclick="showBookingDetail(<?php echo e($booking->id); ?>)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5 small">Chưa có dữ liệu đặt phòng.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <?php echo e($bookings->links()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <!-- Booking Detail Modal -->
    <div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-light-subtle">
                    <h5 class="modal-title fw-bold">Chi tiết đơn đặt phòng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="bookingDetailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showBookingDetail(bookingId) {
            const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
            const content = document.getElementById('bookingDetailContent');
            content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-success"></div></div>';
            modal.show();

            fetch(`/admin/bookings/${bookingId}/detail`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.text())
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(err => {
                    content.innerHTML = '<div class="alert alert-danger rounded-4">Lỗi tải dữ liệu. Vui lòng thử lại.</div>';
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var canvas = document.getElementById('paymentsRevenueChart');
            if (!canvas) return;
            var revenueSeries = <?php echo json_encode($revenueData, 15, 512) ?>;

            function getNiceStep(value) {
                if (!value || value <= 0) return 100000;
                var exponent = Math.pow(10, Math.floor(Math.log10(value)));
                var fraction = value / exponent;
                var niceFraction = 1;

                if (fraction <= 1) {
                    niceFraction = 1;
                } else if (fraction <= 2) {
                    niceFraction = 2;
                } else if (fraction <= 5) {
                    niceFraction = 5;
                } else {
                    niceFraction = 10;
                }

                return niceFraction * exponent;
            }

            var maxRevenue = Math.max.apply(null, revenueSeries.concat([0]));
            var targetSegments = 5;
            var stepSize = getNiceStep(maxRevenue / targetSegments);
            var yAxisMax = stepSize * targetSegments;

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($chartLabels, 15, 512) ?>,
                    datasets: [{
                        label: 'Doanh thu',
                        data: <?php echo json_encode($revenueData, 15, 512) ?>,
                        borderColor: '#3b82f6',
                        backgroundColor: function (context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.02)');
                            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.15)');
                            return gradient;
                        },
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#3b82f6',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 14 },
                            callbacks: {
                                label: function (context) {
                                    return ' ' + new Intl.NumberFormat('vi-VN').format(context.raw || 0) + ' đ';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: yAxisMax,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                count: 6,
                                stepSize: stepSize,
                                font: { size: 12 },
                                callback: function (value) {
                                    return new Intl.NumberFormat('vi-VN').format(value) + ' đ';
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 12 } }
                        }
                    }
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/bookings.blade.php ENDPATH**/ ?>