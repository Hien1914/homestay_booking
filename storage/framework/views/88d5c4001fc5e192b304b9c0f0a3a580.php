<?php $__env->startSection('title', 'Đánh giá & Bình luận'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Xem phản hồi của khách hàng về các chỗ nghỉ của bạn</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-chat-left-text-fill me-2"></i>Tất cả đánh giá</h3>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Khách hàng</th>
                        <th>Chỗ nghỉ</th>
                        <th>Đánh giá</th>
                        <th>Bình luận</th>
                        <th>Ngày gửi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="admin-user-avatar-sm me-2">
                                        <?php echo e(substr($review->user->full_name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <div class="fw-bold"><?php echo e($review->user->full_name); ?></div>
                                        <small class="text-muted"><?php echo e($review->user->email); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="<?php echo e(route('homestay.show', $review->homestay->slug)); ?>" target="_blank">
                                    <?php echo e($review->homestay->title); ?>

                                </a>
                            </td>
                            <td>
                                <div class="text-warning">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?php echo e($i <= $review->rating ? '-fill' : ''); ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted">(<?php echo e($review->rating); ?>/5)</small>
                            </td>
                            <td>
                                <div class="admin-review-text">
                                    <?php echo e($review->comment); ?>

                                </div>
                            </td>
                            <td><small><?php echo e($review->created_at->format('d/m/Y H:i')); ?></small></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Chưa có đánh giá nào.</td>
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

<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/reviews/index.blade.php ENDPATH**/ ?>