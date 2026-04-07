@extends('admin.layout.app')

@section('title', isset($homestay) ? 'Chỉnh sửa Homestay' : 'Tạo Homestay mới')

@php
    $isEdit = isset($homestay) && $homestay;
    $formAction = $isEdit
        ? route('admin.homestays.update', $homestay)
        : route('admin.homestays.store');
    $submitLabel = $isEdit ? 'Cập nhật' : 'Tạo mới';
    $primaryImage = $isEdit ? $homestay->images->where('is_primary', true)->first() : null;
    $roomImages = $isEdit ? $homestay->images->where('is_primary', false) : collect();
    $selectedAmenities = $isEdit ? $homestay->amenities->pluck('id')->toArray() : [];
@endphp

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">{{ $isEdit ? 'Chỉnh sửa Homestay' : 'Tạo Homestay mới' }}</h1>
        <p class="admin-page-subtitle">{{ $isEdit ? 'Cập nhật thông tin homestay' : 'Điền thông tin để tạo homestay mới' }}</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.homestays') }}" class="admin-btn admin-btn-outline">
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>
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
    <script>
        console.error('Validation errors:', @json($errors->all()));
    </script>
@endif

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" id="homestay-form">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="admin-form-grid">
        <!-- Thông tin cơ bản -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="bi bi-info-circle me-2"></i>Thông tin cơ bản</h3>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label for="room_code">Mã homestay</label>
                        <input type="text" id="room_code" name="room_code" value="{{ $generatedRoomCode }}" class="admin-form-control" readonly>
                        <small class="admin-form-hint">Mã tự động tạo</small>
                    </div>
                    <div class="admin-form-group">
                        <label for="type">Loại hình <span class="text-danger">*</span></label>
                        <select id="type" name="type" class="admin-form-control" required>
                            <option value="">-- Chọn loại hình --</option>
                            @foreach($homestayTypes as $value => $label)
                                <option value="{{ $value }}" @selected(old('type', $homestay->type ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $homestay->title ?? '') }}" class="admin-form-control" placeholder="VD: Villa Đà Lạt view đồi thông" required>
                </div>

                <div class="admin-form-group">
                    <label for="slug">Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $homestay->slug ?? '') }}" class="admin-form-control" readonly placeholder="Tự động tạo từ tiêu đề">
                    <small class="admin-form-hint">Slug được tự động tạo từ tiêu đề</small>
                </div>

                <div class="admin-form-group">
                    <label for="description">Mô tả <span class="text-danger">*</span></label>
                    <textarea id="description" name="description" class="admin-form-control" rows="6" placeholder="Mô tả chi tiết về homestay (tối thiểu 100 ký tự)" required minlength="100">{{ old('description', $homestay->description ?? '') }}</textarea>
                    <small class="admin-form-hint">Tối thiểu 100 ký tự. Hiện tại: <span id="desc-count">0</span> ký tự</small>
                </div>
            </div>
        </div>

        <!-- Vị trí -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="bi bi-geo-alt me-2"></i>Vị trí</h3>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-row-location">
                    <div class="admin-form-group">
                        <label for="destination_id">Điểm đến</label>
                        <select id="destination_id" name="destination_id" class="admin-form-control">
                            <option value="">-- Chọn điểm đến --</option>
                            @foreach($destinations ?? [] as $destination)
                                <option value="{{ $destination->id }}" @selected(old('destination_id', $homestay->destination_id ?? '') == $destination->id)>
                                    {{ $destination->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label for="province">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                        <select id="province" name="province" class="admin-form-control" required>
                            <option value="">-- Chọn tỉnh/thành phố --</option>
                        </select>
                    </div>
                </div>

                <div class="admin-form-row-location">
                    <div class="admin-form-group">
                        <label for="ward">Phường/Xã <span class="text-danger">*</span></label>
                        <select id="ward" name="ward" class="admin-form-control" required>
                            <option value="">-- Chọn phường/xã --</option>
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label for="address">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                        <input type="text" id="address" name="address" value="{{ old('address', $homestay->address ?? '') }}" class="admin-form-control" placeholder="Số nhà, đường, khu vực..." required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Giá & Sức chứa -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="bi bi-cash me-2"></i>Giá & Sức chứa</h3>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label for="price_per_night">Giá/đêm (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" id="price_per_night" name="price_per_night" value="{{ old('price_per_night', $homestay->price_per_night ?? '') }}" class="admin-form-control" min="0" step="1000" placeholder="500000" required>
                    </div>
                    <div class="admin-form-group">
                        <label for="max_guests">Số khách tối đa <span class="text-danger">*</span></label>
                        <input type="number" id="max_guests" name="max_guests" value="{{ old('max_guests', $homestay->max_guests ?? 2) }}" class="admin-form-control" min="1" max="50" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lịch đặt phòng -->
        @if($isEdit)
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="bi bi-calendar-check me-2"></i>Lịch đặt phòng</h3>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-group">
                    <label for="check-booking-calendar">Xem lịch đặt phòng</label>
                    <button type="button" id="check-booking-calendar" class="admin-btn admin-btn-outline">
                        <i class="bi bi-calendar me-2"></i>Mở lịch đặt phòng
                    </button>
                    <p class="admin-form-hint mt-2">
                        <span class="booking-legend">
                            <span class="legend-item"><span class="legend-color" style="background: #fff3cd;"></span>Ngày check-in</span>
                            <span class="legend-item"><span class="legend-color" style="background: #cfe2ff;"></span>Ngày check-out</span>
                        </span>
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Tiện nghi -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="bi bi-check2-square me-2"></i>Tiện nghi</h3>
            </div>
            <div class="admin-card-body">
                <div class="admin-amenities-grid">
                    @foreach($amenities as $amenity)
                        <label class="admin-amenity-item">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" 
                                @checked(in_array($amenity->id, old('amenities', $selectedAmenities)))>
                            <span>{{ $amenity->name }}</span>
                        </label>
                    @endforeach
                </div>
                @if($amenities->isEmpty())
                    <p class="text-muted">Chưa có tiện nghi nào. Vui lòng chạy seeder để tạo tiện nghi.</p>
                @endif
            </div>
        </div>

        <!-- Hình ảnh -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="bi bi-image me-2"></i>Hình ảnh</h3>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-group">
                    <label for="cover_image">Ảnh đại diện {!! !$isEdit ? '<span class="text-danger">*</span>' : '' !!}</label>
                    <div class="admin-upload-zone" id="cover-upload-zone">
                        <input type="file" id="cover_image" name="cover_image" class="admin-file-input" accept="image/*" {{ !$isEdit ? 'required' : '' }}>
                        <div class="admin-upload-placeholder">
                            <i class="bi bi-cloud-upload"></i>
                            <p>Kéo thả ảnh hoặc nhấn để chọn</p>
                            <small>Tối đa 5MB. JPG, PNG, WEBP</small>
                        </div>
                        <div class="admin-upload-preview" id="cover-preview" style="display: none;">
                            <img src="" alt="Preview">
                            <button type="button" class="admin-preview-remove" onclick="removeCoverPreview()">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    </div>
                    @if($primaryImage)
                        <div class="admin-current-image mt-2">
                            <img src="{{ asset('storage/' . $primaryImage->image_url) }}" alt="Ảnh hiện tại">
                            <span class="admin-badge admin-badge-primary">Ảnh hiện tại</span>
                        </div>
                    @endif
                </div>

                <div class="admin-form-group">
                    <label for="room_images">Ảnh phòng (nhiều ảnh)</label>
                    <div class="admin-upload-zone admin-upload-multiple" id="room-upload-zone">
                        <input type="file" id="room_images" name="room_images[]" class="admin-file-input" accept="image/*" multiple>
                        <div class="admin-upload-placeholder">
                            <i class="bi bi-images"></i>
                            <p>Kéo thả nhiều ảnh hoặc nhấn để chọn</p>
                            <small>Tối đa 5MB/ảnh</small>
                        </div>
                    </div>
                    <div class="admin-image-preview-grid" id="room-preview-grid"></div>
                    
                    @if($roomImages->count() > 0)
                        <div class="admin-image-grid mt-3">
                            @foreach($roomImages as $image)
                                <div class="admin-image-item" data-image-id="{{ $image->id }}">
                                    <img src="{{ asset('storage/' . $image->image_url) }}" alt="Ảnh phòng">
                                    <button type="button" class="admin-image-delete" onclick="deleteImage(this, {{ $image->id }})">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                    <input type="hidden" name="delete_images[]" value="{{ $image->id }}" style="display: none;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Submit -->
    <div class="admin-form-actions mt-4">
        <a href="{{ route('admin.homestays') }}" class="admin-btn admin-btn-outline">Hủy</a>
        <button type="submit" class="admin-btn admin-btn-primary">
            <i class="bi bi-check-lg"></i>
            {{ $submitLabel }}
        </button>
    </div>
</form>

<!-- Booking Calendar Modal -->
<div class="booking-modal" id="booking-modal">
    <div class="booking-modal-content">
        <div class="booking-modal-header">
            <h2>Lịch đặt phòng - {{ $homestay->title ?? 'Homestay' }}</h2>
            <button type="button" class="booking-modal-close" onclick="closeBookingModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="booking-calendar" id="booking-calendar-content">
            <!-- Calendar sẽ được tạo động bằng JavaScript -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.admin-form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
.admin-form-grid .admin-card:nth-child(1),
.admin-form-grid .admin-card:nth-child(4),
.admin-form-grid .admin-card:nth-child(5),
.admin-form-grid .admin-card:nth-child(6) {
    grid-column: 1 / -1;
}
.admin-form-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.admin-form-row-location {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
.admin-form-section {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
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
}
.admin-form-control:focus {
    outline: none;
    border-color: #003b0d;
    box-shadow: 0 0 0 3px rgba(0, 59, 13, 0.1);
}
.admin-form-control[readonly] {
    background: #f5f5f5;
    cursor: not-allowed;
}
.admin-form-hint {
    display: block;
    margin-top: 4px;
    font-size: 12px;
    color: #666;
}
.admin-amenities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 10px;
}
.admin-amenity-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s;
}
.admin-amenity-item:hover {
    background: #e9ecef;
}
.admin-amenity-item input[type="checkbox"] {
    width: 16px;
    height: 16px;
}

/* Upload Zone Styles */
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
.admin-upload-zone.drag-over {
    border-color: #003b0d;
    background: #e8f5e9;
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
.admin-image-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
    margin-top: 10px;
}
.admin-image-preview-item {
    position: relative;
}
.admin-image-preview-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 6px;
}
.admin-image-preview-item button {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #dc3545;
    font-size: 14px;
}
.admin-image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 15px;
}
.admin-image-item {
    position: relative;
}
.admin-image-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 6px;
}
.admin-image-delete {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.9);
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: white;
    font-size: 14px;
    padding: 0;
    transition: all 0.2s;
}
.admin-image-delete:hover {
    background: #dc3545;
    transform: scale(1.1);
}
.admin-image-delete input {
    display: none;
}
.admin-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* Booking Calendar Styles */
.booking-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.booking-modal.active {
    display: flex;
}

.booking-modal-content {
    background: white;
    border-radius: 8px;
    padding: 30px;
    max-width: 900px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.booking-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 2px solid #eee;
    padding-bottom: 15px;
}

.booking-modal-header h2 {
    margin: 0;
    font-size: 20px;
    color: #333;
}

.booking-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.booking-modal-close:hover {
    color: #000;
}

.booking-calendar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.booking-calendar-month {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    background: #fafafa;
}

.booking-calendar-month-header {
    text-align: center;
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 15px;
    color: #333;
}

.booking-calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
}

.booking-calendar-weekday {
    text-align: center;
    font-weight: 600;
    font-size: 12px;
    color: #666;
    padding: 8px 0;
    border-bottom: 1px solid #ddd;
}

.booking-calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    font-size: 12px;
    cursor: default;
    background: white;
    border: 1px solid #eee;
}

.booking-calendar-day.empty {
    background: #f5f5f5;
    cursor: not-allowed;
}

.booking-calendar-day.available {
    background: #e8f5e9;
    color: #333;
}

.booking-calendar-day.checkin {
    background: #fff3cd;
    color: #333;
    font-weight: 600;
    border-color: #ffc107;
}

.booking-calendar-day.checkout {
    background: #cfe2ff;
    color: #333;
    font-weight: 600;
    border-color: #0d6efd;
}

.booking-calendar-day.booked {
    background: #f8d7da;
    color: #721c24;
    cursor: not-allowed;
}

.booking-legend {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
}

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 3px;
    border: 1px solid #ddd;
}
@media (max-width: 768px) {
    .admin-form-grid {
        grid-template-columns: 1fr;
    }
    .admin-form-row,
    .admin-form-row-location {
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

    // Auto generate slug from title
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd')
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            slugInput.value = slug;
        });
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

    // Load provinces from API v2
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

            // Select existing value if editing
            const currentProvince = "{{ old('province', $homestay->province ?? '') }}";
            if (currentProvince) {
                provinceSelect.value = currentProvince;
                // Trigger ward load
                const provinceCode = Array.from(provinceSelect.options)
                    .find(opt => opt.value === currentProvince)?.dataset.provinceCode;
                if (provinceCode) {
                    loadWards(provinceCode);
                }
            }
        } catch (error) {
            console.error('Error loading provinces:', error);
        }
    }

    // Load wards when province is selected (using API v2)
    async function loadWards(provinceCode) {
        try {
            // Gọi API v2 với province code để lấy wards
            const response = await fetch(`https://provinces.open-api.vn/api/v2/p/${provinceCode}?depth=2`);
            const data = await response.json();
            
            wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
            
            // Check if wards are at root level (for thành phố trung ương like Hanoi)
            if (data.wards && Array.isArray(data.wards)) {
                data.wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.name;
                    option.textContent = ward.name;
                    wardSelect.appendChild(option);
                });
            }
            // Otherwise, lấy tất cả wards từ các districts
            else if (data.districts && Array.isArray(data.districts)) {
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

            // Select existing value if editing
            const currentWard = "{{ old('ward', $homestay->ward ?? '') }}";
            if (currentWard) {
                wardSelect.value = currentWard;
            }
        } catch (error) {
            console.error('Error loading wards:', error);
        }
    }

    // Event listener for province
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

    // Initialize
    loadProvinces();

    // Cover Image Upload with Preview & Drag-Drop
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

    // Drag & Drop for cover image
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

    // Remove cover preview
    window.removeCoverPreview = function() {
        coverInput.value = '';
        coverPreview.style.display = 'none';
        coverPlaceholder.style.display = 'block';
    };

    // Room Images Upload with Preview & Drag-Drop
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

    // Drag & Drop for room images
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
        
        // Update the input files
        const dataTransfer = new DataTransfer();
        roomFiles.forEach(file => dataTransfer.items.add(file));
        roomInput.files = dataTransfer.files;
        
        updateRoomPreviews();
    }, false);

    // Remove room file
    window.removeRoomFile = function(index) {
        roomFiles.splice(index, 1);
        const dataTransfer = new DataTransfer();
        roomFiles.forEach(file => dataTransfer.items.add(file));
        roomInput.files = dataTransfer.files;
        updateRoomPreviews();
    };

    // Delete existing room image
    window.deleteImage = function(button, imageId) {
        const item = button.closest('.admin-image-item');
        const hiddenInput = item.querySelector('input[name="delete_images[]"]');
        
        if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
            item.style.opacity = '0.5';
            item.style.pointerEvents = 'none';
            button.disabled = true;
        }
    };

    // Booking Calendar Functions
    const homestayId = {{ $isEdit ? $homestay->id : 'null' }};
    
    window.openBookingModal = function() {
        if (!homestayId) {
            alert('Vui lòng lưu homestay trước khi xem lịch đặt phòng');
            return;
        }
        document.getElementById('booking-modal').classList.add('active');
        loadBookingCalendar();
    };
    
    window.closeBookingModal = function() {
        document.getElementById('booking-modal').classList.remove('active');
    };
    
    window.loadBookingCalendar = function() {
        // Fetch bookings từ API
        fetch(`/api/homestays/${homestayId}/bookings`)
            .then(response => response.json())
            .then(data => {
                renderCalendar(data.bookings || []);
            })
            .catch(error => {
                console.error('Error loading bookings:', error);
                renderCalendar([]);
            });
    };
    
    window.renderCalendar = function(bookings) {
        const today = new Date();
        const calendarContent = document.getElementById('booking-calendar-content');
        calendarContent.innerHTML = '';
        
        // Show 3 months
        for (let i = 0; i < 3; i++) {
            const date = new Date(today.getFullYear(), today.getMonth() + i, 1);
            calendarContent.appendChild(renderMonth(date, bookings));
        }
    };
    
    window.renderMonth = function(date, bookings) {
        const monthDiv = document.createElement('div');
        monthDiv.className = 'booking-calendar-month';
        
        const monthName = new Intl.DateTimeFormat('vi-VN', { month: 'long', year: 'numeric' }).format(date);
        const headerDiv = document.createElement('div');
        headerDiv.className = 'booking-calendar-month-header';
        headerDiv.textContent = monthName;
        monthDiv.appendChild(headerDiv);
        
        const gridDiv = document.createElement('div');
        gridDiv.className = 'booking-calendar-grid';
        
        // Weekday headers
        const weekdays = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
        weekdays.forEach(day => {
            const dayEl = document.createElement('div');
            dayEl.className = 'booking-calendar-weekday';
            dayEl.textContent = day;
            gridDiv.appendChild(dayEl);
        });
        
        // Days
        const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = (firstDay.getDay() + 6) % 7; // Monday = 0
        
        // Empty cells before first day
        for (let i = 0; i < startingDayOfWeek; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'booking-calendar-day empty';
            gridDiv.appendChild(emptyDiv);
        }
        
        // Days of month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayEl = document.createElement('div');
            const currentDate = new Date(date.getFullYear(), date.getMonth(), day);
            const dateStr = currentDate.toISOString().split('T')[0];
            
            dayEl.className = 'booking-calendar-day available';
            dayEl.textContent = day;
            
            // Check bookings
            const booking = bookings.find(b => {
                const checkIn = new Date(b.check_in_date).toISOString().split('T')[0];
                const checkOut = new Date(b.check_out_date).toISOString().split('T')[0];
                return dateStr >= checkIn && dateStr < checkOut;
            });
            
            const checkinBooking = bookings.find(b => 
                new Date(b.check_in_date).toISOString().split('T')[0] === dateStr
            );
            
            const checkoutBooking = bookings.find(b => 
                new Date(b.check_out_date).toISOString().split('T')[0] === dateStr
            );
            
            if (checkinBooking) {
                dayEl.classList.add('checkin');
                dayEl.title = `Check-in: ${checkinBooking.guest_name || 'Khách'}`;
            } else if (checkoutBooking) {
                dayEl.classList.add('checkout');
                dayEl.title = `Check-out: ${checkoutBooking.guest_name || 'Khách'}`;
            } else if (booking) {
                dayEl.classList.add('booked');
                dayEl.title = `Đã đặt: ${booking.guest_name || 'Khách'}`;
            }
            
            gridDiv.appendChild(dayEl);
        }
        
        monthDiv.appendChild(gridDiv);
        return monthDiv;
    };
    
    // Event listener for booking calendar button
    const checkBookingBtn = document.getElementById('check-booking-calendar');
    if (checkBookingBtn) {
        checkBookingBtn.addEventListener('click', openBookingModal);
    }
    
    // Close modal when clicking outside
    document.getElementById('booking-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookingModal();
        }
    });

    // Form submit validation
    const form = document.getElementById('homestay-form');
    form.addEventListener('submit', function(e) {
        console.log('=== FORM SUBMIT ===');
        console.log('Form action:', form.action);
        console.log('Form method:', form.method);
        
        // Check all required fields
        const title = document.getElementById('title').value;
        const type = document.getElementById('type').value;
        const description = document.getElementById('description').value;
        const province = document.getElementById('province').value;
        const ward = document.getElementById('ward').value;
        const address = document.getElementById('address').value;
        const pricePerNight = document.getElementById('price_per_night').value;
        const maxGuests = document.getElementById('max_guests').value;
        const coverImage = document.getElementById('cover_image').files.length;
        
        console.log('Form data:');
        console.log('- title:', title);
        console.log('- type:', type);
        console.log('- description:', description);
        console.log('- province:', province);
        console.log('- ward:', ward);
        console.log('- address:', address);
        console.log('- price_per_night:', pricePerNight);
        console.log('- max_guests:', maxGuests);
        console.log('- cover_image files:', coverImage);
        
        if (!title) {
            console.error('ERROR: Title is empty');
            e.preventDefault();
            alert('Vui lòng nhập tiêu đề');
            return false;
        }
        
        if (!type) {
            console.error('ERROR: Type is empty');
            e.preventDefault();
            alert('Vui lòng chọn loại hình');
            return false;
        }
        
        if (!province) {
            console.error('ERROR: Province is empty');
            e.preventDefault();
            alert('Vui lòng chọn tỉnh/thành phố');
            return false;
        }
        
        if (!ward) {
            console.error('ERROR: Ward is empty');
            e.preventDefault();
            alert('Vui lòng chọn phường/xã');
            return false;
        }
        
        if (coverImage === 0) {
            console.error('ERROR: No cover image');
            e.preventDefault();
            alert('Vui lòng tải lên ảnh đại diện');
            return false;
        }
        
        console.log('✓ All validation passed');
        console.log('Form submitting...');
        return true;
    });
});
</script>
@endpush
