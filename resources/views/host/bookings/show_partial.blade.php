<div class="booking-detail-popup" id="printableBookingDetail">
    <div class="row g-4">
        <div class="col-md-6">
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Thông tin cơ bản</h6>
            <div class="admin-detail-list">
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Mã booking</span>
                    <span class="admin-detail-value fw-bold">#{{ $booking->id }}</span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Homestay</span>
                    <span class="admin-detail-value">{{ $booking->homestay->title }}</span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Khách hàng</span>
                    <span class="admin-detail-value">
                        <div class="fw-bold">{{ $booking->user->full_name }}</div>
                        <div class="small text-muted">{{ $booking->user->email }}</div>
                    </span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Số khách</span>
                    <span class="admin-detail-value">{{ $booking->num_guests }} người</span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Nhận phòng</span>
                    <span class="admin-detail-value">{{ $booking->check_in->format('d/m/Y') }}</span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Trả phòng</span>
                    <span class="admin-detail-value">{{ $booking->check_out->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-credit-card me-2"></i>Thanh toán & Trạng thái</h6>
            <div class="admin-detail-list">
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Tổng tiền</span>
                    <span class="admin-detail-value fw-bold text-success" style="font-size: 1.2rem;">{{ number_format($booking->total_amount) }}đ</span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Trạng thái</span>
                    <span class="admin-detail-value">
                        @switch($booking->status)
                            @case('pending') <span class="admin-badge admin-badge-pending">Chờ xác nhận</span> @break
                            @case('confirmed') <span class="admin-badge admin-badge-success">Thành công</span> @break
                            @case('checked_in') <span class="admin-badge admin-badge-ongoing">Đang ở</span> @break
                            @case('completed') <span class="admin-badge admin-badge-success">Hoàn thành</span> @break
                            @case('cancelled') <span class="admin-badge admin-badge-danger">Đã hủy</span> @break
                        @endswitch
                    </span>
                </div>
                @if($booking->cancel_status != 'none' && $booking->cancel_status)
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Yêu cầu hủy</span>
                    <span class="admin-detail-value">
                        @if($booking->cancel_status == 'pending') <span class="text-warning fw-bold">Chờ duyệt</span>
                        @elseif($booking->cancel_status == 'approved') <span class="text-success fw-bold">Đã duyệt</span>
                        @else <span class="text-danger fw-bold">Từ chối</span> @endif
                    </span>
                </div>
                @endif
                @if($booking->payment)
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Thanh toán</span>
                    <span class="admin-detail-value">
                        @if($booking->payment->payment_status === \App\Models\Payment::STATUS_SUCCESS)
                            <span class="text-success small fw-medium">Đã thanh toán ({{ $booking->payment->payment_method }})</span>
                        @elseif($booking->payment->paid_at)
                            <span class="text-info small fw-medium">Đã CK, chờ xác nhận</span>
                        @else
                            <span class="text-danger small fw-medium">Chưa thanh toán</span>
                        @endif
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($booking->review)
    <div class="mt-4 p-3 bg-light rounded-3 border">
        <h6 class="fw-bold mb-2"><i class="bi bi-star-fill text-warning me-2"></i>Đánh giá của khách</h6>
        <div class="mb-2">
            @for($i=1; $i<=5; $i++)
                <i class="bi bi-star-fill {{ $i <= $booking->review->rating ? 'text-warning' : 'text-muted' }}"></i>
            @endfor
            <span class="ms-2 fw-bold">{{ $booking->review->rating }}/5</span>
        </div>
        <p class="mb-0 text-muted fst-italic">"{{ $booking->review->comment }}"</p>
    </div>
    @endif

    <div class="mt-4 d-flex justify-content-between align-items-center">
        <div class="d-flex gap-2">
            @if($booking->cancel_status == 'pending')
                <a href="{{ route('host.bookings.cancel-approve', ['booking' => $booking, 'action' => 'approve']) }}" class="admin-create-btn" style="background-color: var(--admin-success);" onclick="return confirm('Duyệt yêu cầu hủy?')">Duyệt hủy</a>
                <a href="{{ route('host.bookings.cancel-approve', ['booking' => $booking, 'action' => 'reject']) }}" class="admin-filter-clear-btn" style="color: var(--admin-danger); border-color: var(--admin-danger);" onclick="return confirm('Từ chối yêu cầu hủy?')">Từ chối hủy</a>
            @endif
        </div>
        <button type="button" class="admin-filter-btn" onclick="window.printBooking()">
            <i class="bi bi-printer me-2"></i>In đơn hàng
        </button>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #printableBookingDetail, #printableBookingDetail * { visibility: visible; }
    #printableBookingDetail {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
    }
    .btn, .admin-filter-btn, .admin-create-btn, .admin-filter-clear-btn { display: none !important; }
}
</style>

<script>
window.printBooking = function() {
    window.print();
}
</script>
