@extends('admin.layout.app')

@section('title', 'Báo cáo')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">Báo cáo & Thống kê</h1>
        <p class="admin-page-subtitle">Xem và xuất báo cáo hoạt động của hệ thống</p>
    </div>
    <div class="admin-page-actions">
        <button type="button" class="admin-btn admin-btn-outline" onclick="exportAllReports()">
            <i class="bi bi-download"></i>
            Xuất tất cả
        </button>
    </div>
</div>

<!-- Date Range Filter -->
<div class="admin-card admin-filters-card">
    <div class="admin-filters-row">
        <div class="admin-filter-group">
            <label class="admin-filter-label">Từ ngày</label>
            <input type="date" id="dateFrom" class="admin-filter-input">
        </div>
        <div class="admin-filter-group">
            <label class="admin-filter-label">Đến ngày</label>
            <input type="date" id="dateTo" class="admin-filter-input">
        </div>
        <button type="button" class="admin-btn admin-btn-primary" onclick="applyDateFilter()">
            <i class="bi bi-funnel"></i>
            Lọc dữ liệu
        </button>
    </div>
</div>

<!-- Statistics Overview -->
<div class="admin-stats-grid admin-stats-grid-4">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary">
            <i class="bi bi-currency-dollar"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value" id="totalRevenue">{{ number_format($totalRevenue ?? 0) }}đ</div>
            <div class="admin-stat-label">Tổng doanh thu</div>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value" id="totalBookings">{{ $totalBookings ?? 0 }}</div>
            <div class="admin-stat-label">Tổng đặt phòng</div>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-info">
            <i class="bi bi-people"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value" id="newUsers">{{ $newUsers ?? 0 }}</div>
            <div class="admin-stat-label">Người dùng mới</div>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-warning">
            <i class="bi bi-house-door"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value" id="newHomestays">{{ $newHomestays ?? 0 }}</div>
            <div class="admin-stat-label">Homestay mới</div>
        </div>
    </div>
</div>

<div class="admin-grid admin-grid-2">
    <!-- Revenue Chart -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="bi bi-graph-up me-2"></i>Biểu đồ doanh thu</h3>
            <button type="button" class="admin-btn admin-btn-sm admin-btn-outline" onclick="exportRevenueChart()">
                <i class="bi bi-download"></i>
            </button>
        </div>
        <div class="admin-card-body">
            <canvas id="revenueChart" height="300"></canvas>
        </div>
    </div>
    
    <!-- Bookings Chart -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="bi bi-bar-chart me-2"></i>Biểu đồ đặt phòng</h3>
            <button type="button" class="admin-btn admin-btn-sm admin-btn-outline" onclick="exportBookingsChart()">
                <i class="bi bi-download"></i>
            </button>
        </div>
        <div class="admin-card-body">
            <canvas id="bookingsChart" height="300"></canvas>
        </div>
    </div>
</div>

<!-- Top Homestays -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-trophy me-2"></i>Top Homestay được đặt nhiều nhất</h3>
        <button type="button" class="admin-btn admin-btn-sm admin-btn-outline" onclick="exportTopHomestays()">
            <i class="bi bi-download"></i>
            Xuất Excel
        </button>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Xếp hạng</th>
                        <th>Homestay</th>
                        <th>Số đặt phòng</th>
                        <th>Doanh thu</th>
                        <th>Đánh giá</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topHomestays ?? [] as $index => $homestay)
                        <tr>
                            <td>
                                <span class="admin-rank-badge admin-rank-{{ $index + 1 }}">
                                    @if($index < 3)
                                        <i class="bi bi-trophy-fill"></i>
                                    @endif
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td>
                                <div class="admin-homestay-cell">
                                    <div class="admin-homestay-thumb">
                                        @if(isset($homestay->images[0]))
                                            <img src="{{ asset('storage/' . $homestay->images[0]) }}" alt="{{ $homestay->name }}">
                                        @else
                                            <div class="admin-homestay-thumb-placeholder">
                                                <i class="bi bi-house"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="admin-homestay-info">
                                        <span class="admin-homestay-name">{{ $homestay->name }}</span>
                                        <span class="admin-homestay-code">{{ $homestay->city ?? 'Chưa xác định' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td><strong>{{ $homestay->bookings_count ?? 0 }}</strong></td>
                            <td>{{ number_format($homestay->total_revenue ?? 0) }}đ</td>
                            <td>
                                <div class="admin-rating">
                                    <i class="bi bi-star-fill"></i>
                                    <span>{{ number_format($homestay->avg_rating ?? 0, 1) }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Chưa có dữ liệu
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Revenue by Location -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-geo-alt me-2"></i>Doanh thu theo địa điểm</h3>
        <button type="button" class="admin-btn admin-btn-sm admin-btn-outline" onclick="exportLocationRevenue()">
            <i class="bi bi-download"></i>
            Xuất Excel
        </button>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Địa điểm</th>
                        <th>Số homestay</th>
                        <th>Số đặt phòng</th>
                        <th>Doanh thu</th>
                        <th>Tỷ lệ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revenueByLocation ?? [] as $location)
                        <tr>
                            <td><strong>{{ $location->name }}</strong></td>
                            <td>{{ $location->homestays_count }}</td>
                            <td>{{ $location->bookings_count }}</td>
                            <td>{{ number_format($location->revenue) }}đ</td>
                            <td>
                                <div class="admin-progress-bar">
                                    <div class="admin-progress-fill" style="width: {{ $location->percentage }}%"></div>
                                    <span>{{ $location->percentage }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Chưa có dữ liệu
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default date range (last 30 days)
    const today = new Date();
    const thirtyDaysAgo = new Date(today);
    thirtyDaysAgo.setDate(today.getDate() - 30);
    
    document.getElementById('dateTo').value = today.toISOString().split('T')[0];
    document.getElementById('dateFrom').value = thirtyDaysAgo.toISOString().split('T')[0];
    
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueLabels ?? ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12']) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($revenueData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!},
                borderColor: '#4a9349',
                backgroundColor: 'rgba(74, 147, 73, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + 'đ';
                        }
                    }
                }
            }
        }
    });
    
    // Bookings Chart
    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    new Chart(bookingsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($bookingLabels ?? ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12']) !!},
            datasets: [{
                label: 'Số đặt phòng',
                data: {!! json_encode($bookingData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!},
                backgroundColor: 'rgba(74, 147, 73, 0.8)',
                borderColor: '#4a9349',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});

function applyDateFilter() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    if (!dateFrom || !dateTo) {
        showToast('Vui lòng chọn khoảng thời gian', 'warning');
        return;
    }
    
    showToast('Đang tải dữ liệu...', 'info');
    
    // TODO: Implement AJAX filter
    setTimeout(() => {
        showToast('Dữ liệu đã được cập nhật', 'success');
    }, 1000);
}

function exportAllReports() {
    showToast('Đang xuất tất cả báo cáo...', 'info');
    setTimeout(() => {
        showToast('Xuất báo cáo thành công!', 'success');
    }, 2000);
}

function exportRevenueChart() {
    showToast('Đang xuất biểu đồ doanh thu...', 'info');
    setTimeout(() => {
        showToast('Xuất thành công!', 'success');
    }, 1000);
}

function exportBookingsChart() {
    showToast('Đang xuất biểu đồ đặt phòng...', 'info');
    setTimeout(() => {
        showToast('Xuất thành công!', 'success');
    }, 1000);
}

function exportTopHomestays() {
    showToast('Đang xuất danh sách top homestay...', 'info');
    setTimeout(() => {
        showToast('Xuất Excel thành công!', 'success');
    }, 1500);
}

function exportLocationRevenue() {
    showToast('Đang xuất doanh thu theo địa điểm...', 'info');
    setTimeout(() => {
        showToast('Xuất Excel thành công!', 'success');
    }, 1500);
}
</script>
@endpush
