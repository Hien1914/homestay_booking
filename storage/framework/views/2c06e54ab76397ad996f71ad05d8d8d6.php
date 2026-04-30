

<?php $__env->startSection('title', isset($homestay) ? 'Chỉnh sửa chỗ nghỉ' : 'Thêm chỗ nghỉ mới'); ?>

<?php
    $isEdit = isset($homestay) && $homestay;
    $formAction = $isEdit
        ? route('host.homestays.update', $homestay)
        : route('host.homestays.store');
    $submitLabel = $isEdit ? 'Cập nhật' : 'Tạo mới';
    $primaryImage = $isEdit ? $homestay->images->where('is_primary', true)->first() : null;
    $roomImages = $isEdit ? $homestay->images->where('is_primary', false) : collect();
    $selectedAmenities = $isEdit ? $homestay->amenities->pluck('id')->toArray() : [];
?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle"><?php echo e($isEdit ? 'Cập nhật thông tin chỗ nghỉ' : 'Điền thông tin để tạo chỗ nghỉ mới. Sau khi gửi, chỗ nghỉ sẽ chờ admin duyệt.'); ?></p>
    </div>
</div>

<?php if(session('error')): ?>
    <div class="admin-alert admin-alert-danger mb-4">
        <strong>Lỗi:</strong> <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>

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

<div class="p-2">
    <form action="<?php echo e($formAction); ?>" method="POST" enctype="multipart/form-data" id="homestay-form" class="homestay-form">
        <?php echo csrf_field(); ?>
        <?php if($isEdit): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <!-- Thông tin cơ bản -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-light-subtle">
                <h5 class="mb-0 fw-bold h6 text-dark">
                    <i class="bi bi-info-circle me-2 text-primary"></i>Thông tin cơ bản
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-12">
                        <label for="title" class="form-label fw-bold">Tiêu đề homestay <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" value="<?php echo e(old('title', $homestay->title ?? '')); ?>" class="form-control rounded-3" placeholder="VD: Villa Đà Lạt view đồi thông" required>
                    </div>

                    <div class="col-12">
                        <label for="slug" class="form-label fw-bold">Đường dẫn (Slug)</label>
                        <input type="text" id="slug" name="slug" value="<?php echo e(old('slug', $homestay->slug ?? '')); ?>" class="form-control bg-light rounded-3" placeholder="Tự động tạo từ tiêu đề" readonly>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label fw-bold">Mô tả chi tiết <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-control rounded-3" rows="8" placeholder="Mô tả chi tiết về homestay (tối thiểu 100 ký tự)" required minlength="100"><?php echo e(old('description', $homestay->description ?? '')); ?></textarea>
                        <div class="form-text mt-2 d-flex justify-content-between">
                            <span>Tối thiểu 100 ký tự để khách hàng hiểu rõ hơn về chỗ nghỉ của bạn.</span>
                            <span>Hiện tại: <span id="desc-count" class="fw-bold">0</span> ký tự</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Cột trái: Vị trí -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-light-subtle">
                        <h5 class="mb-0 fw-bold h6 text-dark">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>Vị trí địa lý
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="destination_id" class="form-label fw-bold">Điểm đến hiện có</label>
                                <select id="destination_id" name="destination_id" class="form-select rounded-3">
                                    <option value="">-- Chọn điểm đến đã có --</option>
                                    <?php $__currentLoopData = $destinations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($destination->id); ?>" <?php if(old('destination_id', $homestay->destination_id ?? '') == $destination->id): echo 'selected'; endif; ?>>
                                            <?php echo e($destination->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="destination_custom" class="form-label fw-bold">Hoặc nhập điểm đến mới</label>
                                <input type="text" id="destination_custom" name="destination_custom" value="<?php echo e(old('destination_custom', '')); ?>" class="form-control rounded-3" placeholder="VD: Đà Lạt, Hội An...">
                            </div>

                            <div class="col-md-6">
                                <label for="province" class="form-label fw-bold">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select id="province" name="province" class="form-select rounded-3" required>
                                    <option value="">-- Chọn tỉnh/TP --</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="ward" class="form-label fw-bold">Phường/Xã <span class="text-danger">*</span></label>
                                <select id="ward" name="ward" class="form-select rounded-3" required>
                                    <option value="">-- Chọn phường/xã --</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label fw-bold">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                                <input type="text" id="address" name="address" value="<?php echo e(old('address', $homestay->address ?? '')); ?>" class="form-control rounded-3" placeholder="Số nhà, tên đường, khu vực..." required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Giá & Sức chứa -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-light-subtle">
                        <h5 class="mb-0 fw-bold h6 text-dark">
                            <i class="bi bi-cash-coin me-2 text-primary"></i>Giá & Sức chứa
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-12">
                                <label for="price_per_night" class="form-label fw-bold">Giá thuê 1 đêm (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" id="price_per_night" name="price_per_night" value="<?php echo e(old('price_per_night', $homestay->price_per_night ?? '')); ?>" class="form-control rounded-3" min="0" step="1000" placeholder="500.000" required>
                                <div class="form-text mt-1 text-info small"><i class="bi bi-info-circle me-1"></i>Giá gốc chưa trừ khuyến mãi.</div>
                            </div>

                            <div class="col-12">
                                <label for="max_guests" class="form-label fw-bold">Sức chứa tối đa (Khách) <span class="text-danger">*</span></label>
                                <input type="number" id="max_guests" name="max_guests" value="<?php echo e(old('max_guests', $homestay->max_guests ?? 2)); ?>" class="form-control rounded-3" min="1" max="50" required>
                            </div>

                            <div class="col-12">
                                <label for="promotion_id" class="form-label fw-bold">Chương trình khuyến mãi</label>
                                <select id="promotion_id" name="promotion_id" class="form-select rounded-3">
                                    <option value="">-- Không áp dụng khuyến mãi --</option>
                                    <?php $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($promo->id); ?>" <?php if(old('promotion_id', $homestay->promotion_id ?? '') == $promo->id): echo 'selected'; endif; ?>>
                                            <?php echo e($promo->title); ?> (Giảm <?php echo e($promo->discount_percent); ?>%)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cấu trúc phòng -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-light-subtle">
                <h5 class="mb-0 fw-bold h6 text-dark">
                    <i class="bi bi-door-open me-2 text-primary"></i>Cấu trúc và số lượng phòng
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php
                        $roomTypes = \App\Models\HomestayRoom::ROOM_TYPES;
                    ?>
                    <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $room = $isEdit ? $homestay->rooms->where('feature_type', $type)->first() : null;
                            $quantity = $room?->quantity ?? 0;
                        ?>
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <label for="room_<?php echo e($type); ?>" class="form-label fw-bold small text-secondary mb-2"><?php echo e($label); ?></label>
                                <input type="number" id="room_<?php echo e($type); ?>" name="rooms[<?php echo e($type); ?>]" value="<?php echo e(old("rooms.{$type}", $quantity)); ?>" class="form-control rounded-2" min="0" max="100" placeholder="0">
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Tiện nghi -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-light-subtle">
                <h5 class="mb-0 fw-bold h6 text-dark">
                    <i class="bi bi-stars me-2 text-primary"></i>Tiện ích & Dịch vụ đi kèm
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
                    <?php $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $selectedAmenity = $isEdit ? $homestay->amenities->firstWhere('id', $amenity->id) : null;
                            $isSelected = $selectedAmenity ? true : false;
                            $quantity = $selectedAmenity?->pivot?->quantity ?? 1;
                            $oldAmenities = old('amenities', []);
                            $isChecked = in_array($amenity->id, $oldAmenities) ? true : $isSelected;
                            $oldQuantity = old("amenity_quantities.{$amenity->id}", $quantity);
                        ?>
                        <div class="col">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <label class="d-flex align-items-center gap-2 mb-2 cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="<?php echo e($amenity->id); ?>" 
                                        class="form-check-input amenity-checkbox-input" <?php if($isChecked): echo 'checked'; endif; ?>>
                                    <span class="fw-bold small text-secondary"><?php echo e($amenity->name); ?></span>
                                </label>
                                <input type="number" name="amenity_quantities[<?php echo e($amenity->id); ?>]" 
                                    class="form-control rounded-2 bg-white" value="<?php echo e($oldQuantity); ?>" min="1" max="100" 
                                    placeholder="SL: 1" <?php if(!$isChecked): echo 'disabled'; endif; ?>>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Hình ảnh -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-light-subtle">
                <h5 class="mb-0 fw-bold h6 text-dark">
                    <i class="bi bi-images me-2 text-primary"></i>Hình ảnh chỗ nghỉ
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-5">
                    <label for="cover_image" class="form-label fw-bold">Ảnh bìa chính <?php echo !$isEdit ? '<span class="text-danger">*</span>' : '<span class="text-muted fw-normal small"> (Nhấn vào ảnh để thay đổi)</span>'; ?></label>
                    <div class="admin-upload-zone rounded-4 mb-3" id="cover-upload-zone" style="height: 250px; cursor: pointer;">
                        <input type="file" id="cover_image" name="cover_image" class="admin-file-input" accept="image/*" <?php echo e(!$isEdit ? 'required' : ''); ?>>
                        <div class="admin-upload-placeholder" <?php echo $isEdit && $primaryImage ? 'style="display:none;"' : ''; ?>>
                            <i class="bi bi-cloud-arrow-up fs-2 text-primary"></i>
                            <p class="mb-1 fw-bold">Tải ảnh bìa lên</p>
                            <small class="text-muted">JPG, PNG, WEBP. Tối đa 5MB</small>
                        </div>
                        <div class="admin-upload-preview d-flex justify-content-center align-items-center w-100 h-100" id="cover-preview" <?php echo $isEdit && $primaryImage ? 'style="display:block;"' : 'style="display:none;"'; ?>>
                            <img src="<?php echo e($primaryImage ? asset('storage/' . $primaryImage->image_url) : ''); ?>" alt="Preview" class="rounded-4 h-100" style="width: auto; max-width: 100%; object-fit: contain; display: block;">
                            <button type="button" class="admin-preview-remove shadow-sm" onclick="removeCoverPreview(event)">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <label for="room_images" class="form-label fw-bold">Ảnh chi tiết / Ảnh phòng</label>
                    <div class="admin-upload-zone admin-upload-multiple rounded-4 mb-3" id="room-upload-zone" style="height: 180px;">
                        <input type="file" id="room_images" name="room_images[]" class="admin-file-input" accept="image/*" multiple>
                        <div class="admin-upload-placeholder">
                            <i class="bi bi-plus-square-dotted fs-2 text-primary"></i>
                            <p class="mb-1 fw-bold">Thêm nhiều ảnh chi tiết</p>
                            <small class="text-muted">Chọn hoặc kéo thả nhiều ảnh cùng lúc</small>
                        </div>
                    </div>
                    
                    <div class="admin-image-preview-flex mt-3 d-flex flex-wrap gap-3" id="room-preview-grid"></div>

                    <?php if($roomImages->count() > 0): ?>
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="fw-bold text-dark mb-3 small text-uppercase" style="letter-spacing: 0.5px;">Các ảnh chi tiết hiện có:</h6>
                            <div class="d-flex flex-wrap gap-3">
                                <?php $__currentLoopData = $roomImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="admin-image-item position-relative rounded-3 overflow-hidden shadow-sm border">
                                        <img src="<?php echo e(asset('storage/' . $image->image_url)); ?>" alt="Ảnh phòng" class="object-fit-contain" style="height: 120px; width: auto; max-width: 200px; display: block;">
                                        <button type="button" class="admin-image-delete btn btn-danger btn-sm rounded-circle shadow position-absolute" style="top:5px; right:5px;" onclick="deleteImage(this, <?php echo e($image->id); ?>)">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        <input type="hidden" name="delete_images[]" value="<?php echo e($image->id); ?>" class="d-none" disabled>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Nút hành động -->
        <div class="admin-form-actions d-flex justify-content-end gap-2 mb-5">
            <a href="<?php echo e(route('host.homestays.index')); ?>" class="admin-filter-clear-btn px-4 d-flex align-items-center">
                Hủy bỏ
            </a>
            <button type="submit" class="admin-create-btn px-5">
                <i class="bi bi-check-circle me-2"></i><?php echo e($submitLabel); ?> chỗ nghỉ
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin/homestays-form.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const descInput = document.getElementById('description');
    const descCount = document.getElementById('desc-count');
    const provinceSelect = document.getElementById('province');
    const wardSelect = document.getElementById('ward');

    // Amenity checkbox management
    const amenityCheckboxes = document.querySelectorAll('.amenity-checkbox-input');
    amenityCheckboxes.forEach(checkbox => {
        const amenityId = checkbox.value;
        const quantityInput = document.querySelector(`input[name="amenity_quantities[${amenityId}]"]`);
        if (quantityInput) {
            checkbox.addEventListener('change', function() {
                quantityInput.disabled = !this.checked;
            });
        }
    });

    // Auto generate slug from title
    if (titleInput && slugInput) {
        const syncSlug = function() {
            const slug = titleInput.value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            slugInput.value = slug;
        };
        titleInput.addEventListener('input', syncSlug);
        if (!slugInput.value) {
            syncSlug();
        }
    }

    // Character count for description
    if (descInput && descCount) {
        function updateCount() {
            descCount.textContent = descInput.value.length;
            descCount.style.color = descInput.value.length < 100 ? '#dc3545' : '#28a745';
        }
        descInput.addEventListener('input', updateCount);
        updateCount();
    }

    // Load provinces from API
    const currentWard = "<?php echo e(old('ward', $homestay->ward ?? '')); ?>";
    
    async function loadProvinces() {
        try {
            const response = await fetch('https://provinces.open-api.vn/api/v2/');
            const provinces = await response.json();
            provinceSelect.innerHTML = '<option value="">-- Chọn tỉnh/thành phố --</option>';
            provinces.forEach(p => {
                const option = document.createElement('option');
                option.value = p.name;
                option.dataset.provinceCode = p.code;
                option.textContent = p.name;
                provinceSelect.appendChild(option);
            });
            const currentProvince = "<?php echo e(old('province', $homestay->province ?? '')); ?>";
            if (currentProvince) {
                provinceSelect.value = currentProvince;
                const provinceCode = Array.from(provinceSelect.options)
                    .find(opt => opt.value === currentProvince)?.dataset.provinceCode;
                if (provinceCode) {
                    loadWards(provinceCode);
                }
            }
        } catch (error) {
            console.error('Lỗi tải tỉnh/thành phố:', error);
        }
    }

    async function loadWards(provinceCode) {
        try {
            const response = await fetch(`https://provinces.open-api.vn/api/v2/p/${provinceCode}?depth=2`);
            if (!response.ok) throw new Error('API response failed');
            const data = await response.json();
            wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
            if (data.wards && Array.isArray(data.wards)) {
                data.wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.name;
                    option.textContent = ward.name;
                    wardSelect.appendChild(option);
                });
            } else if (data.districts && Array.isArray(data.districts)) {
                data.districts.forEach(district => {
                    if (district.wards && Array.isArray(district.wards)) {
                        district.wards.forEach(ward => {
                            const option = document.createElement('option');
                            option.value = ward.name;
                            option.textContent = `${ward.name}, ${district.name}`;
                            wardSelect.appendChild(option);
                        });
                    }
                });
            }
            if (currentWard) {
                wardSelect.value = currentWard;
            }
        } catch (error) {
            console.error('Lỗi tải phường/xã:', error);
            wardSelect.innerHTML = '<option value="">-- Lỗi tải danh sách --</option>';
        }
    }

    provinceSelect.addEventListener('change', function() {
        if (this.value) {
            const provinceCode = Array.from(this.options)
                .find(opt => opt.value === this.value)?.dataset.provinceCode;
            if (provinceCode) {
                loadWards(provinceCode);
            }
        } else {
            wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
        }
    });

    loadProvinces();

    // Cover Image Upload
    const coverUploadZone = document.getElementById('cover-upload-zone');
    const coverInput = document.getElementById('cover_image');
    const coverPreview = document.getElementById('cover-preview');
    const coverPlaceholder = coverUploadZone.querySelector('.admin-upload-placeholder');

    function handleCoverFile(file) {
        if (file && file.type.startsWith('image/')) {
            const url = URL.createObjectURL(file);
            coverPreview.querySelector('img').src = url;
            coverPlaceholder.style.display = 'none';
            coverPreview.style.display = 'block';
        }
    }

    coverInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            handleCoverFile(this.files[0]);
        }
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        coverUploadZone.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        coverUploadZone.addEventListener(eventName, () => {
            coverUploadZone.classList.add('drag-over');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        coverUploadZone.addEventListener(eventName, () => {
            coverUploadZone.classList.remove('drag-over');
        }, false);
    });

    coverUploadZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            coverInput.files = files;
            handleCoverFile(files[0]);
        }
    }, false);

    window.removeCoverPreview = function(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        coverInput.value = '';
        coverPreview.style.display = 'none';
        coverPlaceholder.style.display = 'block';
    };

    // Room Images Upload
    const roomUploadZone = document.getElementById('room-upload-zone');
    const roomInput = document.getElementById('room_images');
    const roomPreviewGrid = document.getElementById('room-preview-grid');
    let roomFiles = [];

    function updateRoomPreviews() {
        roomPreviewGrid.innerHTML = '';
        roomFiles.forEach((file, index) => {
            if (file && file.type.startsWith('image/')) {
                const url = URL.createObjectURL(file);
                const div = document.createElement('div');
                div.className = 'admin-image-item position-relative rounded-3 overflow-hidden shadow-sm border';
                div.innerHTML = `
                    <img src="${url}" alt="Preview" class="object-fit-contain" style="height: 120px; width: auto; max-width: 200px; display: block;">
                    <button type="button" class="admin-image-delete btn btn-danger btn-sm rounded-circle shadow position-absolute" style="top:5px; right:5px;" onclick="removeRoomFile(${index})">
                        <i class="bi bi-x"></i>
                    </button>
                `;
                roomPreviewGrid.appendChild(div);
            }
        });
    }

    roomInput.addEventListener('change', function(e) {
        roomFiles = Array.from(this.files);
        updateRoomPreviews();
    });

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        roomUploadZone.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        roomUploadZone.addEventListener(eventName, () => {
            roomUploadZone.classList.add('drag-over');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        roomUploadZone.addEventListener(eventName, () => {
            roomUploadZone.classList.remove('drag-over');
        }, false);
    });

    roomUploadZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        roomFiles = Array.from(dt.files);
        const dataTransfer = new DataTransfer();
        roomFiles.forEach(file => dataTransfer.items.add(file));
        roomInput.files = dataTransfer.files;
        updateRoomPreviews();
    }, false);

    window.removeRoomFile = function(index) {
        roomFiles.splice(index, 1);
        const dataTransfer = new DataTransfer();
        roomFiles.forEach(file => dataTransfer.items.add(file));
        roomInput.files = dataTransfer.files;
        updateRoomPreviews();
    };

    window.deleteImage = function(button, imageId) {
        const item = button.closest('.admin-image-item');
        const input = item.querySelector('input[name="delete_images[]"]');
        
        if (input.disabled) {
            // Mark for deletion
            item.style.opacity = '0.3';
            item.classList.add('border', 'border-danger');
            input.disabled = false;
            button.innerHTML = '<i class="bi bi-arrow-counterclockwise"></i>';
            button.classList.replace('btn-danger', 'btn-warning');
        } else {
            // Restore
            item.style.opacity = '1';
            item.classList.remove('border', 'border-danger');
            input.disabled = true;
            button.innerHTML = '<i class="bi bi-x"></i>';
            button.classList.replace('btn-warning', 'btn-danger');
        }
    };
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/homestays/form.blade.php ENDPATH**/ ?>