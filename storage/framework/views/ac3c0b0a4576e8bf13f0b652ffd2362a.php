

<?php $__env->startSection('title', 'Mã giảm giá'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Quản lý mã giảm giá cho các chỗ nghỉ của bạn.</p>
    </div>
    <div class="admin-page-actions">
        <a href="<?php echo e(route('host.promotions.create')); ?>" class="admin-create-btn">
            <i class="bi bi-plus-lg"></i>
            Tạo mã giảm
        </a>
    </div>
</div>

<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary"><i class="bi bi-ticket-perforated"></i></div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e($totalPromotions); ?></div>
            <div class="admin-stat-label">Tổng mã</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success"><i class="bi bi-check-circle"></i></div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e($activePromotions); ?></div>
            <div class="admin-stat-label">Đang hoạt động</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-warning"><i class="bi bi-hourglass-split"></i></div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e($upcomingPromotions); ?></div>
            <div class="admin-stat-label">Sắp diễn ra</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-danger"><i class="bi bi-x-circle"></i></div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e($expiredPromotions); ?></div>
            <div class="admin-stat-label">Hết hạn</div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="mb-0 fw-bold h6">
            <i class="bi bi-percent me-2 text-primary"></i>Danh sách mã giảm giá
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Tiêu đề</th>
                        <th class="text-center">Giảm</th>
                        <th class="text-center">Thời gian</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promotion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $today = now()->toDateString();
                            $status = (!$promotion->is_active)
                                ? ['class' => 'admin-badge-cancelled', 'label' => 'Đang tắt']
                                : (($promotion->end_date->toDateString() < $today)
                                    ? ['class' => 'admin-badge-cancelled', 'label' => 'Hết hạn']
                                    : (($promotion->start_date->toDateString() > $today)
                                        ? ['class' => 'admin-badge-pending', 'label' => 'Sắp diễn ra']
                                        : ['class' => 'admin-badge-success', 'label' => 'Hoạt động']));
                        ?>
                        <tr>
                            <td class="ps-4"><span class="admin-id-badge">#<?php echo e($promotion->id); ?></span></td>
                            <td><div class="fw-bold"><?php echo e($promotion->title); ?></div></td>
                            <td class="text-center"><span class="text-success fw-bold"><?php echo e($promotion->discount_percent); ?>%</span></td>
                            <td class="text-center small text-muted"><?php echo e($promotion->start_date->format('d/m/Y')); ?> - <?php echo e($promotion->end_date->format('d/m/Y')); ?></td>
                            <td class="text-center"><span class="admin-badge <?php echo e($status['class']); ?>"><?php echo e($status['label']); ?></span></td>
                            <td class="text-center">
                                <div class="admin-actions d-flex justify-content-center gap-1">
                                    <a href="<?php echo e(route('host.promotions.edit', $promotion)); ?>" class="admin-action-btn" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('host.promotions.destroy', $promotion)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm này?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="admin-action-btn admin-action-btn-danger" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">Chưa có mã giảm giá nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            <?php echo e($promotions->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/promotions/index.blade.php ENDPATH**/ ?>