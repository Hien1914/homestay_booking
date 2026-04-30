@extends('host.layout.app')

@section('title', 'Quản lý đặt phòng')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Danh sách đặt phòng của các chỗ nghỉ bạn quản lý</p>
    </div>
</div>

<!-- Thống kê nhanh trạng thái -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-4">
    <div class="col">
        <div class="admin-stat-card d-flex align-items-center p-3 h-100 shadow-sm border-0">
            <div class="admin-stat-icon admin-stat-icon-warning me-3">
                <i class="bi bi-hourglass-split fs-4"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value fw-bold h4 mb-0">{{ $pendingCount }}</div>
                <div class="admin-stat-label small text-muted">Chờ duyệt</div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="admin-stat-card d-flex align-items-center p-3 h-100 shadow-sm border-0">
            <div class="admin-stat-icon admin-stat-icon-primary me-3">
                <i class="bi bi-check-circle fs-4"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value fw-bold h4 mb-0">{{ $successCount }}</div>
                <div class="admin-stat-label small text-muted">Thành công</div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="admin-stat-card d-flex align-items-center p-3 h-100 shadow-sm border-0">
            <div class="admin-stat-icon admin-stat-icon-info me-3">
                <i class="bi bi-house-door fs-4"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value fw-bold h4 mb-0">{{ $checkedInCount }}</div>
                <div class="admin-stat-label small text-muted">Đang ở</div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="admin-stat-card d-flex align-items-center p-3 h-100 shadow-sm border-0">
            <div class="admin-stat-icon admin-stat-icon-danger me-3">
                <i class="bi bi-x-circle fs-4"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value fw-bold h4 mb-0">{{ $cancelledCount }}</div>
                <div class="admin-stat-label small text-muted">Đã hủy</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-3">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('host.bookings.index') }}" class="row g-3 align-items-end">
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
                    <option value="{{ \App\Models\Booking::STATUS_PENDING }}" {{ request('status') == \App\Models\Booking::STATUS_PENDING ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="{{ \App\Models\Booking::STATUS_CONFIRMED }}" {{ request('status') == \App\Models\Booking::STATUS_CONFIRMED ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="{{ \App\Models\Booking::STATUS_CHECKED_IN }}" {{ request('status') == \App\Models\Booking::STATUS_CHECKED_IN ? 'selected' : '' }}>Đang ở</option>
                    <option value="{{ \App\Models\Booking::STATUS_COMPLETED }}" {{ request('status') == \App\Models\Booking::STATUS_COMPLETED ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="{{ \App\Models\Booking::STATUS_CANCELLED }}" {{ request('status') == \App\Models\Booking::STATUS_CANCELLED ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                <a href="{{ route('host.bookings.index') }}" class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
            </div>
        </form>
    </div>
</div>

<!-- Danh sách booking -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-calendar-check me-2 text-primary"></i>Danh sách đặt phòng
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Homestay</th>
                        <th>Khách hàng</th>
                        <th class="text-center">Ngày nhận</th>
                        <th class="text-center">Ngày trả</th>
                        <th class="text-center">Tổng tiền</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Thanh toán</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="ps-4"><span class="admin-id-badge">#{{ $booking->id }}</span></td>
                            <td><div class="fw-bold small">{{ $booking->homestay->title }}</div></td>
                            <td><div class="small">{{ $booking->user->full_name }}</div></td>
                            <td class="text-center small">{{ $booking->check_in->format('d/m/Y') }}</td>
                            <td class="text-center small">{{ $booking->check_out->format('d/m/Y') }}</td>
                            <td class="text-center fw-bold text-success">{{ number_format($booking->total_amount) }}đ</td>
                            <td class="text-center">
                                @php
                                    $statusClass = match ($booking->status) {
                                        \App\Models\Booking::STATUS_PENDING => 'admin-badge-pending',
                                        \App\Models\Booking::STATUS_CONFIRMED => 'admin-badge-confirmed',
                                        \App\Models\Booking::STATUS_CHECKED_IN => 'admin-badge-ongoing',
                                        \App\Models\Booking::STATUS_COMPLETED => 'admin-badge-success',
                                        \App\Models\Booking::STATUS_CANCELLED => 'admin-badge-cancelled',
                                        default => 'admin-badge-secondary',
                                    };
                                @endphp
                                <span class="admin-badge {{ $statusClass }}">{{ $booking->statusLabel() }}</span>
                            </td>
                            <td class="text-center">
                                @if(!$booking->payment)
                                    <span class="admin-badge admin-badge-secondary">Chưa thanh toán</span>
                                @else
                                    @php
                                        $payClass = match ($booking->payment->payment_status) {
                                            \App\Models\Payment::STATUS_SUCCESS => 'admin-badge-success',
                                            \App\Models\Payment::STATUS_PENDING => ($booking->payment->paid_at ? 'admin-badge-info' : 'admin-badge-pending'),
                                            default => 'admin-badge-secondary',
                                        };
                                        $payLabel = $booking->payment->payment_status === \App\Models\Payment::STATUS_PENDING && $booking->payment->paid_at
                                            ? 'Chờ duyệt'
                                            : $booking->payment->statusLabel();
                                    @endphp
                                    <span class="admin-badge {{ $payClass }}">{{ $payLabel }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="admin-actions d-flex justify-content-center gap-1">
                                    <button type="button" class="admin-action-btn view-booking-detail" data-id="{{ $booking->id }}" data-url="{{ route('host.bookings.show', $booking) }}" title="Chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if($booking->cancel_status == 'pending')
                                        <a href="{{ route('host.bookings.cancel-approve', ['booking' => $booking, 'action' => 'approve']) }}" class="admin-action-btn" style="color: var(--admin-success); border-color: var(--admin-success);" onclick="return confirm('Duyệt yêu cầu hủy?')" title="Duyệt hủy">
                                            <i class="bi bi-check-circle"></i>
                                        </a>
                                        <a href="{{ route('host.bookings.cancel-approve', ['booking' => $booking, 'action' => 'reject']) }}" class="admin-action-btn admin-action-btn-danger" onclick="return confirm('Từ chối yêu cầu hủy?')" title="Từ chối hủy">
                                            <i class="bi bi-x-circle"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-5">Không có đặt phòng nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-center">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Chi tiết đặt phòng -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold" id="bookingDetailModalLabel">Chi tiết đặt phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="bookingDetailContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingModal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
    const modalContent = document.getElementById('bookingDetailContent');
    
    document.querySelectorAll('.view-booking-detail').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const id = this.getAttribute('data-id');
            
            document.getElementById('bookingDetailModalLabel').textContent = 'Chi tiết đặt phòng #' + id;
            modalContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';
            bookingModal.show();
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                modalContent.innerHTML = html;
            })
            .catch(error => {
                modalContent.innerHTML = '<div class="alert alert-danger">Lỗi khi tải dữ liệu.</div>';
                console.error('Error:', error);
            });
        });
    });
});
</script>
@endpush

