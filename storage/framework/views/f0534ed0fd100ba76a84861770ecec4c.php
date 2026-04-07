<?php $__env->startSection('title', isset($destination) ? 'Chỉnh sửa điểm đến' : 'Tạo điểm đến mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo e(isset($destination) ? 'Chỉnh sửa điểm đến' : 'Tạo điểm đến mới'); ?></h1>
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

    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="bi bi-geo-alt me-2"></i>Thông tin điểm đến</h3>
        </div>
        <div class="admin-card-body">
            <div class="admin-form-group">
                <label for="name">Tên điểm đến <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="admin-form-control" 
                    value="<?php echo e(old('name', $destination->name ?? '')); ?>" 
                    placeholder="VD: Đà Lạt, Hạ Long..." required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="admin-form-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="admin-form-group">
                <label for="slug">Slug <span class="text-danger">*</span></label>
                <input type="text" id="slug" name="slug" class="admin-form-control" 
                    value="<?php echo e(old('slug', $destination->slug ?? '')); ?>" 
                    placeholder="da-lat, ha-long..." required>
                <small class="text-muted">URL-friendly identifier (không tự động generate)</small>
                <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="admin-form-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="admin-form-group">
                <label for="description">Mô tả ngắn</label>
                <textarea id="description" name="description" class="admin-form-control" rows="3" placeholder="Mô tả về điểm đến..."><?php echo e(old('description', $destination->description ?? '')); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="admin-form-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="admin-form-group">
                <label for="image">Ảnh đại diện <span class="text-danger">*</span></label>
                <div class="admin-upload-zone" id="image-upload-zone">
                    <input type="file" id="image" name="image" class="admin-file-input" accept="image/*" <?php echo e(!isset($destination) ? 'required' : ''); ?>>
                    <div class="admin-upload-placeholder">
                        <i class="bi bi-image"></i>
                        <p>Kéo thả ảnh hoặc nhấn để chọn</p>
                        <small>Tối đa 5MB. JPG, PNG, WEBP</small>
                    </div>
                    <div class="admin-upload-preview" id="image-preview" style="display: none;">
                        <img src="" alt="Preview">
                        <button type="button" class="admin-preview-remove" onclick="removeImagePreview()">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
                <?php if(isset($destination) && $destination->image): ?>
                    <div class="admin-current-image mt-2">
                        <img src="<?php echo e(asset('storage/' . $destination->image)); ?>" alt="Ảnh hiện tại">
                        <span class="admin-badge admin-badge-primary">Ảnh hiện tại</span>
                    </div>
                <?php endif; ?>
                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="admin-form-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="admin-form-actions mt-4">
        <a href="<?php echo e(route('admin.destinations')); ?>" class="admin-btn admin-btn-outline">Hủy</a>
        <button type="submit" class="admin-btn admin-btn-primary">
            <i class="bi bi-check-lg"></i>
            <?php echo e(isset($destination) ? 'Cập nhật' : 'Tạo mới'); ?>

        </button>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.admin-form-group {
    margin-bottom: 15px;
}

.admin-form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.admin-form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
}

.admin-form-control:focus {
    outline: none;
    border-color: #003b0d;
    box-shadow: 0 0 0 3px rgba(0, 59, 13, 0.1);
}

.admin-form-error {
    color: #dc3545;
    font-size: 12px;
    margin-top: 4px;
}

.admin-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* Upload Zone */
.admin-upload-zone {
    position: relative;
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    background: #fafafa;
    transition: all 0.3s;
    cursor: pointer;
}

.admin-upload-zone:hover {
    border-color: #003b0d;
    background: #f0f8f0;
}

.admin-file-input {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
}

.admin-upload-placeholder i {
    font-size: 48px;
    color: #003b0d;
    margin-bottom: 10px;
}

.admin-upload-placeholder p {
    margin: 8px 0 4px;
    font-weight: 500;
    color: #333;
}

.admin-upload-placeholder small {
    color: #666;
}

.admin-upload-preview {
    position: relative;
    max-width: 300px;
    margin: 0 auto;
}

.admin-upload-preview img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.admin-preview-remove {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #dc3545;
    font-size: 20px;
    transition: all 0.2s;
}

.admin-preview-remove:hover {
    background: #dc3545;
    color: white;
}

.admin-current-image {
    display: flex;
    align-items: center;
    gap: 10px;
}

.admin-current-image img {
    width: 120px;
    height: 80px;
    object-fit: cover;
    border-radius: 6px;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageUploadZone = document.getElementById('image-upload-zone');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const imagePlaceholder = imageUploadZone.querySelector('.admin-upload-placeholder');

    function handleImageFile(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.querySelector('img').src = e.target.result;
                imagePlaceholder.style.display = 'none';
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    imageInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            handleImageFile(this.files[0]);
        }
    });

    // Drag & Drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        imageUploadZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    imageUploadZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            imageInput.files = files;
            handleImageFile(files[0]);
        }
    }, false);
});

window.removeImagePreview = function() {
    document.getElementById('image').value = '';
    document.getElementById('image-preview').style.display = 'none';
    document.querySelector('.admin-upload-placeholder').style.display = 'block';
};
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/destinations/form.blade.php ENDPATH**/ ?>