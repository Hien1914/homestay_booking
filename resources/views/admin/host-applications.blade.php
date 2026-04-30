@extends('admin.layout.app')

@section('title', 'Đăng ký chủ nhà')

@section('content')
    @php
        $applicationCollection = $applications->getCollection();
        $totalApplications = $applications->total();
        $pendingCount = $applicationCollection->where('status', \App\Models\HostApplication::STATUS_PENDING)->count();
        $approvedCount = $applicationCollection->filter(fn($item) => $item->isApproved())->count();
        $rejectedCount = $applicationCollection->where('status', \App\Models\HostApplication::STATUS_REJECTED)->count();
    @endphp

    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <h1 class="admin-page-title">@yield('title')</h1>
            <p class="admin-page-subtitle">Duyệt và quản lý đơn đăng ký trở thành chủ nhà.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.host-applications') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $fromDate ?? '' }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $toDate ?? '' }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                    <a href="{{ route('admin.host-applications') }}"
                        class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-primary">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ $totalApplications }}</div>
                <div class="admin-stat-label">Tổng đơn đăng ký</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ $pendingCount }}</div>
                <div class="admin-stat-label">Chờ duyệt</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-success">
                <i class="bi bi-patch-check"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ $approvedCount }}</div>
                <div class="admin-stat-label">Đã xác nhận</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-danger">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ $rejectedCount }}</div>
                <div class="admin-stat-label">Đã từ chối</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="card-title mb-0 fw-bold h6">
                <i class="bi bi-person-badge me-2 text-primary"></i>Danh sách yêu cầu đăng ký chủ nhà
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>CMND/CCCD</th>
                            <th>Ngân hàng</th>
                            <th>Số tài khoản</th>
                            <th>Chủ tài khoản</th>
                            <th>Trạng thái</th>
                            <th>Ngày gửi</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                            <tr>
                                <td>
                                    <span class="admin-id-badge">#{{ $app->id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $app->user?->full_name ?? 'Khách' }}</div>
                                    <small class="text-muted">{{ $app->user?->email ?? '-' }}</small>
                                </td>
                                <td>{{ $app->id_card ?? '-' }}</td>
                                <td>{{ $app->bank_name ?? '-' }}</td>
                                <td>{{ $app->bank_acc ?? '-' }}</td>
                                <td>{{ $app->bank_holder ?? '-' }}</td>
                                <td>
                                    @if($app->status === \App\Models\HostApplication::STATUS_PENDING)
                                        <span class="admin-badge admin-badge-pending">Chờ duyệt</span>
                                    @elseif($app->isApproved())
                                        <span class="admin-badge admin-badge-confirmed">Đã xác nhận</span>
                                    @else
                                        <span class="admin-badge admin-badge-cancelled">Từ chối</span>
                                    @endif
                                </td>
                                <td>{{ optional($app->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($app->status === \App\Models\HostApplication::STATUS_PENDING)
                                        <div class="d-flex justify-content-center gap-1">
                                            <form action="{{ route('admin.host-applications.approve', $app->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="admin-action-btn admin-action-btn-success"
                                                    title="Duyệt" onclick="return confirm('Duyệt đơn này?')">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.host-applications.reject', $app->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="admin-action-btn admin-action-btn-danger"
                                                    title="Từ chối" onclick="return confirm('Từ chối đơn này?')">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted small">Đã xử lý</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Chưa có đơn đăng ký nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
@endsection