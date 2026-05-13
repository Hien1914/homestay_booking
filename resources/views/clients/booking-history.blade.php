@extends('clients.layout.app')
@section('title', 'Lịch sử đặt phòng')
@section('content')
<link rel="stylesheet" href="{{ asset('css/clients/profile.css') }}">
<link rel="stylesheet" href="{{ asset('css/clients/booking-history.css') }}">

<section class="profile-page section-py" style="min-height: 60vh;">
  <div class="container-setting my-4">
    <div class="profile-detail-card" style="width: 100%;">
      <div class="profile-card-head" style="margin-bottom: 24px;">
          <h2 class="profile-card-title mb-0">Lịch sử đặt phòng của tôi</h2>
        </div>
      </div>

      @if(session('error'))
        <div class="alert alert-danger mb-3 rounded-4" style="border: 1px solid #fca5a5; background-color: #fef2f2; color: #991b1b; padding: 14px 16px; font-size: 0.95rem;">
          <i class="fa-solid fa-circle-xmark me-2"></i> {{ session('error') }}
        </div>
      @endif

      @if(session('success'))
        <div class="alert alert-success mb-3 rounded-4" style="border: 1px solid #86efac; background-color: #f0fdf4; color: #166534; padding: 14px 16px; font-size: 0.95rem;">
          <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
      @endif

      <div class="alert alert-warning mb-4 rounded-4" style="border: 1px solid #fcd34d; background-color: #fffbeb; color: #b45309; padding: 16px; font-size: 0.95rem;">
        <i class="fa-solid fa-circle-exclamation me-2 fs-5 align-middle"></i> 
        <strong>Lưu ý quan trọng:</strong> Quý khách <b>BẮT BUỘC 100%</b> phải thực hiện thao tác <strong style="color: #0d6efd;"><i class="fa-solid fa-key"></i> Nhận phòng</strong> và <strong style="color: #6366f1;"><i class="fa-solid fa-door-closed"></i> Trả phòng</strong> trực tiếp trên hệ thống tại trang Lịch sử đặt phòng này ngay khi đến nhận và rời khỏi chỗ nghỉ. Việc này là bắt buộc để hệ thống ghi nhận chính xác thời gian lưu trú, đảm bảo quyền lợi của quý khách và tuân thủ các chính sách của NestAway.
      </div>

      <div class="table-responsive">
        <table class="table booking-history-table">
            <thead>
                <tr>
                    <th class="text-center">Mã đơn</th>
                    <th>Chỗ nghỉ</th>
                    <th class="text-center">Ngày nhận/trả</th>
                    <th class="text-center">Tổng tiền</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thanh toán</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td class="text-center"><strong>#{{ $booking->id }}</strong></td>
                        <td>
                            <a href="{{ route('homestay.show', $booking->homestay->slug) }}" class="text-decoration-none text-dark fw-bold">
                                {{ Str::limit($booking->homestay->title, 35) }}
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-column align-items-center">
                                <span>{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</span>
                                <small class="text-muted">đến {{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</small>
                            </div>
                        </td>
                        <td class="text-center"><strong>{{ number_format($booking->total_amount, 0, ',', '.') }} đ</strong></td>
                        <td class="text-center">
                            <span class="admin-badge {{ $booking->statusBadgeClass() }}">{{ $booking->statusLabel() }}</span>
                        </td>
                        <td class="text-center">
                            @if($booking->payment && $booking->payment->payment_status === \App\Models\Payment::STATUS_PENDING && !$booking->payment->paid_at && in_array($booking->status, [\App\Models\Booking::STATUS_PENDING, \App\Models\Booking::STATUS_CONFIRMED]))
                                <a href="{{ route('payment.show', ['booking' => $booking->id]) }}" class="booking-history-pay-link">
                                    <i class="bi bi-credit-card-2-front-fill me-1"></i> Thanh toán ngay
                                </a>
                            @elseif($booking->payment)
                                <span class="admin-badge {{ $booking->payment->displayStatusBadgeClass() }}">{{ $booking->payment->displayStatusLabel() }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="booking-history-actions">
                                <a href="{{ route('homestay.show', $booking->homestay->slug) }}" class="booking-history-icon-btn" title="Xem phòng">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                @if(in_array($booking->status, [\App\Models\Booking::STATUS_PENDING, \App\Models\Booking::STATUS_CONFIRMED]))
                                    @php
                                        $checkIn = \Carbon\Carbon::parse($booking->check_in)->startOfDay();
                                        $daysUntil = now()->diffInDays($checkIn, false);
                                    @endphp
                                    @if($daysUntil > 0)
                                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đặt phòng này?');">
                                            @csrf
                                            <button type="submit" class="booking-history-icon-btn is-danger" title="Hủy phòng">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                @if($booking->status === \App\Models\Booking::STATUS_CONFIRMED)
                                    <form action="{{ route('bookings.checkin', $booking->id) }}" method="POST" onsubmit="return confirm('Bạn muốn nhận phòng ngay bây giờ?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="booking-history-icon-btn" title="Nhận phòng" style="color: #0d6efd; border-color: #0d6efd;">
                                            <i class="fa-solid fa-key"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($booking->status === \App\Models\Booking::STATUS_CHECKED_IN)
                                    <form action="{{ route('bookings.checkout', $booking->id) }}" method="POST" onsubmit="return confirm('Bạn muốn trả phòng ngay bây giờ?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="booking-history-icon-btn" title="Trả phòng" style="color: #6366f1; border-color: #6366f1;">
                                            <i class="fa-solid fa-door-closed"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($booking->status === \App\Models\Booking::STATUS_COMPLETED)
                                    <a href="{{ route('homestay.show', $booking->homestay->slug) }}#danh-gia" class="booking-history-icon-btn" title="Đánh giá">
                                        <i class="fa-regular fa-star"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-calendar2-x mb-2" style="font-size: 2rem;"></i>
                                <span>Chưa có lịch sử đặt phòng nào.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
