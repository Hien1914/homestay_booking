

<?php $__env->startSection('title', isset($destination) ? 'Chỉnh sửa điểm đến' : 'Tạo điểm đến mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle"><?php echo e(isset($destination) ? 'Cập nhật thông tin điểm đến' : 'Điền thông tin để tạo điểm đến mới'); ?></p>
    </div>
    <div class="admin-page-actions">
        <a href="<?php echo e(route('admin.destinations')); ?>" class="admin-btn admin-btn-outline">
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>
    </div>
</div>

<?php if($errors->any()): ?>
    <div class="admin-alert admin-alert-danger mb-4">
        <strong>Lỗi validation:</strong>
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?php echo e(isset($destination) ? route('admin.destinations.update', $destination) : route('admin.destinations.store')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php if(isset($destination)): ?>
        <?php echo method_field('PUT'); ?>
    <?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-geo-alt me-2 text-primary"></i>Thông tin điểm đến
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo e(isset($destination) ? route('admin.destinations.update', $destination) : route('admin.destinations.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php if(isset($destination)): ?>
                <?php echo method_field('PUT'); ?>
            <?php endif; ?>

            <div class="mb-4">
                <label for="name" class="form-label small fw-bold text-secondary">Tên điểm đến <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control rounded-3 py-2" 
                    value="<?php echo e(old('name', $destination->name ?? '')); ?>" 
                    placeholder="VD: Đà Lạt, Hạ Long..." required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label for="slug" class="form-label small fw-bold text-secondary">Slug <span class="text-danger">*</span></label>
                <input type="text" id="slug" name="slug" class="form-control rounded-3 py-2 bg-light" 
                    value="<?php echo e(old('slug', $destination->slug ?? '')); ?>" 
                    placeholder="tu-dong-theo-ten-diem-den" readonly>
                <div class="form-text small">Slug được tự động tạo theo tên điểm đến để tối ưu SEO</div>
                <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label for="description" class="form-label small fw-bold text-secondary">Mô tả ngắn</label>
                <textarea id="description" name="description" class="form-control rounded-3" rows="4" placeholder="Mô tả về điểm đến..."><?php echo e(old('description', $destination->description ?? '')); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary">Ảnh đại diện <span class="text-danger">*</span></label>
                <div class="admin-upload-zone rounded-4 p-5 text-center border-2 border-dashed" id="image-upload-zone" style="cursor: pointer; border-color: #e2e8f0; background: #f8fafc; transition: all 0.2s;">
                    <input type="file" id="image" name="image" class="d-none" accept="image/*" <?php echo e(!isset($destination) ? 'required' : ''); ?>>
                    <div class="admin-upload-placeholder" id="upload-placeholder">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                            <i class="bi bi-cloud-arrow-up fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Nhấn để tải ảnh lên</h6>
                        <p class="text-muted small mb-0">Hoặc kéo và thả file vào đây</p>
                        <small class="text-muted mt-2 d-block">Định dạng: JPG, PNG, WEBP (Tối đa 5MB)</small>
                    </div>
                    <div class="admin-upload-preview position-relative d-none" id="image-preview">
                        <img src="" alt="Preview" class="img-fluid rounded-3 shadow-sm" style="max-height: 400px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle shadow" onclick="removeImagePreview(event)">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>

                <?php if(isset($destination) && $destination->image): ?>
                    <div class="mt-3 p-2 bg-light rounded-3 border d-inline-block" id="current-image-container">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?php echo e(asset('storage/' . $destination->image)); ?>" class="rounded-2" style="width: 80px; height: 50px; object-fit: cover;">
                            <div>
                                <div class="small fw-bold text-dark">Ảnh hiện tại</div>
                                <div class="text-muted" style="font-size: 10px;">Đang được sử dụng trên hệ thống</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="admin-form-actions d-flex justify-content-end gap-2 pt-3 border-top mt-5">
                <a href="<?php echo e(route('admin.destinations')); ?>" class="admin-filter-clear-btn text-decoration-none d-flex align-items-center px-4">Hủy</a>
                <button type="submit" class="admin-create-btn px-4">
                    <i class="bi bi-check-lg me-2"></i>
                    <?php echo e(isset($destination) ? 'Cập nhật điểm đến' : 'Tạo điểm đến ngay'); ?>

                </button>
            </div>
        </form>
    </div>
</div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin/destinations-form.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/admin/destinations-form.js')); ?>"></script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/destinations/form.blade.php ENDPATH**/ ?>