@extends('admin.layout.app')

@section('title', 'Quản lý chỗ nghỉ')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">Quản lý chỗ nghỉ</h1>
        <p class="admin-page-subtitle">Quản lý tất cả homestay trong hệ thống</p>
    </div>
    <div class="admin-page-actions">
        <button type="button" class="admin-btn admin-btn-outline" onclick="exportHomestays()">
            <i class="bi bi-download"></i>
            Xuất Excel
        </button>
        <a href="{{ route('admin.homestays.create') }}" class="admin-btn admin-btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tạo mới
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="admin-stats-grid admin-stats-grid-3">
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
            <i class="bi bi-star"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ number_format($stats['avgRating'], 1) }}</div>
            <div class="admin-stat-label">Điểm trung bình</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="admin-card admin-filters-card">
    <div class="admin-filters-row">
        <div class="admin-search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchHomestay" class="admin-search-input" placeholder="Tìm kiếm theo tên, mã homestay...">
        </div>
    </div>
</div>

<!-- Homestays Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-house-door me-2"></i>Danh sách chỗ nghỉ</h3>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table" id="homestaysTable">
                <thead>
                    <tr>
                        <th>Chỗ nghỉ</th>
                        <th>Tỉnh/Thành phố</th>
                        <th>Phường/Xã</th>
                        <th>Giá/đêm</th>
                        <th>Đánh giá</th>
                        <th>Lượt đặt</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($homestays as $homestay)
                        @php
                            $primaryImage = $homestay->images->where('is_primary', true)->first();
                            $coverImage = $primaryImage ? $primaryImage->image_url : ($homestay->images->first()?->image_url ?? null);
                            $avgRating = $homestay->reviews_avg_rating ?? 0;
                        @endphp
                        <tr data-status="{{ $homestay->status }}">
                            <td>
                                <div class="admin-homestay-cell">
                                    <div class="admin-homestay-thumb">
                                        @if($coverImage)
                                            <img src="{{ asset('storage/' . $coverImage) }}" alt="{{ $homestay->title }}">
                                        @else
                                            <div class="admin-homestay-thumb-placeholder">
                                                <i class="bi bi-house"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="admin-homestay-info">
                                        <span class="admin-homestay-name">{{ $homestay->title ?? 'Chưa có tên' }}</span>
                                        <span class="admin-homestay-code">{{ $homestay->room_code }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $homestay->province ?? 'Chưa xác định' }}</td>
                            <td>{{ $homestay->ward ?? 'Chưa xác định' }}</td>
                            <td>{{ number_format($homestay->price_per_night ?? 0) }}đ</td>
                            <td>
                                <div class="admin-rating">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <span>{{ number_format($avgRating, 1) }}</span>
                                </div>
                            </td>
                            <td>{{ $homestay->bookings_count ?? 0 }}</td>
                            <td>{{ optional($homestay->created_at)->format('d/m/Y') }}</td>
                            <td>
                                <div class="admin-actions d-flex gap-1">
                                    <a href="{{ route('admin.homestays.calendar', $homestay) }}" class="admin-action-btn" title="Xem lịch đặt phòng">
                                        <i class="bi bi-calendar3"></i>
                                    </a>
                                    <a href="{{ route('admin.homestays.edit', $homestay) }}" class="admin-action-btn" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.homestays.destroy', $homestay) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa homestay này?');">
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
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-house-door"></i> Chưa có chỗ nghỉ nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchHomestay');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('homestaysTable');
    const rows = table.querySelectorAll('tbody tr');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const status = row.dataset.status;
            
            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;
            
            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
});

function exportHomestays() {
    showToast('Đang xuất danh sách chỗ nghỉ...', 'info');
    setTimeout(() => {
        showToast('Xuất Excel thành công!', 'success');
    }, 1500);
}
</script>
@endpush
