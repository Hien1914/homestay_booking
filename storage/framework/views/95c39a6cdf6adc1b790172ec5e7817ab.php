

<?php $__env->startSection('title', 'Quản lý thanh toán'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-page-header">
        <div class="admin-page-header-content">
            <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
            <p class="admin-page-subtitle">Theo dõi giao dịch thanh toán và doanh thu theo ngày.</p>
        </div>
    </div>

    <div class="admin-stats-grid admin-stats-grid-3">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-danger">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e(number_format((float) $totalRevenue, 0, ',', '.')); ?> đ</div>
                <div class="admin-stat-label">Tổng doanh thu</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-success">
                <i class="bi bi-patch-check"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e(number_format($successPayments)); ?></div>
                <div class="admin-stat-label">Thanh toán thành công</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e(number_format($pendingPayments)); ?></div>
                <div class="admin-stat-label">Chưa thanh toán</div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="card-title mb-0 fw-bold h6">
                <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Doanh thu theo ngày
            </h5>
        </div>
        <div class="card-body p-4">
            <div style="height: 450px;">
                <canvas id="paymentsRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('admin.payments')); ?>" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo e($fromDate); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo e($toDate); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e($status === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                    <a href="<?php echo e(route('admin.payments')); ?>"
                        class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="card-title mb-0 fw-bold h6">
                <i class="bi bi-wallet2 me-2 text-primary"></i>Bảng doanh thu và thanh toán
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Người dùng</th>
                            <th>Email</th>
                            <th>Tên homestay</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <span class="admin-id-badge">#<?php echo e($payment->booking?->id ?? '-'); ?></span>
                                </td>
                                <td class="fw-bold"><?php echo e($payment->booking?->user?->full_name ?? 'Chưa xác định'); ?></td>
                                <td><?php echo e($payment->booking?->user?->email ?? '-'); ?></td>
                                <td><?php echo e($payment->booking?->homestay?->title ?? '-'); ?></td>
                                <td>
                                    <span
                                        class="fw-bold text-success"><?php echo e(number_format((float) $payment->amount, 0, ',', '.')); ?>

                                        đ</span>
                                </td>
                                <td>
                                    <span
                                        class="admin-badge <?php echo e($payment->statusBadgeClass()); ?>"><?php echo e($payment->statusLabel()); ?></span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <?php if($payment->payment_status === \App\Models\Payment::STATUS_PENDING && $payment->paid_at && $payment->booking): ?>
                                            <form action="<?php echo e(route('admin.payments.confirm', $payment->booking->id)); ?>"
                                                method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <button type="submit" class="admin-action-btn"
                                                    style="color: var(--admin-success); border-color: var(--admin-success);"
                                                    title="Xác nhận đã nhận tiền"
                                                    onclick="return confirm('Xác nhận admin đã nhận được khoản chuyển này?');">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <button type="button" class="admin-action-btn" title="Xem chi tiết"
                                            onclick="showPaymentDetail(<?php echo e($payment->id); ?>)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">Chưa có giao dịch thanh toán nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                <?php echo e($payments->links()); ?>

            </div>
        </div>
    </div>

    <!-- Payment Detail Modal -->
    <div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content admin-modal-content"
                style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Chi tiết thanh toán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3" id="paymentDetailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
        <script>
            function showPaymentDetail(paymentId) {
                const modal = new bootstrap.Modal(document.getElementById('paymentDetailModal'));
                const content = document.getElementById('paymentDetailContent');

                content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
                modal.show();

                fetch(`/admin/payments/${paymentId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.text())
                    .then(html => {
                        content.innerHTML = html;
                    })
                    .catch(err => {
                        content.innerHTML = '<div class="alert alert-danger">Lỗi kết nối.</div>';
                    });
            }

            document.addEventListener('DOMContentLoaded', function () {
                var canvas = document.getElementById('paymentsRevenueChart');
                if (!canvas) return;

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
                                grid: { color: '#f1f5f9' },
                                ticks: {
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>