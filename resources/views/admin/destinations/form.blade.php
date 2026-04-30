@extends('admin.layout.app')

@section('title', isset($destination) ? 'Chỉnh sửa điểm đến' : 'Tạo điểm đến mới')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">{{ isset($destination) ? 'Cập nhật thông tin điểm đến' : 'Điền thông tin để tạo điểm đến mới' }}</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.destinations') }}" class="admin-btn admin-btn-outline">
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>
    </div>
</div>

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

<form action="{{ isset($destination) ? route('admin.destinations.update', $destination) : route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($destination))
        @method('PUT')
    @endif

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-geo-alt me-2 text-primary"></i>Thông tin điểm đến
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ isset($destination) ? route('admin.destinations.update', $destination) : route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($destination))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label for="name" class="form-label small fw-bold text-secondary">Tên điểm đến <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control rounded-3 py-2" 
                    value="{{ old('name', $destination->name ?? '') }}" 
                    placeholder="VD: Đà Lạt, Hạ Long..." required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="slug" class="form-label small fw-bold text-secondary">Slug <span class="text-danger">*</span></label>
                <input type="text" id="slug" name="slug" class="form-control rounded-3 py-2 bg-light" 
                    value="{{ old('slug', $destination->slug ?? '') }}" 
                    placeholder="tu-dong-theo-ten-diem-den" readonly>
                <div class="form-text small">Slug được tự động tạo theo tên điểm đến để tối ưu SEO</div>
                @error('slug')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="form-label small fw-bold text-secondary">Mô tả ngắn</label>
                <textarea id="description" name="description" class="form-control rounded-3" rows="4" placeholder="Mô tả về điểm đến...">{{ old('description', $destination->description ?? '') }}</textarea>
                @error('description')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary">Ảnh đại diện <span class="text-danger">*</span></label>
                <div class="admin-upload-zone rounded-4 p-5 text-center border-2 border-dashed" id="image-upload-zone" style="cursor: pointer; border-color: #e2e8f0; background: #f8fafc; transition: all 0.2s;">
                    <input type="file" id="image" name="image" class="d-none" accept="image/*" {{ !isset($destination) ? 'required' : '' }}>
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

                @if(isset($destination) && $destination->image)
                    <div class="mt-3 p-2 bg-light rounded-3 border d-inline-block" id="current-image-container">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/' . $destination->image) }}" class="rounded-2" style="width: 80px; height: 50px; object-fit: cover;">
                            <div>
                                <div class="small fw-bold text-dark">Ảnh hiện tại</div>
                                <div class="text-muted" style="font-size: 10px;">Đang được sử dụng trên hệ thống</div>
                            </div>
                        </div>
                    </div>
                @endif
                @error('image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-actions d-flex justify-content-end gap-2 pt-3 border-top mt-5">
                <a href="{{ route('admin.destinations') }}" class="admin-filter-clear-btn text-decoration-none d-flex align-items-center px-4">Hủy</a>
                <button type="submit" class="admin-create-btn px-4">
                    <i class="bi bi-check-lg me-2"></i>
                    {{ isset($destination) ? 'Cập nhật điểm đến' : 'Tạo điểm đến ngay' }}
                </button>
            </div>
        </form>
    </div>
</div>
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/destinations-form.css') }}">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const imageUploadZone = document.getElementById('image-upload-zone');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const imagePlaceholder = imageUploadZone.querySelector('.admin-upload-placeholder');

    const slugify = (value) => value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');

    const syncSlug = () => {
        slugInput.value = slugify(nameInput.value);
    };

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', syncSlug);
        if (!slugInput.value) {
            syncSlug();
        }
    }

    if (imageUploadZone) {
        imageUploadZone.addEventListener('click', () => imageInput.click());
    }

    function handleImageFile(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.querySelector('img').src = e.target.result;
                imagePlaceholder.classList.add('d-none');
                imagePreview.classList.remove('d-none');
                const currentImg = document.getElementById('current-image-container');
                if (currentImg) currentImg.classList.add('d-none');
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

    ['dragenter', 'dragover'].forEach(eventName => {
        imageUploadZone.addEventListener(eventName, () => {
            imageUploadZone.style.borderColor = 'var(--admin-primary)';
            imageUploadZone.style.background = '#eff6ff';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        imageUploadZone.addEventListener(eventName, () => {
            imageUploadZone.style.borderColor = '#e2e8f0';
            imageUploadZone.style.background = '#f8fafc';
        }, false);
    });

    imageUploadZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            imageInput.files = files;
            handleImageFile(files[0]);
        }
    }, false);
});

window.removeImagePreview = function(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    document.getElementById('image').value = '';
    document.getElementById('image-preview').classList.add('d-none');
    document.getElementById('upload-placeholder').classList.remove('d-none');
    const currentImg = document.getElementById('current-image-container');
    if (currentImg) currentImg.classList.remove('d-none');
};
</script>
@endpush

