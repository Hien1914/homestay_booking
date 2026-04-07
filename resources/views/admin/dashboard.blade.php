@extends('admin.layout.app')

@section('title', 'Tổng quan')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">Tổng quan hệ thống</h1>
        <p class="admin-page-subtitle">Theo dõi và quản lý toàn bộ hoạt động của NestAway</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.reports') }}" class="admin-btn admin-btn-outline">
            <i class="bi bi-graph-up"></i>
            Xem báo cáo
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary">
            <i class="bi bi-people"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ $totalUsers }}</div>
            <div class="admin-stat-label">Tổng người dùng</div>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-house-door"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ $totalHomestays }}</div>
            <div class="admin-stat-label">Tổng chỗ nghỉ</div>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-warning">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ $publishedHomestays }}</div>
            <div class="admin-stat-label">Đang hoạt động</div>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-cash-coin"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">0 ₫</div>
            <div class="admin-stat-label">Tổng doanh thu</div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="admin-content-grid">
    <!-- Recent Homestays -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="bi bi-house-door me-2"></i>Chỗ nghỉ mới nhất</h3>
            <a href="{{ route('admin.homestays') }}" class="admin-btn admin-btn-sm admin-btn-outline">
                Xem tất cả
            </a>
        </div>
        <div class="admin-card-body">
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Tên chỗ nghỉ</th>
                            <th>Mã</th>
                            <th>Địa điểm</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentHomestays as $homestay)
                            <tr>
                                <td>
                                    <strong>{{ $homestay->title ?? 'Chưa có tên' }}</strong>
                                </td>
                                <td><span class="admin-id-badge">{{ $homestay->room_code }}</span></td>
                                <td>{{ $homestay->province ?? 'Chưa xác định' }}</td>
                                <td>
                                    @if($homestay->status === 'published')
                                        <span class="admin-badge admin-badge-success">Đang hoạt động</span>
                                    @else
                                        <span class="admin-badge admin-badge-secondary">Chưa đăng</span>
                                    @endif
                                </td>
                                <td>{{ $homestay->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-house-door"></i> Chưa có chỗ nghỉ nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Recent Users -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="bi bi-people me-2"></i>Người dùng mới</h3>
            <a href="{{ route('admin.users') }}" class="admin-btn admin-btn-sm admin-btn-outline">
                Xem tất cả
            </a>
        </div>
        <div class="admin-card-body">
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Người dùng</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Ngày đăng ký</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td>
                                    <div class="admin-user-cell">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=003b0d&color=fff&size=36" alt="Avatar" class="admin-user-avatar">
                                        <span>{{ $user->full_name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="admin-badge admin-badge-warning">Admin</span>
                                    @else
                                        <span class="admin-badge admin-badge-info">Khách hàng</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-people"></i> Chưa có người dùng
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="admin-charts-grid">
    <!-- Revenue Chart -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="bi bi-graph-up me-2"></i>Báo cáo doanh thu</h3>
        </div>
        <div class="admin-card-body">
            <div class="admin-line-chart" id="revenueChart">
                <svg viewBox="0 0 800 300" class="admin-chart-svg">
                    <!-- Grid lines -->
                    <defs>
                        <pattern id="grid" width="100" height="50" patternUnits="userSpaceOnUse">
                            <path d="M 100 0 L 0 0 0 50" fill="none" stroke="#e9efe8" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="800" height="300" fill="url(#grid)" />
                    
                    <!-- Y-axis labels -->
                    <text x="20" y="20" font-size="12" fill="#5f7066">10M</text>
                    <text x="20" y="80" font-size="12" fill="#5f7066">7.5M</text>
                    <text x="20" y="140" font-size="12" fill="#5f7066">5M</text>
                    <text x="20" y="200" font-size="12" fill="#5f7066">2.5M</text>
                    <text x="20" y="260" font-size="12" fill="#5f7066">0</text>
                    
                    <!-- Axes -->
                    <line x1="40" y1="280" x2="780" y2="280" stroke="#d7e3d2" stroke-width="2"/>
                    <line x1="40" y1="280" x2="40" y2="20" stroke="#d7e3d2" stroke-width="2"/>
                    
                    <!-- Line data -->
                    <polyline points="80,240 150,200 220,180 290,150 360,120 430,90 500,100 570,80 640,110 710,70" 
                              fill="none" stroke="#003b0d" stroke-width="2"/>
                    <polyline points="80,240 150,200 220,180 290,150 360,120 430,90 500,100 570,80 640,110 710,70" 
                              fill="none" stroke="#169b62" stroke-width="3" opacity="0.8"/>
                    
                    <!-- Data points -->
                    <circle cx="80" cy="240" r="4" fill="#003b0d"/>
                    <circle cx="150" cy="200" r="4" fill="#003b0d"/>
                    <circle cx="220" cy="180" r="4" fill="#003b0d"/>
                    <circle cx="290" cy="150" r="4" fill="#003b0d"/>
                    <circle cx="360" cy="120" r="4" fill="#003b0d"/>
                    <circle cx="430" cy="90" r="4" fill="#003b0d"/>
                    <circle cx="500" cy="100" r="4" fill="#003b0d"/>
                    <circle cx="570" cy="80" r="4" fill="#003b0d"/>
                    <circle cx="640" cy="110" r="4" fill="#003b0d"/>
                    <circle cx="710" cy="70" r="4" fill="#003b0d"/>
                    
                    <!-- X-axis labels -->
                    <text x="60" y="300" font-size="12" fill="#5f7066">T1</text>
                    <text x="130" y="300" font-size="12" fill="#5f7066">T2</text>
                    <text x="200" y="300" font-size="12" fill="#5f7066">T3</text>
                    <text x="270" y="300" font-size="12" fill="#5f7066">T4</text>
                    <text x="340" y="300" font-size="12" fill="#5f7066">T5</text>
                    <text x="410" y="300" font-size="12" fill="#5f7066">T6</text>
                    <text x="480" y="300" font-size="12" fill="#5f7066">T7</text>
                    <text x="550" y="300" font-size="12" fill="#5f7066">T8</text>
                    <text x="620" y="300" font-size="12" fill="#5f7066">T9</text>
                    <text x="690" y="300" font-size="12" fill="#5f7066">T10</text>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Bookings Chart -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="bi bi-calendar-check me-2"></i>Thống kê đơn đặt phòng</h3>
        </div>
        <div class="admin-card-body">
            <div class="admin-line-chart" id="bookingsChart">
                <svg viewBox="0 0 800 300" class="admin-chart-svg">
                    <!-- Grid lines -->
                    <defs>
                        <pattern id="grid2" width="100" height="50" patternUnits="userSpaceOnUse">
                            <path d="M 100 0 L 0 0 0 50" fill="none" stroke="#e9efe8" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="800" height="300" fill="url(#grid2)" />
                    
                    <!-- Y-axis labels -->
                    <text x="15" y="20" font-size="12" fill="#5f7066">150</text>
                    <text x="20" y="80" font-size="12" fill="#5f7066">112</text>
                    <text x="20" y="140" font-size="12" fill="#5f7066">75</text>
                    <text x="20" y="200" font-size="12" fill="#5f7066">37</text>
                    <text x="25" y="260" font-size="12" fill="#5f7066">0</text>
                    
                    <!-- Axes -->
                    <line x1="40" y1="280" x2="780" y2="280" stroke="#d7e3d2" stroke-width="2"/>
                    <line x1="40" y1="280" x2="40" y2="20" stroke="#d7e3d2" stroke-width="2"/>
                    
                    <!-- Line data -->
                    <polyline points="80,200 150,160 220,140 290,120 360,100 430,110 500,90 570,130 640,80 710,120" 
                              fill="none" stroke="#d99022" stroke-width="2"/>
                    <polyline points="80,200 150,160 220,140 290,120 360,100 430,110 500,90 570,130 640,80 710,120" 
                              fill="none" stroke="#d99022" stroke-width="3" opacity="0.8"/>
                    
                    <!-- Data points -->
                    <circle cx="80" cy="200" r="4" fill="#d99022"/>
                    <circle cx="150" cy="160" r="4" fill="#d99022"/>
                    <circle cx="220" cy="140" r="4" fill="#d99022"/>
                    <circle cx="290" cy="120" r="4" fill="#d99022"/>
                    <circle cx="360" cy="100" r="4" fill="#d99022"/>
                    <circle cx="430" cy="110" r="4" fill="#d99022"/>
                    <circle cx="500" cy="90" r="4" fill="#d99022"/>
                    <circle cx="570" cy="130" r="4" fill="#d99022"/>
                    <circle cx="640" cy="80" r="4" fill="#d99022"/>
                    <circle cx="710" cy="120" r="4" fill="#d99022"/>
                    
                    <!-- X-axis labels -->
                    <text x="60" y="300" font-size="12" fill="#5f7066">T1</text>
                    <text x="130" y="300" font-size="12" fill="#5f7066">T2</text>
                    <text x="200" y="300" font-size="12" fill="#5f7066">T3</text>
                    <text x="270" y="300" font-size="12" fill="#5f7066">T4</text>
                    <text x="340" y="300" font-size="12" fill="#5f7066">T5</text>
                    <text x="410" y="300" font-size="12" fill="#5f7066">T6</text>
                    <text x="480" y="300" font-size="12" fill="#5f7066">T7</text>
                    <text x="550" y="300" font-size="12" fill="#5f7066">T8</text>
                    <text x="620" y="300" font-size="12" fill="#5f7066">T9</text>
                    <text x="690" y="300" font-size="12" fill="#5f7066">T10</text>
                </svg>
            </div>
        </div>
    </div>
</div>
@endsection
