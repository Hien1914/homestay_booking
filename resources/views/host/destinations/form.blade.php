@extends('host.layout.app')

@section('title', isset($destination) ? 'Sửa điểm đến' : 'Thêm điểm đến')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Nhập thông tin khu vực/điểm đến mới</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <form action="{{ isset($destination) ? route('host.destinations.update', $destination->id) : route('host.destinations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($destination))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="admin-form-group">
                        <label for="name">Tên điểm đến <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="admin-form-control @error('name') is-invalid @enderror" value="{{ old('name', $destination->name ?? '') }}" required placeholder="Ví dụ: Đà Lạt, Hội An..." oninput="updateSlug(this.value)">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="admin-form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="admin-form-control" value="{{ old('slug', $destination->slug ?? '') }}" readonly placeholder="Tự động tạo từ tên">
                    </div>
                </div>
            </div>

            <div class="admin-form-group mt-3">
                <label for="image">Ảnh đại diện</label>
                <input type="file" name="image" id="image" class="admin-form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(this)">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                
                <div class="mt-2" id="imagePreviewContainer">
                    @if(isset($destination) && $destination->image)
                        <img src="{{ asset('storage/' . $destination->image) }}" alt="Preview" style="max-height: 150px; border-radius: 8px;">
                    @else
                        <img id="preview" src="#" alt="Preview" style="max-height: 150px; border-radius: 8px; display: none;">
                    @endif
                </div>
            </div>

            <div class="admin-form-group mt-3">
                <label for="description">Mô tả ngắn</label>
                <textarea name="description" id="description" rows="4" class="admin-form-control @error('description') is-invalid @enderror" placeholder="Mô tả về điểm đến này...">{{ old('description', $destination->description ?? '') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-actions mt-4">
                <a href="{{ route('host.destinations') }}" class="admin-btn admin-btn-secondary">Hủy</a>
                <button type="submit" class="admin-btn admin-btn-primary">
                    {{ isset($destination) ? 'Cập nhật' : 'Lưu lại' }}
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    function updateSlug(val) {
        let slug = val.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[đĐ]/g, 'd')
            .replace(/([^a-z0-9\s-]|[\s-]+)/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview');
                const container = document.getElementById('imagePreviewContainer');
                
                // If there's an existing image, replace its src, otherwise show the preview element
                const existingImg = container.querySelector('img:not(#preview)');
                if (existingImg) {
                    existingImg.src = e.target.result;
                } else {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
