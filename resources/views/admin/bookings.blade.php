@extends('admin.layout.app')

@section('title', 'Quản lý đặt phòng')

@section('content')
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <h1 class="admin-page-title h3 fw-bold mb-1">@yield('title')</h1>
            <p class="admin-page-subtitle text-muted small mb-0">Quản lý tất cả đơn đặt phòng trong hệ thống</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-primary">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ $totalBookings }}</div>
                <div class="admin-stat-label">Tổng đặt phòng</div>
            </div>
        </div>

        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ $confirmedBookings }}</div>
                <div class="admin-stat-label">Đã xác nhận</div>
            </div>
        </div>

        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-info">
                <i class="bi bi-credit-card"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ number_format((float) $totalRevenue, 0, ',', '.') }}đ</div>
                <div class="admin-stat-label">Tổng doanh thu</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <div class="row g-4 align-items-end">
                <div class="col-lg-12">
                    <form method="GET" action="{{ route('admin.bookings') }}" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="bookings_from_date" class="form-label small fw-bold text-secondary">Từ ngày</label>
                            <input type="date" id="bookings_from_date" name="from_date" class="form-control"
                                value="{{ $fromDate }}">
                        </div>
                        <div class="col-md-5">
                            <label for="bookings_to_date" class="form-label small fw-bold text-secondary">Đến ngày</label>
                            <input type="date" id="bookings_to_date" name="to_date" class="form-control"
                                value="{{ $toDate }}">
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                            <a href="{{ route('admin.bookings') }}"
                                class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approval Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold h6 text-secondary" style="letter-spacing: 0.5px;">Đơn cần duyệt</h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Homestay</th>
                            <th>Lịch trình</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingApprovals as $booking)
                            <tr data-status="{{ $booking->status }}">
                                <td><span class="fw-bold text-secondary">#{{ $booking->id }}</span></td>
                                <td><div class="fw-bold text-dark small">{{ $booking->user?->full_name ?? 'Khách' }}</div></td>
                                <td>
                                    <div class="small fw-semibold text-dark text-truncate mx-auto" style="max-width: 150px;">{{ $booking->homestay?->title ?? '-' }}</div>
                                    <div class="text-muted" style="font-size: 10px;">Mã: #{{ $booking->homestay_id }}</div>
                                </td>
                                <td>
                                    <div class="small text-dark fw-medium">{{ optional($booking->check_in)->format('d/m/Y') }}</div>
                                    <div class="text-muted" style="font-size: 10px;">đến {{ optional($booking->check_out)->format('d/m/Y') }}</div>
                                </td>
                                <td><span class="fw-bold text-success small">{{ number_format((float) $booking->total_amount, 0, ',', '.') }}đ</span></td>
                                <td>
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
                                <td><span class="admin-badge admin-badge-info">Chờ duyệt</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <form action="{{ route('admin.payments.confirm', $booking->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="admin-action-btn admin-action-btn-success" title="Duyệt thanh toán" onclick="return confirm('Xác nhận đã nhận thanh toán?');">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="admin-action-btn" title="Chi tiết đơn đặt phòng" onclick="showBookingDetail({{ $booking->id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5 small">Không có đơn nào đang chờ duyệt.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $pendingApprovals->links() }}
            </div>
        </div>
    </div>

    <!-- General Bookings Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold h6 text-secondary" style="letter-spacing: 0.5px;">Danh sách đặt phòng chung</h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table" id="bookingsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Homestay</th>
                            <th>Lịch trình</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr data-status="{{ $booking->status }}">
                                <td>
                                    <span class="fw-bold text-secondary">#{{ $booking->id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small">{{ $booking->user?->full_name ?? 'Khách' }}</div>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark text-truncate mx-auto" style="max-width: 150px;">
                                        {{ $booking->homestay?->title ?? '-' }}</div>
                                    <div class="text-muted" style="font-size: 10px;">Mã: #{{ $booking->homestay_id }}</div>
                                </td>
                                <td>
                                    <div class="small text-dark fw-medium">{{ optional($booking->check_in)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-muted" style="font-size: 10px;">đến
                                        {{ optional($booking->check_out)->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    <span
                                        class="fw-bold text-success small">{{ number_format((float) $booking->total_amount, 0, ',', '.') }}đ</span>
                                </td>
                                <td>
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
                                <td>
                                    @if($booking->payment)
                                        @php
                                            $payClass = match ($booking->payment->payment_status) {
                                                \App\Models\Payment::STATUS_SUCCESS => 'admin-badge-success',
                                                \App\Models\Payment::STATUS_PENDING => ($booking->payment->paid_at ? 'admin-badge-info' : 'admin-badge-pending'),
                                                default => 'admin-badge-secondary',
                                            };
                                            $payLabel = $booking->payment->payment_status === \App\Models\Payment::STATUS_PENDING && $booking->payment->paid_at ? 'Chờ duyệt' : $booking->payment->statusLabel();
                                        @endphp
                                        <span class="admin-badge {{ $payClass }}">{{ $payLabel }}</span>
                                    @else
                                        <span class="admin-badge admin-badge-secondary">Chưa thanh toán</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        @if($booking->payment && $booking->payment->payment_status === \App\Models\Payment::STATUS_PENDING && $booking->payment->paid_at)
                                            <form action="{{ route('admin.payments.confirm', $booking->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="admin-action-btn admin-action-btn-success"
                                                    title="Duyệt thanh toán"
                                                    onclick="return confirm('Xác nhận đã nhận thanh toán?');">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="admin-action-btn" title="Chi tiết đơn đặt phòng"
                                            onclick="showBookingDetail({{ $booking->id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5 small">Chưa có dữ liệu đặt phòng.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Booking Detail Modal -->
    <div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-light-subtle">
                    <h5 class="modal-title fw-bold">Chi tiết đơn đặt phòng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="bookingDetailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showBookingDetail(bookingId) {
            const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
            const content = document.getElementById('bookingDetailContent');
            content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-success"></div></div>';
            modal.show();

            fetch(`/admin/bookings/${bookingId}/detail`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.text())
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(err => {
                    content.innerHTML = '<div class="alert alert-danger rounded-4">Lỗi tải dữ liệu. Vui lòng thử lại.</div>';
                });
        }
    </script>
@endpush
