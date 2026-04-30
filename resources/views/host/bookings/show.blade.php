@extends('host.layout.app')

@section('title', 'Chi tiết đặt phòng #' . $booking->id)

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Xem thông tin đặt phòng</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('host.bookings.index') }}" class="admin-btn admin-btn-outline">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3>Thông tin booking</h3>
    </div>
    <div class="admin-card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="admin-table">
                    <tr><th>Mã booking:</th><td>#{{ $booking->id }}</td></tr>
                    <tr><th>Homestay:</th><td>{{ $booking->homestay->title }}</td></tr>
                    <tr><th>Khách hàng:</th><td>{{ $booking->user->full_name }} ({{ $booking->user->email }})</td></tr>
                    <tr><th>Số khách:</th><td>{{ $booking->num_guests }}</td></tr>
                    <tr><th>Nhận phòng:</th><td>{{ $booking->check_in->format('d/m/Y') }}</td></tr>
                    <tr><th>Trả phòng:</th><td>{{ $booking->check_out->format('d/m/Y') }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="admin-table">
                    <tr><th>Tổng tiền:</th><td>{{ number_format($booking->total_amount) }}đ</td></tr>
                    <tr><th>Trạng thái:</th><td>
                        @switch($booking->status)
                            @case('pending') <span class="admin-badge admin-badge-warning">Chờ xác nhận</span> @break
                            @case('confirmed') <span class="admin-badge admin-badge-primary">Đã xác nhận</span> @break
                            @case('checked_in') <span class="admin-badge admin-badge-info">Đang ở</span> @break
                            @case('completed') <span class="admin-badge admin-badge-success">Hoàn thành</span> @break
                            @case('cancelled') <span class="admin-badge admin-badge-danger">Đã hủy</span> @break
                        @endswitch
                     </td></tr>
                    @if($booking->cancel_status != 'none')
                    <tr><th>Yêu cầu hủy:</th><td>
                        @if($booking->cancel_status == 'pending') Chờ duyệt
                        @elseif($booking->cancel_status == 'approved') Đã duyệt hủy phòng
                        @else Từ chối @endif
                     </td></tr>
                    <tr><th>Lý do hủy:</th><td>{{ $booking->cancel_reason ?: 'Không có' }}</td></tr>
                    @endif
                    @if($booking->payment)
                    <tr><th>Thanh toán:</th><td>
                        @if($booking->payment->payment_status === \App\Models\Payment::STATUS_SUCCESS)
                            Đã nhận tiền
                        @elseif($booking->payment->paid_at)
                            Khách đã chuyển khoản, chờ admin xác nhận
                        @elseif($booking->payment->payment_status === \App\Models\Payment::STATUS_PENDING)
                            Chưa thanh toán
                        @else
                            {{ $booking->payment->statusLabel() }}
                        @endif
                    </td></tr>
                    @endif
                </table>
            </div>
        </div>

        @if($booking->review)
        <div class="mt-4">
            <h5>Đánh giá của khách</h5>
            <p><strong>Rating:</strong> {{ $booking->review->rating }}/5</p>
            <p><strong>Nhận xét:</strong> {{ $booking->review->comment }}</p>
        </div>
        @endif

        <div class="mt-4 d-flex gap-2">
            @if($booking->status == 'confirmed')
                <form action="{{ route('host.bookings.checkin', $booking) }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-primary">Xác nhận nhận phòng</button>
                </form>
            @endif
            @if($booking->status == 'checked_in')
                <form action="{{ route('host.bookings.complete', $booking) }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-success">Hoàn thành</button>
                </form>
            @endif
            @if($booking->cancel_status == 'pending')
                <a href="{{ route('host.bookings.cancel-approve', ['booking' => $booking, 'action' => 'approve']) }}" class="admin-btn admin-btn-success" onclick="return confirm('Duyệt yêu cầu hủy?')">Duyệt hủy</a>
                <a href="{{ route('host.bookings.cancel-approve', ['booking' => $booking, 'action' => 'reject']) }}" class="admin-btn admin-btn-danger" onclick="return confirm('Từ chối yêu cầu hủy?')">Từ chối hủy</a>
            @endif
        </div>
    </div>
</div>
@endsection
