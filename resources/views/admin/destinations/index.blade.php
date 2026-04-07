@extends('admin.layout.app')

@section('title', 'Quản lý điểm đến')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">Quản lý điểm đến</h1>
        <p class="admin-page-subtitle">Quản lý tất cả điểm đến trong hệ thống</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.destinations.create') }}" class="admin-btn admin-btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tạo mới
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary">
            <i class="bi bi-geo-alt"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ $stats['total'] }}</div>
            <div class="admin-stat-label">Tổng điểm đến</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="admin-card admin-filters-card">
    <div class="admin-filters-row">
        <div class="admin-search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchDestination" class="admin-search-input" placeholder="Tìm kiếm điểm đến...">
        </div>
    </div>
</div>

<!-- Destinations Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-geo-alt me-2"></i>Danh sách điểm đến</h3>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table" id="destinationsTable">
                <thead>
                    <tr>
                        <th>Tên điểm đến</th>
                        <th>Tỉnh/Thành phố</th>
                        <th>Miền</th>
                        <th>Số chỗ nghỉ</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($destinations as $destination)
                        <tr data-destination-id="{{ $destination->id }}">
                            <td>
                                <div class="admin-destination-cell">
                                    <i class="bi bi-geo-alt-fill me-2"></i>
                                    {{ $destination->name }}
                                </div>
                            </td>
                            <td>{{ $destination->province ?? '-' }}</td>
                            <td>{{ $destination->region ?? '-' }}</td>
                            <td><span class="admin-badge admin-badge-info">{{ $destination->homestays_count ?? 0 }}</span></td>
                            <td>{{ optional($destination->created_at)->format('d/m/Y') }}</td>
                            <td>
                                <div class="admin-actions d-flex gap-1">
                                    <a href="{{ route('admin.destinations.edit', $destination) }}" class="admin-action-btn" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa điểm đến này?');">
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
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-geo-alt"></i> Chưa có điểm đến nào
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
    const searchInput = document.getElementById('searchDestination');
    const table = document.getElementById('destinationsTable');
    const rows = table.querySelectorAll('tbody tr');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        
        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return;
            const text = row.textContent.toLowerCase();
            const matchesSearch = text.includes(searchTerm);
            row.style.display = matchesSearch ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterTable);
});
</script>
@endpush
