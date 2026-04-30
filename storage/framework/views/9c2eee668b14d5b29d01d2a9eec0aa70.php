<?php $__env->startSection('title', 'Đánh giá & Bình luận'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Xem phản hồi của khách hàng về các chỗ nghỉ của bạn</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-chat-left-text-fill me-2 text-primary"></i>Tất cả đánh giá
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="ps-4">Khách hàng</th>
                        <th>Email</th>
                        <th>Chỗ nghỉ</th>
                        <th class="text-center">Đánh giá</th>
                        <th>Bình luận</th>
                        <th class="text-center">Ngày gửi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?php echo e($review->user->full_name); ?></div>
                            </td>
                            <td>
                                <div class="text-muted small"><?php echo e($review->user->email); ?></div>
                            </td>
                            <td>
                                <div class="small text-truncate">
                                    <a href="<?php echo e(route('homestay.show', $review->homestay->slug)); ?>" target="_blank" class="text-decoration-none">
                                        <?php echo e($review->homestay->title); ?>

                                    </a>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="text-warning small mb-1">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?php echo e($i <= $review->rating ? '-fill' : ''); ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td>
                                <div class="admin-review-text small text-muted mx-auto" style="max-width: 250px; white-space: normal;">
                                    <?php echo e($review->comment); ?>

                                </div>
                            </td>
                            <td class="text-center"><small class="text-muted"><?php echo e($review->created_at->format('d/m/Y')); ?></small></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Chưa có đánh giá nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-center">
            <?php echo e($reviews->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/reviews.blade.php ENDPATH**/ ?>