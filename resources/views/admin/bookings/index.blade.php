@extends('admin.layout.app')

@section('title', 'Đặt phòng')
@section('page_title', 'Quản lý đặt phòng')
@section('page_kicker', 'Bảng bookings')

@section('content')
<section class="admin-page-section">
    <div class="admin-mini-grid">
        <article class="admin-mini-card"><span>Tổng booking</span><strong>{{ $bookings->count() }}</strong></article>
        <article class="admin-mini-card"><span>Đã xác nhận</span><strong>{{ $bookings->where('status', 'confirmed')->count() }}</strong></article>
        <article class="admin-mini-card"><span>Đã thanh toán</span><strong>{{ $bookings->where('payment_status', 'success')->count() }}</strong></article>
    </div>

    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách đặt phòng</h2>
                <p>Dữ liệu lấy từ bảng <code>bookings</code>.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách</th>
                        <th>Homestay</th>
                        <th>Check in</th>
                        <th>Check out</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thanh toán</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->guest?->full_name ?? '-' }}</td>
                            <td>{{ $booking->homestay?->title ?? '-' }}</td>
                            <td>{{ optional($booking->check_in)->format('d/m/Y') }}</td>
                            <td>{{ optional($booking->check_out)->format('d/m/Y') }}</td>
                            <td>{{ number_format((float) $booking->total_amount, 0, ',', '.') }}</td>
                            <td>{{ $booking->status }}</td>
                            <td>{{ $booking->payment_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Chưa có dữ liệu trong bảng bookings.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
</section>
@endsection
