@extends('admin.layout.app')

@section('title', 'Tổng quát hệ thống')

@section('content')
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <h1 class="admin-page-title h3 fw-bold mb-1">@yield('title')</h1>
            <p class="admin-page-subtitle text-muted small mb-0">Theo dõi số liệu tổng hợp và hoạt động mới nhất của hệ
                thống.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="dashboard_from_date" class="form-label small fw-bold text-secondary">Từ ngày</label>
                    <input type="date" id="dashboard_from_date" name="from_date" class="form-control border-light-subtle"
                        value="{{ $fromDate }}">
                </div>
                <div class="col-md-4">
                    <label for="dashboard_to_date" class="form-label small fw-bold text-secondary">Đến ngày</label>
                    <input type="date" id="dashboard_to_date" name="to_date" class="form-control border-light-subtle"
                        value="{{ $toDate }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="admin-filter-btn">
                        <i class="bi bi-filter"></i> Lọc dữ liệu
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="admin-filter-clear-btn">Xóa lọc</a>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-primary">
                <i class="bi bi-people"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ number_format($totalUsers) }}</div>
                <div class="admin-stat-label">Người dùng</div>
            </div>
        </div>
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
                <div
                    class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold h6" style="letter-spacing: 0.5px;">Doanh thu và lượng đặt phòng</h5>
                </div>
                <div class="card-body p-4">
                    <div class="admin-chart-container" style="height: 350px;">
                        <canvas id="revenueBookingChart"></canvas>
                    </div>
                    <div class="d-flex flex-wrap gap-3 justify-content-center small mt-4">
                        <span><span class="admin-dot d-inline-block me-1 rounded-circle"
                                style="background:#1d4ed8; width:10px; height:10px;"></span>Doanh thu</span>
                        <span><span class="admin-dot d-inline-block me-1 rounded-circle"
                                style="background:#f97316; width:10px; height:10px;"></span>Lượng đặt phòng</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-light-subtle">
                    <h5 class="card-title mb-0 fw-bold h6" style="letter-spacing: 0.5px;">Trạng thái đặt phòng</h5>
                </div>
                <div class="card-body p-4">
                    <div class="admin-chart-container" style="height: 350px;">
                        <canvas id="bookingStatusChart"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                <div
                    class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold h6" style="letter-spacing: 0.5px;">Chỗ nghỉ mới nhất</h5>
                    <a href="{{ route('admin.homestays') }}"
                        class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Tên chỗ nghỉ</th>
                                    <th>Mã</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentHomestays as $homestay)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark small text-truncate" style="max-width: 200px;">
                                                {{ $homestay->title }}</div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-light text-dark border small fw-medium">{{ $homestay->room_code }}</span>
                                        </td>
                                        <td>
                                            @if($homestay->status === 'available')
                                                <span class="admin-badge admin-badge-success">Còn trống</span>
                                            @else
                                                <span class="admin-badge admin-badge-warning">Bận</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($homestay->created_at)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5 small">Chưa có dữ liệu.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                <div
                    class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold h6" style="letter-spacing: 0.5px;">Người dùng mới nhất</h5>
                    <a href="{{ route('admin.users') }}"
                        class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark small">{{ $user->full_name }}</div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ optional($user->created_at)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-5 small">Chưa có dữ liệu.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold h6" style="letter-spacing: 0.5px;">Top 5 homestay được săn đón nhất</h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Top</th>
                            <th>Chỗ nghỉ</th>
                            <th>Điểm đến</th>
                            <th>Lượt đặt</th>
                            <th>Lượt thích</th>
                            <th>Đánh giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($popularHomestays as $index => $homestay)
                            <tr>
                                <td>
                                    <span
                                        class="badge {{ $index == 0 ? 'bg-warning' : ($index == 1 ? 'bg-secondary-subtle text-secondary' : ($index == 2 ? 'bg-danger-subtle text-danger' : 'bg-light text-muted')) }} rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 24px; height: 24px; font-size: 10px;">{{ $index + 1 }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small">{{ $homestay->title }}</div>
                                </td>
                                <td>{{ $homestay->destination->name ?? $homestay->province ?? '-' }}</td>
                                <td>
                                    <span
                                        class="admin-badge admin-badge-success">{{ number_format($homestay->bookings_count) }}</span>
                                </td>
                                <td>
                                    <span
                                        class="admin-badge admin-badge-danger">{{ number_format($homestay->favorites_count) }}</span>
                                </td>
                                <td>
                                    <div class="text-warning small fw-bold">
                                        <i
                                            class="bi bi-star-fill me-1"></i>{{ number_format((float) ($homestay->reviews_avg_rating ?? 0), 1) }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5 small">Chưa có dữ liệu.</td>
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