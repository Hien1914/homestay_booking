<?php $__env->startSection('title', 'Quản lý rút tiền'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="admin-page-title">Yêu cầu rút tiền</h1>
        <p class="admin-page-subtitle">Duyệt và quản lý các yêu cầu rút tiền từ Host</p>
    </div>
</div>

<div class="admin-stats-grid admin-stats-grid-4 mb-4">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary">
            <i class="bi bi-list-task"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e(number_format($stats['total'])); ?></div>
            <div class="admin-stat-label">Tổng số yêu cầu</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-warning">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e(number_format($stats['pending'])); ?></div>
            <div class="admin-stat-label">Chờ xử lý</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e(number_format($stats['completed'])); ?></div>
            <div class="admin-stat-label">Đã hoàn thành</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-danger">
            <i class="bi bi-x-circle"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e(number_format($stats['failed'])); ?></div>
            <div class="admin-stat-label">Đã từ chối</div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <h5 class="admin-card-title">Danh sách yêu cầu</h5>
        <div class="admin-card-actions">
            <form action="<?php echo e(route('admin.payouts')); ?>" method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Chờ xử lý</option>
                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Đã hoàn tất</option>
                    <option value="failed" <?php echo e(request('status') == 'failed' ? 'selected' : ''); ?>>Đã từ chối</option>
                </select>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Host</th>
                    <th>Số tiền</th>
                    <th>Ngày yêu cầu</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>#<?php echo e($payout->id); ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-person text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold"><?php echo e($payout->host->name); ?></div>
                                <div class="small text-muted"><?php echo e($payout->host->email); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="fw-bold"><?php echo e(number_format($payout->amount)); ?>đ</td>
                    <td><?php echo e($payout->created_at->format('d/m/Y H:i')); ?></td>
                    <td>
                        <?php if($payout->status === 'pending'): ?>
                            <span class="admin-badge admin-badge-warning">Đang chờ</span>
                        <?php elseif($payout->status === 'completed'): ?>
                            <span class="admin-badge admin-badge-success">Thành công</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge-danger">Đã từ chối</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <div class="admin-actions d-flex justify-content-end gap-2">
                            <?php if($payout->status === 'pending'): ?>
                                <form action="<?php echo e(route('admin.payouts.approve', $payout)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn duyệt yêu cầu rút tiền này?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <button type="submit" class="admin-action-btn text-success" title="Duyệt yêu cầu">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                <form action="<?php echo e(route('admin.payouts.reject', $payout)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn từ chối yêu cầu rút tiền này?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <button type="submit" class="admin-action-btn text-danger" title="Từ chối yêu cầu">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small italic">Đã xử lý lúc <?php echo e($payout->updated_at->format('d/m/Y H:i')); ?></span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-4">Không có yêu cầu nào.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($payouts->hasPages()): ?>
    <div class="admin-card-footer">
        <?php echo e($payouts->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/payouts/index.blade.php ENDPATH**/ ?>