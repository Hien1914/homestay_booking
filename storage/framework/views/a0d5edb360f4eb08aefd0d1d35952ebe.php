

<?php $__env->startSection('title', 'Quản lý đánh giá'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .admin-reviews-table {
        table-layout: fixed;
    }
    .admin-reviews-table th,
    .admin-reviews-table td {
        width: calc(100% / 7);
    }
    .admin-reviews-table .admin-review-comment-cell {
        text-align: left !important;
        white-space: pre-wrap;
        word-break: break-word;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Xem tất cả đánh giá của người dùng trong hệ thống</p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-3">
    <div class="card-body p-4">
        <form method="GET" action="<?php echo e(route('admin.reviews')); ?>" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-bold text-secondary">Từ ngày</label>
                <input type="date" name="from_date" class="form-control" value="<?php echo e($fromDate); ?>">
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold text-secondary">Đến ngày</label>
                <input type="date" name="to_date" class="form-control" value="<?php echo e($toDate); ?>">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                <a href="<?php echo e(route('admin.reviews')); ?>" class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-star me-2 text-primary"></i>Danh sách đánh giá
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-wrap">
            <table class="admin-table admin-reviews-table">
                <thead>
                    <tr>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th class="text-center">Homestay</th>
                        <th class="text-center">Điểm</th>
                        <th>Nội dung đánh giá</th>
                        <th class="text-center">Thời gian</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-bold"><?php echo e($review->user?->full_name ?? '-'); ?></td>
                            <td><?php echo e($review->user?->email ?? '-'); ?></td>
                            <td class="text-center"><?php echo e($review->homestay?->title ?? '-'); ?></td>
                            <td class="text-center">
                                <div class="admin-rating justify-content-center">
                                    <?php for($i = 0; $i < (int) $review->rating; $i++): ?>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    <?php endfor; ?>
                                    <?php for($i = (int) $review->rating; $i < 5; $i++): ?>
                                        <i class="bi bi-star text-warning"></i>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td class="admin-review-comment-cell"><?php echo e($review->comment ?: '-'); ?></td>
                            <td class="text-center small"><?php echo e(optional($review->created_at)->format('H:i d/m/Y')); ?></td>
                            <td class="text-center">
                                <form method="POST" action="<?php echo e(route('admin.reviews.destroy', $review)); ?>" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="admin-action-btn admin-action-btn-danger" title="Xóa đánh giá" aria-label="Xóa đánh giá">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-star"></i> Chưa có đánh giá nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <?php echo e($reviews->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/reviews.blade.php ENDPATH**/ ?>