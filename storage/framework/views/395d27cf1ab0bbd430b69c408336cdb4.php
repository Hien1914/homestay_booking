

<?php $__env->startSection('title', 'Đăng ký chủ nhà'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $applicationCollection = $applications->getCollection();
        $totalApplications = $applications->total();
        $pendingCount = $applicationCollection->where('status', \App\Models\HostApplication::STATUS_PENDING)->count();
        $approvedCount = $applicationCollection->filter(fn($item) => $item->isApproved())->count();
        $rejectedCount = $applicationCollection->where('status', \App\Models\HostApplication::STATUS_REJECTED)->count();
    ?>

    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
            <p class="admin-page-subtitle">Duyệt và quản lý đơn đăng ký trở thành chủ nhà.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('admin.host-applications')); ?>" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo e($fromDate ?? ''); ?>">
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo e($toDate ?? ''); ?>">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                    <a href="<?php echo e(route('admin.host-applications')); ?>"
                        class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-primary">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e($totalApplications); ?></div>
                <div class="admin-stat-label">Tổng đơn đăng ký</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e($pendingCount); ?></div>
                <div class="admin-stat-label">Chờ duyệt</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-success">
                <i class="bi bi-patch-check"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e($approvedCount); ?></div>
                <div class="admin-stat-label">Đã xác nhận</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-danger">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e($rejectedCount); ?></div>
                <div class="admin-stat-label">Đã từ chối</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="card-title mb-0 fw-bold h6">
                <i class="bi bi-person-badge me-2 text-primary"></i>Danh sách yêu cầu đăng ký chủ nhà
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>CMND/CCCD</th>
                            <th>Ngân hàng</th>
                            <th>Số tài khoản</th>
                            <th>Chủ tài khoản</th>
                            <th>Trạng thái</th>
                            <th>Ngày gửi</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <span class="admin-id-badge">#<?php echo e($app->id); ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo e($app->user?->full_name ?? 'Khách'); ?></div>
                                    <small class="text-muted"><?php echo e($app->user?->email ?? '-'); ?></small>
                                </td>
                                <td><?php echo e($app->id_card ?? '-'); ?></td>
                                <td><?php echo e($app->bank_name ?? '-'); ?></td>
                                <td><?php echo e($app->bank_acc ?? '-'); ?></td>
                                <td><?php echo e($app->bank_holder ?? '-'); ?></td>
                                <td>
                                    <?php if($app->status === \App\Models\HostApplication::STATUS_PENDING): ?>
                                        <span class="admin-badge admin-badge-pending">Chờ duyệt</span>
                                    <?php elseif($app->isApproved()): ?>
                                        <span class="admin-badge admin-badge-confirmed">Đã xác nhận</span>
                                    <?php else: ?>
                                        <span class="admin-badge admin-badge-cancelled">Từ chối</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e(optional($app->created_at)->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <?php if($app->status === \App\Models\HostApplication::STATUS_PENDING): ?>
                                        <div class="d-flex justify-content-center gap-1">
                                            <form action="<?php echo e(route('admin.host-applications.approve', $app->id)); ?>" method="POST"
                                                class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <button type="submit" class="admin-action-btn admin-action-btn-success"
                                                    title="Duyệt" onclick="return confirm('Duyệt đơn này?')">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form action="<?php echo e(route('admin.host-applications.reject', $app->id)); ?>" method="POST"
                                                class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <button type="submit" class="admin-action-btn admin-action-btn-danger"
                                                    title="Từ chối" onclick="return confirm('Từ chối đơn này?')">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">Đã xử lý</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Chưa có đơn đăng ký nào
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                <?php echo e($applications->links()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/host-applications.blade.php ENDPATH**/ ?>