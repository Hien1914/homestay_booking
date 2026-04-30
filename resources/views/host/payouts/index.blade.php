@extends('host.layout.app')

@section('title', 'Lịch sử rút tiền')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="admin-page-title">Quản lý rút tiền</h1>
        <p class="admin-page-subtitle">Xem lịch sử và gửi yêu cầu rút tiền của bạn</p>
    </div>
    <a href="{{ route('host.payouts.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle"></i>
        <span>Yêu cầu rút tiền</span>
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background: rgba(34, 197, 94, 0.1); color: var(--success-color);">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="admin-stat-info">
                <span class="admin-stat-label">Tổng thu nhập</span>
                <h3 class="admin-stat-value">{{ number_format($totalRevenue) }}đ</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--primary-color);">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="admin-stat-info">
                <span class="admin-stat-label">Đã rút</span>
                <h3 class="admin-stat-value">{{ number_format($totalWithdrawn) }}đ</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                <i class="bi bi-piggy-bank"></i>
            </div>
            <div class="admin-stat-info">
                <span class="admin-stat-label">Khả dụng</span>
                <h3 class="admin-stat-value">{{ number_format($available) }}đ</h3>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h5 class="admin-">Lịch sử giao dịch</h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã GD</th>
                    <th>Ngày yêu cầu</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th>Cập nhật cuối</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $payout)
                <tr>
                    <td>#{{ $payout->id }}</td>
                    <td>{{ $payout->created_at->format('d/m/Y H:i') }}</td>
                    <td class="fw-bold">{{ number_format($payout->amount) }}đ</td>
                    <td>
                        @if($payout->status === 'pending')
                            <span class="admin-status-badge status-pending">Đang chờ</span>
                        @elseif($payout->status === 'completed')
                            <span class="admin-status-badge status-active">Thành công</span>
                        @else
                            <span class="admin-status-badge status-failed">Bị từ chối</span>
                        @endif
                    </td>
                    <td>{{ $payout->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Chưa có giao dịch nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payouts->hasPages())
    <div class="admin-card-footer">
        {{ $payouts->links() }}
    </div>
    @endif
</div>
@endsection

