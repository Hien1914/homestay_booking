@extends('admin.layout.app')

@section('title', isset($amenity) ? 'Chỉnh sửa tiện nghi' : 'Tạo tiện nghi mới')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">{{ isset($amenity) ? 'Chỉnh sửa tiện nghi' : 'Tạo tiện nghi mới' }}</h1>
        <p class="admin-page-subtitle">{{ isset($amenity) ? 'Cập nhật thông tin tiện nghi' : 'Điền thông tin để tạo tiện nghi mới' }}</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.amenities') }}" class="admin-btn admin-btn-outline">
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

<div class="admin-card" style="max-width: 600px;">
    <div class="admin-card-header">
        <h3><i class="bi bi-check2-square me-2"></i>Thông tin tiện nghi</h3>
    </div>
    <div class="admin-card-body">
        <form action="{{ isset($amenity) ? route('admin.amenities.update', $amenity) : route('admin.amenities.store') }}" method="POST">
            @csrf
            @if(isset($amenity))
                @method('PUT')
            @endif

            <div class="admin-form-group">
                <label for="name">Tên tiện nghi <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="admin-form-control" 
                    value="{{ old('name', $amenity->name ?? '') }}" 
                    placeholder="VD: WiFi, Máy lạnh, Bếp..." required>
                @error('name')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-actions">
                <a href="{{ route('admin.amenities') }}" class="admin-btn admin-btn-outline">Hủy</a>
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-lg"></i>
                    {{ isset($amenity) ? 'Cập nhật' : 'Tạo mới' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
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
    margin-top: 20px;
}
</style>
@endpush
