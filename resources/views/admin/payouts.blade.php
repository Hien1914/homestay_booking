@extends('admin.layout.app')

@section('title', 'Quản lý rút tiền')

@section('content')
    <div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="admin-page-title">Yêu cầu rút tiền</h1>
            <p class="admin-page-subtitle">Duyệt và quản lý các yêu cầu rút tiền từ Host</p>
        </div>
    </div>

    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-primary">
                <i class="bi bi-list-task"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ number_format($stats['total']) }}</div>
                <div class="admin-stat-label">Tổng yêu cầu</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ number_format($stats['pending']) }}</div>
                <div class="admin-stat-label">Chờ xử lý</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ number_format($stats['completed']) }}</div>
                <div class="admin-stat-label">Đã hoàn thành</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-danger">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ number_format($stats['failed']) }}</div>
                <div class="admin-stat-label">Đã từ chối</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.payouts') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $fromDate ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $toDate ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Đã hoàn tất</option>
                        <option value="failed" {{ $status == 'failed' ? 'selected' : '' }}>Đã từ chối</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                    <a href="{{ route('admin.payouts') }}"
                        class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="card-title mb-0 fw-bold h6">
                <i class="bi bi-list-task me-2 text-primary"></i>Danh sách yêu cầu
            </h5>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Host</th>
                        <th>Số tiền</th>
                        <th>Ngày yêu cầu</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payouts as $payout)
                        <tr>
                            <td><span class="admin-id-badge">#{{ $payout->id }}</span></td>
                            <td>
                                <div class="fw-bold">{{ $payout->host->full_name }}</div>
                            </td>
                            <td class="fw-bold text-success">{{ number_format($payout->amount) }}đ</td>
                            <td>{{ $payout->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($payout->status === 'pending')
                                    <span class="admin-badge admin-badge-pending">Chờ xử lý</span>
                                @elseif($payout->status === 'completed')
                                    <span class="admin-badge admin-badge-confirmed">Thành công</span>
                                @else
                                    <span class="admin-badge admin-badge-cancelled">Đã từ chối</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    @if($payout->status === 'pending')
                                        <form action="{{ route('admin.payouts.approve', $payout) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn duyệt yêu cầu rút tiền này?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="admin-action-btn"
                                                style="color: var(--admin-success); border-color: var(--admin-success);"
                                                title="Duyệt yêu cầu">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.payouts.reject', $payout) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn từ chối yêu cầu rút tiền này?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="admin-action-btn admin-action-btn-danger"
                                                title="Từ chối yêu cầu">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Đã xử lý</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Không có yêu cầu nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payouts->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $payouts->links() }}
            </div>
        @endif
    </div>
@endsection