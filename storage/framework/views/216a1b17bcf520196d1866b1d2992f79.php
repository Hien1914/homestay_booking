

<?php $__env->startSection('title', 'Quản lý chỗ nghỉ'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Quản lý tất cả homestay bạn đăng ký</p>
    </div>
    <div class="admin-page-actions">
        <a href="<?php echo e(route('host.homestays.create')); ?>" class="admin-create-btn">
            <i class="bi bi-plus-lg"></i>
            Thêm mới
        </a>
    </div>
</div>

<!-- Thống kê nhanh -->
<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary">
            <i class="bi bi-house-door"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e($stats['total']); ?></div>
            <div class="admin-stat-label">Tổng chỗ nghỉ</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e($stats['available']); ?></div>
            <div class="admin-stat-label">Đang hoạt động</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-warning">
            <i class="bi bi-star-fill"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e(number_format($stats['avgRating'], 1)); ?></div>
            <div class="admin-stat-label">Điểm TB</div>
        </div>
    </div>
</div>

<!-- Bảng danh sách -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-house-door me-2 text-primary"></i>Danh sách chỗ nghỉ
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="ps-4">Chỗ nghỉ</th>
                        <th>Địa chỉ</th>
                        <th class="text-center">Giá/đêm</th>
                        <th class="text-center">Đánh giá</th>
                        <th class="text-center">Trạng thái duyệt</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $homestays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $homestay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $primaryImage = $homestay->images->where('is_primary', true)->first() ?? $homestay->images->first();
                            $thumb = $primaryImage ? asset('storage/' . $primaryImage->image_url) : asset('images/no-image.png');
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="admin-thumbnail me-3">
                                        <?php if($primaryImage): ?>
                                            <img src="<?php echo e($thumb); ?>" class="w-100 h-100 object-fit-cover">
                                        <?php else: ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image"></i></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="fw-bold text-dark small text-truncate" style="max-width: 180px;"><?php echo e($homestay->title); ?></div>
                                </div>
                            </td>
                            <td><?php echo e($homestay->province); ?><br><small class="text-muted"><?php echo e($homestay->ward); ?></small></td>
                            <td class="text-center">
                                <?php if($homestay->discounted_price < $homestay->price_per_night): ?>
                                    <div class="fw-bold text-success"><?php echo e(number_format($homestay->discounted_price)); ?>đ</div>
                                    <small class="text-muted text-decoration-line-through"><?php echo e(number_format($homestay->price_per_night)); ?>đ</small>
                                <?php else: ?>
                                    <div class="fw-bold"><?php echo e(number_format($homestay->price_per_night)); ?>đ</div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-star-fill text-warning" style="font-size: 0.75rem;"></i>
                                    <span><?php echo e(number_format($homestay->reviews_avg_rating ?? 0, 1)); ?></span>
                                    <small class="text-muted">(<?php echo e($homestay->reviews_count ?? 0); ?>)</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if($homestay->is_approved): ?>
                                    <span class="admin-badge admin-badge-success">Đã duyệt</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge-pending">Chờ duyệt</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="admin-actions d-flex gap-1 justify-content-center">
                                    <a href="<?php echo e(route('host.homestays.edit', $homestay)); ?>" class="admin-action-btn" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('host.homestays.destroy', $homestay)); ?>" method="POST" onsubmit="return confirm('Xóa homestay này?')">
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
                        <tr><td colspan="8" class="text-center text-muted py-5">Bạn chưa có homestay nào. Hãy tạo mới.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-center">
            <?php echo e($homestays->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/homestays/index.blade.php ENDPATH**/ ?>