@extends('admin.layout.app')

@section('title', 'Quản lý hoàn tiền')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Theo dõi các đơn đã hoàn tiền trong hệ thống.</p>
    </div>
</div>

<div class="admin-stats-grid admin-stats-grid-4">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-info">
            <i class="bi bi-arrow-return-left"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ number_format((float) $totalRefundAmount, 0, ',', '.') }} đ</div>
            <div class="admin-stat-label">Tổng tiền hoàn</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary">
            <i class="bi bi-receipt-cutoff"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ number_format($refundCount) }}</div>
            <div class="admin-stat-label">Số đơn hoàn tiền</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-warning">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ number_format((float) $averageRefundAmount, 0, ',', '.') }} đ</div>
            <div class="admin-stat-label">Hoàn trung bình</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-wallet2"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ number_format((float) $refundedRevenue, 0, ',', '.') }} đ</div>
            <div class="admin-stat-label">Giá trị đơn đã hoàn</div>
        </div>
    </div>
</div>

<div class="admin-card admin-filters-card">
    <form method="GET" action="{{ route('admin.refunds') }}" class="admin-filters-row">
        <div class="admin-form-group admin-filter-field">
            <label for="refunds_from_date">Từ ngày</label>
            <input type="date" id="refunds_from_date" name="from_date" value="{{ $fromDate }}">
        </div>
        <div class="admin-form-group admin-filter-field">
            <label for="refunds_to_date">Đến ngày</label>
            <input type="date" id="refunds_to_date" name="to_date" value="{{ $toDate }}">
        </div>
        <div class="admin-search-box">
            <i class="bi bi-search"></i>
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                class="admin-search-input"
                placeholder="Tìm theo mã đơn, khách hàng, homestay..."
            >
        </div>
        <div class="admin-form-actions admin-filter-actions">
            <button type="submit" class="admin-btn admin-btn-primary">Lọc</button>
            <a href="{{ route('admin.refunds') }}" class="admin-btn admin-btn-secondary">Xóa lọc</a>
        </div>
    </form>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-arrow-return-left me-2"></i>Danh sách hoàn tiền</h3>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="text-center">Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Homestay</th>
                        <th class="text-center">Tổng đơn</th>
                        <th class="text-center">Tiền hoàn</th>
                        <th class="text-center">Tỷ lệ hoàn</th>
                        <th class="text-center">Ngày cập nhật</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($refunds as $refund)
                        @php
                            $refundRate = (float) ($refund->booking->total_amount ?? 0) > 0
                                ? round(((float) $refund->amount / (float) $refund->booking->total_amount) * 100)
                                : 0;
                        @endphp
                        <tr>
                            <td class="text-center">
                                <span class="admin-code-text">#{{ $refund->booking_id }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $refund->booking->user->full_name ?? 'Chưa xác định' }}</span>
                                    <small class="text-muted">{{ $refund->booking->user->email ?? '-' }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $refund->booking->homestay->title ?? 'Chưa xác định' }}</span>
                                    <small class="text-muted">{{ $refund->booking->homestay->room_code ?? '-' }}</small>
                                </div>
                            </td>
                            <td class="text-center">{{ number_format((float) ($refund->booking->total_amount ?? 0), 0, ',', '.') }} đ</td>
                            <td class="text-center text-success fw-bold">{{ number_format((float) $refund->amount, 0, ',', '.') }} đ</td>
                            <td class="text-center">{{ $refundRate }}%</td>
                            <td class="text-center">{{ optional($refund->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Chưa có giao dịch hoàn tiền nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $refunds->links() }}
        </div>
    </div>
</div>
@endsection


