@extends('host.layout.app')

@section('title', 'Tổng quát kênh Host')

@section('content')
<div class="admin-page-header mb-4">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title h3 fw-bold mb-1">@yield('title')</h1>
        <p class="admin-page-subtitle text-muted small mb-0">Theo dõi hoạt động kinh doanh của các chỗ nghỉ bạn đang quản lý.</p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('host.dashboard') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="host_dashboard_from_date" class="form-label small fw-bold text-secondary">Từ ngày</label>
                <input type="date" id="host_dashboard_from_date" name="from_date" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-md-4">
                <label for="host_dashboard_to_date" class="form-label small fw-bold text-secondary">Đến ngày</label>
                <input type="date" id="host_dashboard_to_date" name="to_date" class="form-control" value="{{ $toDate }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                <a href="{{ route('host.dashboard') }}" class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="admin-stats-grid mb-4">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-house-door"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ number_format($totalHomestays) }}</div>
            <div class="admin-stat-label">Chỗ nghỉ</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-warning">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ number_format($totalBookings) }}</div>
            <div class="admin-stat-label">Đặt phòng</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-danger">
            <i class="bi bi-cash-coin"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value text-nowrap">{{ number_format((float) $totalRevenue, 0, ',', '.') }} đ</div>
            <div class="admin-stat-label">Doanh thu</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold h6 text-secondary" style="letter-spacing: 0.5px;">Doanh thu và lượng đặt phòng</h5>
            </div>
            <div class="card-body p-4">
                <div class="admin-chart-container mb-3" style="height: 350px;">
                    <canvas id="revenueBookingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-light-subtle">
                <h5 class="mb-0 fw-bold h6 text-secondary" style="letter-spacing: 0.5px;">Trạng thái đặt phòng</h5>
            </div>
            <div class="card-body p-4">
                <div class="admin-chart-container mb-3" style="height: 350px;">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="mb-0 fw-bold h6 text-secondary">Chỗ nghỉ mới cập nhật</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light-subtle">
                    <tr>
                        <th class="ps-4 py-3 small text-secondary fw-bold" style="font-size: 11px;">Tên chỗ nghỉ</th>
                        <th class="text-center py-3 small text-secondary fw-bold" style="font-size: 11px;">Điểm đến</th>
                        <th class="text-center py-3 small text-secondary fw-bold" style="font-size: 11px;">Trạng thái</th>
                        <th class="pe-4 text-end py-3 small text-secondary fw-bold" style="font-size: 11px;">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentHomestays as $homestay)
                        <tr>
                            <td class="ps-4 py-3 fw-bold small">{{ $homestay->title }}</td>
                            <td class="text-center py-3 small">{{ $homestay->destination->name ?? $homestay->province ?? '-' }}</td>
                            <td class="text-center py-3">
                                <span class="badge {{ $homestay->status === 'available' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill small" style="font-size: 10px;">{{ $homestay->status === 'available' ? 'Còn trống' : 'Bận' }}</span>
                            </td>
                            <td class="pe-4 text-end py-3 small text-muted">{{ optional($homestay->created_at)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted small">Chưa có dữ liệu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="mb-0 fw-bold h6 text-secondary">Đặt phòng gần đây</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light-subtle">
                    <tr>
                        <th class="ps-4 py-3 small text-secondary fw-bold" style="font-size: 11px;">Khách hàng</th>
                        <th class="py-3 small text-secondary fw-bold" style="font-size: 11px;">Chỗ nghỉ</th>
                        <th class="text-center py-3 small text-secondary fw-bold" style="font-size: 11px;">Nhận/Trả phòng</th>
                        <th class="pe-4 text-end py-3 small text-secondary fw-bold" style="font-size: 11px;">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                        <tr>
                            <td class="ps-4 py-3 fw-bold small">{{ $booking->user?->full_name ?? 'Khách' }}</td>
                            <td class="py-3 small">{{ $booking->homestay?->title ?? '-' }}</td>
                            <td class="text-center py-3 small">
                                <span class="text-dark">{{ optional($booking->check_in)->format('d/m/Y') }}</span>
                                <span class="text-muted ms-1">- {{ optional($booking->check_out)->format('d/m/Y') }}</span>
                            </td>
                            <td class="pe-4 text-end py-3">
                                <span class="badge bg-light text-dark border small rounded-pill px-2 py-1" style="font-size: 10px;">{{ $booking->statusLabel() }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted small">Chưa có dữ liệu.</td></tr>
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
window.dashboardChartsData = {
    chartLabels: @json($chartLabels),
    scatterRevenue: @json($scatterRevenue),
    scatterBookings: @json($scatterBookings),
    chartTickLimit: @json($chartTickLimit),
    bookingStatusLabels: @json($bookingStatusLabels),
    bookingStatusData: @json($bookingStatusData),
    bookingStatusColors: @json($bookingStatusColors),
};
</script>
<script src="{{ asset('js/admin/dashboard-charts.js') }}"></script>
@endpush


