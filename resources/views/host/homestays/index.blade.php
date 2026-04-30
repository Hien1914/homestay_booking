@extends('host.layout.app')

@section('title', 'Quản lý chỗ nghỉ')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Quản lý tất cả homestay bạn đăng ký</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('host.homestays.create') }}" class="admin-create-btn">
            <i class="bi bi-plus-lg"></i>
            Thêm mới
        </a>
    </div>
</div>

<!-- Thống kê nhanh -->
<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary">
            <i class="bi bi-house-door"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ $stats['total'] }}</div>
            <div class="admin-stat-label">Tổng chỗ nghỉ</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ $stats['available'] }}</div>
            <div class="admin-stat-label">Đang hoạt động</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-warning">
            <i class="bi bi-star-fill"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ number_format($stats['avgRating'], 1) }}</div>
            <div class="admin-stat-label">Điểm TB</div>
        </div>
    </div>
</div>

<!-- Bảng danh sách -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-house-door me-2 text-primary"></i>Danh sách chỗ nghỉ
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="ps-4">Chỗ nghỉ</th>
                        <th>Địa chỉ</th>
                        <th class="text-center">Giá/đêm</th>
                        <th class="text-center">Đánh giá</th>
                        <th class="text-center">Trạng thái duyệt</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($homestays as $homestay)
                        @php
                            $primaryImage = $homestay->images->where('is_primary', true)->first() ?? $homestay->images->first();
                            $thumb = $primaryImage ? asset('storage/' . $primaryImage->image_url) : asset('images/no-image.png');
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="admin-thumbnail me-3">
                                        @if($primaryImage)
                                            <img src="{{ $thumb }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image"></i></div>
                                        @endif
                                    </div>
                                    <div class="fw-bold text-dark small text-truncate" style="max-width: 180px;">{{ $homestay->title }}</div>
                                </div>
                            </td>
                            <td>{{ $homestay->province }}<br><small class="text-muted">{{ $homestay->ward }}</small></td>
                            <td class="text-center">
                                @if($homestay->discounted_price < $homestay->price_per_night)
                                    <div class="fw-bold text-success">{{ number_format($homestay->discounted_price) }}đ</div>
                                    <small class="text-muted text-decoration-line-through">{{ number_format($homestay->price_per_night) }}đ</small>
                                @else
                                    <div class="fw-bold">{{ number_format($homestay->price_per_night) }}đ</div>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-star-fill text-warning" style="font-size: 0.75rem;"></i>
                                    <span>{{ number_format($homestay->reviews_avg_rating ?? 0, 1) }}</span>
                                    <small class="text-muted">({{ $homestay->reviews_count ?? 0 }})</small>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($homestay->is_approved)
                                    <span class="admin-badge admin-badge-success">Đã duyệt</span>
                                @else
                                    <span class="admin-badge admin-badge-pending">Chờ duyệt</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="admin-actions d-flex gap-1 justify-content-center">
                                    <a href="{{ route('host.homestays.edit', $homestay) }}" class="admin-action-btn" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('host.homestays.destroy', $homestay) }}" method="POST" onsubmit="return confirm('Xóa homestay này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action-btn admin-action-btn-danger" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-5">Bạn chưa có homestay nào. Hãy tạo mới.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-center">
            {{ $homestays->links() }}
        </div>
    </div>
</div>
@endsection
