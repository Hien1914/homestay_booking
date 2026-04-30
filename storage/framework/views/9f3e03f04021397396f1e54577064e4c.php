

<?php $__env->startSection('title', $promotion ? 'Sửa mã giảm giá' : 'Tạo mã giảm giá'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Thiết lập mã giảm giá cho chỗ nghỉ của bạn.</p>
    </div>
    <div class="admin-page-actions">
        <a href="<?php echo e(route('host.promotions.index')); ?>" class="admin-btn admin-btn-outline">
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>
    </div>
</div>

<?php if($errors->any()): ?>
    <div class="admin-alert admin-alert-danger mb-4">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-ticket-perforated me-2 text-primary"></i>Thông tin mã giảm
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo e($promotion ? route('host.promotions.update', $promotion) : route('host.promotions.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php if($promotion): ?>
                <?php echo method_field('PUT'); ?>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-12">
                    <label for="title" class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo e(old('title', $promotion?->title)); ?>" required placeholder="Ví dụ: Giảm giá mùa hè">
                </div>

                <div class="col-12">
                    <label for="discount_percent" class="form-label fw-bold">Phần trăm giảm <span class="text-danger">*</span></label>
                    <input type="number" id="discount_percent" name="discount_percent" class="form-control" min="1" max="100" value="<?php echo e(old('discount_percent', $promotion?->discount_percent)); ?>" required placeholder="Ví dụ: 10">
                </div>

                <div class="col-12">
                    <label for="start_date" class="form-label fw-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo e(old('start_date', $promotion?->start_date?->format('Y-m-d'))); ?>" required>
                </div>

                <div class="col-12">
                    <label for="end_date" class="form-label fw-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo e(old('end_date', $promotion?->end_date?->format('Y-m-d'))); ?>" required>
                </div>

                <div class="col-12">
                    <label for="min_nights" class="form-label fw-bold">Số đêm tối thiểu</label>
                    <input type="number" id="min_nights" name="min_nights" class="form-control" min="1" value="<?php echo e(old('min_nights', $promotion?->min_nights ?? 1)); ?>" placeholder="Ví dụ: 2">
                </div>

                <div class="col-12">
                    <label for="is_active" class="form-label fw-bold">Trạng thái</label>
                    <select id="is_active" name="is_active" class="form-select">
                        <option value="1" <?php if((int) old('is_active', $promotion?->is_active ? 1 : 0) === 1): echo 'selected'; endif; ?>>Kích hoạt</option>
                        <option value="0" <?php if((int) old('is_active', $promotion?->is_active ? 1 : 0) === 0): echo 'selected'; endif; ?>>Tạm tắt</option>
                    </select>
                </div>
            </div>

            <div class="admin-form-actions mt-4 d-flex justify-content-end gap-2">
                <a href="<?php echo e(route('host.promotions.index')); ?>" class="admin-filter-clear-btn px-4">Hủy bỏ</a>
                <button type="submit" class="admin-create-btn px-4">
                    <i class="bi bi-check-circle me-2"></i><?php echo e($promotion ? 'Cập nhật' : 'Tạo mới'); ?> mã giảm giá
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/promotions/form.blade.php ENDPATH**/ ?>