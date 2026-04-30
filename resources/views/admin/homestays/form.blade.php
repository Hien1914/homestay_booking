@extends('admin.layout.app')

@section('title', isset($homestay) ? 'Chỉnh sửa Homestay' : 'Tạo Homestay mới')

@php
    $isEdit = isset($homestay) && $homestay;
    $formAction = $isEdit
        ? route('admin.homestays.update', $homestay)
        : route('admin.homestays.store');
    $submitLabel = $isEdit ? 'Cập nhật' : 'Gửi duyệt';
    $primaryImage = $isEdit ? $homestay->images->where('is_primary', true)->first() : null;
    $roomImages = $isEdit ? $homestay->images->where('is_primary', false) : collect();
    $selectedAmenities = $isEdit ? $homestay->amenities->pluck('id')->toArray() : [];
@endphp

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">{{ $isEdit ? 'Cập nhật thông tin homestay' : 'Điền thông tin để tạo homestay mới. Sau khi gửi, homestay sẽ chờ duyệt từ admin.' }}</p>
    </div>
</div>

@if(session('error'))
    <div class="admin-alert admin-alert-danger mb-4">
        <strong>Lỗi:</strong> {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="admin-alert admin-alert-danger mb-4">
        <strong>Lỗi validation:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" id="homestay-form" class="homestay-form">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="mb-0 fw-bold h6">
                <i class="bi bi-info-circle me-2 text-primary"></i>Thông tin cơ bản
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-12">
                    <label for="title" class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $homestay->title ?? '') }}" class="form-control" placeholder="VD: Villa Đà Lạt view đồi thông" required>
                </div>

                <div class="col-12">
                    <label for="slug" class="form-label fw-bold">Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $homestay->slug ?? '') }}" class="form-control bg-light" placeholder="Tự động tạo từ tiêu đề" readonly>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label fw-bold">Mô tả <span class="text-danger">*</span></label>
                    <textarea id="description" name="description" class="form-control" rows="5" placeholder="Mô tả chi tiết về homestay (tối thiểu 100 ký tự)" required minlength="100">{{ old('description', $homestay->description ?? '') }}</textarea>
                    <div class="form-text mt-2">Tối thiểu 100 ký tự. Hiện tại: <span id="desc-count" class="fw-bold">0</span> ký tự</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Cột trái: Vị trí -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-header bg-white py-3 border-light-subtle">
                    <h5 class="mb-0 fw-bold h6">
                        <i class="bi bi-geo-alt me-2 text-primary"></i>Vị trí
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="destination_id" class="form-label fw-bold">Điểm đến</label>
                            <select id="destination_id" name="destination_id" class="form-select">
                                <option value="">-- Chọn điểm đến (hoặc nhập mới) --</option>
                                @foreach($destinations ?? [] as $destination)
                                    <option value="{{ $destination->id }}" @selected(old('destination_id', $homestay->destination_id ?? '') == $destination->id)>
                                        {{ $destination->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="destination_custom" class="form-label fw-bold">Tên điểm đến (nếu tạo mới)</label>
                            <input type="text" id="destination_custom" name="destination_custom" value="{{ old('destination_custom', '') }}" class="form-control" placeholder="VD: Đà Lạt, Hội An...">
                        </div>

                        <div class="col-md-6">
                            <label for="province" class="form-label fw-bold">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                            <select id="province" name="province" class="form-select" required>
                                <option value="">-- Chọn tỉnh/thành phố --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="ward" class="form-label fw-bold">Phường/Xã <span class="text-danger">*</span></label>
                            <select id="ward" name="ward" class="form-select" required>
                                <option value="">-- Chọn phường/xã --</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label fw-bold">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address', $homestay->address ?? '') }}" class="form-control" placeholder="Số nhà, tên đường, khu vực..." required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột phải: Giá & Sức chứa -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-header bg-white py-3 border-light-subtle">
                    <h5 class="mb-0 fw-bold h6">
                        <i class="bi bi-cash me-2 text-primary"></i>Giá & Sức chứa
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="price_per_night" class="form-label fw-bold">Giá/đêm (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" id="price_per_night" name="price_per_night" value="{{ old('price_per_night', $homestay->price_per_night ?? '') }}" class="form-control" min="0" step="1000" placeholder="500000" required>
                        </div>

                        <div class="col-12">
                            <label for="max_guests" class="form-label fw-bold">Số khách tối đa <span class="text-danger">*</span></label>
                            <input type="number" id="max_guests" name="max_guests" value="{{ old('max_guests', $homestay->max_guests ?? 2) }}" class="form-control" min="1" max="50" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="mb-0 fw-bold h6">
                <i class="bi bi-door-open me-2 text-primary"></i>Số lượng phòng trong homestay
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @php
                    $roomTypes = \App\Models\HomestayRoom::ROOM_TYPES;
                @endphp
                @foreach($roomTypes as $type => $label)
                    @php
                        $room = $isEdit ? $homestay->rooms->where('feature_type', $type)->first() : null;
                        $quantity = $room?->quantity ?? 0;
                    @endphp
                    <div class="col-md-3">
                        <label for="room_{{ $type }}" class="form-label fw-bold">{{ $label }}</label>
                        <input type="number" id="room_{{ $type }}" name="rooms[{{ $type }}]" value="{{ old("rooms.{$type}", $quantity) }}" class="form-control" min="0" max="100" placeholder="0">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="mb-0 fw-bold h6">
                <i class="bi bi-check2-square me-2 text-primary"></i>Tiện nghi
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="admin-amenities-grid">
                @foreach($amenities as $amenity)
                    @php
                        $selectedAmenity = $isEdit ? $homestay->amenities->firstWhere('id', $amenity->id) : null;
                        $isSelected = $selectedAmenity ? true : false;
                        $quantity = $selectedAmenity?->pivot?->quantity ?? 1;
                        $oldAmenities = old('amenities', []);
                        $isChecked = in_array($amenity->id, $oldAmenities) ? true : $isSelected;
                        $oldQuantity = old("amenity_quantities.{$amenity->id}", $quantity);
                    @endphp
                    <div class="amenity-item">
                        <label class="amenity-checkbox">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" 
                                class="amenity-checkbox-input" @checked($isChecked)>
                            <span class="amenity-name">{{ $amenity->name }}</span>
                        </label>
                        <input type="number" name="amenity_quantities[{{ $amenity->id }}]" 
                            class="amenity-quantity-input" value="{{ $oldQuantity }}" min="1" max="100" 
                            placeholder="Số lượng" @disabled(!$isChecked)>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="mb-0 fw-bold h6">
                <i class="bi bi-image me-2 text-primary"></i>Hình ảnh
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-12">
                    <label for="cover_image" class="form-label fw-bold">Ảnh chính {!! !$isEdit ? '<span class="text-danger">*</span>' : '<span class="text-muted fw-normal small"> (tùy chọn)</span>' !!}</label>
                    <div class="admin-upload-zone" id="cover-upload-zone">
                        <input type="file" id="cover_image" name="cover_image" class="admin-file-input" accept="image/*" {{ !$isEdit ? 'required' : '' }}>
                        <div class="admin-upload-placeholder">
                            <i class="bi bi-cloud-upload fs-2"></i>
                            <p class="mb-1">Kéo thả ảnh hoặc nhấn để chọn</p>
                            <small class="text-muted">Tối đa 5MB. JPG, PNG, WEBP</small>
                        </div>
                        <div class="admin-upload-preview" id="cover-preview" style="display: none;">
                            <img src="" alt="Preview">
                            <button type="button" class="admin-preview-remove" onclick="removeCoverPreview()">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    </div>
                    @if($primaryImage)
                        <div class="admin-current-image mt-3">
                            <img src="{{ asset('storage/' . $primaryImage->image_url) }}" alt="Ảnh hiện tại" class="rounded shadow-sm" style="max-height: 150px;">
                            <div class="mt-2"><span class="badge bg-primary">Ảnh hiện tại</span></div>
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <label for="room_images" class="form-label fw-bold">Ảnh phòng (nhiều ảnh)</label>
                    <div class="admin-upload-zone admin-upload-multiple" id="room-upload-zone">
                        <input type="file" id="room_images" name="room_images[]" class="admin-file-input" accept="image/*" multiple>
                        <div class="admin-upload-placeholder">
                            <i class="bi bi-images fs-2"></i>
                            <p class="mb-1">Kéo thả nhiều ảnh hoặc nhấn để chọn</p>
                            <small class="text-muted">Tối đa 5MB/ảnh</small>
                        </div>
                    </div>
                    <div class="admin-image-preview-grid mt-3" id="room-preview-grid"></div>
                    
                    @if($roomImages->count() > 0)
                        <div class="admin-image-grid mt-4">
                            <div class="row g-3">
                                @foreach($roomImages as $image)
                                    <div class="col-md-2 col-sm-4 col-6">
                                        <div class="admin-image-item position-relative" data-image-id="{{ $image->id }}">
                                            <img src="{{ asset('storage/' . $image->image_url) }}" alt="Ảnh phòng" class="img-fluid rounded shadow-sm">
                                            <button type="button" class="admin-image-delete position-absolute top-0 end-0 m-1 btn btn-danger btn-sm rounded-circle" onclick="deleteImage(this, {{ $image->id }})">
                                                <i class="bi bi-x"></i>
                                            </button>
                                            <input type="hidden" name="delete_images[]" value="{{ $image->id }}" class="d-none">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="admin-filter-btn px-4">
            <i class="bi bi-check-lg me-2"></i>{{ $submitLabel }}
        </button>
        <a href="{{ route('admin.homestays') }}" class="admin-filter-clear-btn px-4">Hủy bỏ</a>
    </div>
</form>


@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/homestays-form.css') }}">
<style>
    .homestay-form {
        max-width: 100%;
    }

    .homestay-two-column {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .admin-rooms-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .room-input-group {
        margin-bottom: 0;
    }

    .admin-amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 12px;
    }

    .amenity-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        background: #f8f9fa;
    }

    .amenity-item:hover {
        border-color: #dee2e6;
        background: #f0f4f8;
    }

    .amenity-checkbox {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        cursor: pointer;
    }

    .amenity-checkbox-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .amenity-name {
        color: #333;
        font-weight: 500;
    }

    .amenity-quantity-input {
        width: 80px;
        padding: 6px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .amenity-quantity-input:disabled {
        background: #e9ecef;
        color: #999;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .homestay-two-column {
            grid-template-columns: 1fr;
        }

        .admin-amenities-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
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
    const currentWard = "{{ old('ward', $homestay->ward ?? '') }}";
    
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

            const currentProvince = "{{ old('province', $homestay->province ?? '') }}";
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
            const reader = new FileReader();
            reader.onload = function(e) {
                coverPreview.querySelector('img').src = e.target.result;
                coverPlaceholder.style.display = 'none';
                coverPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    coverInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            handleCoverFile(this.files[0]);
        }
    });

    // Drag & Drop for cover
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        coverUploadZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

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

    window.removeCoverPreview = function() {
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
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'admin-image-preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" onclick="removeRoomFile(${index})">
                        <i class="bi bi-x-circle"></i>
                    </button>
                `;
                roomPreviewGrid.appendChild(div);
            };
            reader.readAsDataURL(file);
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
        if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
            item.style.opacity = '0.5';
            item.style.pointerEvents = 'none';
            button.disabled = true;
        }
    };
});
</script>
@endpush

