@extends('admin.layout.app')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">Quản lý người dùng</h1>
        <p class="admin-page-subtitle">Quản lý tài khoản người dùng và phân quyền</p>
    </div>
    <div class="admin-page-actions">
        <button type="button" class="admin-btn admin-btn-outline" onclick="exportUsers()">
            <i class="bi bi-download"></i>
            Xuất Excel
        </button>
    </div>
</div>

<!-- Statistics -->
<div class="admin-stats-grid admin-stats-grid-2">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary">
            <i class="bi bi-people"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ $users->count() }}</div>
            <div class="admin-stat-label">Tổng người dùng</div>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-info">
            <i class="bi bi-person"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ $users->where('role', 'guest')->count() }}</div>
            <div class="admin-stat-label">Khách hàng</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="admin-card admin-filters-card">
        <div class="admin-search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchUser" class="admin-search-input" placeholder="Tìm kiếm theo tên, email...">
        </div>
</div>

<!-- Users Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-people me-2"></i>Danh sách người dùng</h3>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Vai trò</th>
                        <th>Số lần đặt phòng</th>
                        <th>Ngày đăng ký</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr data-role="{{ $user->role }}">
                            <td><span class="admin-id-badge">#{{ $user->id }}</span></td>
                            <td>
                                <div class="admin-user-cell">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=003b0d&color=fff&size=36" alt="Avatar" class="admin-user-avatar">
                                    <span>{{ $user->full_name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?: '-' }}</td>
                            <td>
                                <span class="admin-badge admin-badge-info">Khách hàng</span>
                            </td>
                            <td>{{ $user->bookings_count }}</td>
                            <td>{{ optional($user->created_at)->format('d/m/Y') }}</td>
                            <td>
                                <div class="admin-actions d-flex gap-1">
                                    <button type="button" class="admin-action-btn" title="Xem chi tiết" onclick="viewUser({{ $user->id }})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="admin-action-btn admin-action-btn-danger" title="Khóa tài khoản" onclick="lockUser({{ $user->id }})">
                                        <i class="bi bi-lock"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-people"></i> Chưa có người dùng nào
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
    const searchInput = document.getElementById('searchUser');
    const roleFilter = document.getElementById('roleFilter');
    const table = document.getElementById('usersTable');
    const rows = table.querySelectorAll('tbody tr');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleValue = roleFilter.value;
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const role = row.dataset.role;
            
            const matchesSearch = text.includes(searchTerm);
            const matchesRole = !roleValue || role === roleValue;
            
            row.style.display = matchesSearch && matchesRole ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
});

function viewUser(id) {
    showToast('Đang tải thông tin người dùng...', 'info');
}

function lockUser(id) {
    if (confirm('Bạn có chắc muốn khóa tài khoản này?')) {
        showToast('Tài khoản đã bị khóa', 'success');
    }
}

function exportUsers() {
    showToast('Đang xuất danh sách người dùng...', 'info');
    setTimeout(() => {
        showToast('Xuất Excel thành công!', 'success');
    }, 1500);
}
</script>
@endpush
