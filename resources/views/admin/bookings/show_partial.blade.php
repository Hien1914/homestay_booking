@php
    $paymentClass = 'admin-badge-pending';
    $paymentLabel = 'Đang chờ thanh toán';
    if ($booking->payment) {
        $paymentClass = $booking->payment->displayStatusBadgeClass();
        $paymentLabel = $booking->payment->displayStatusLabel();
    }
@endphp

<div class="admin-table-wrap">
    <table class="admin-table">
        <tbody>
            <tr>
                <th>Mã đơn</th>
                <td><span class="admin-id-badge">#{{ $booking->id }}</span></td>
            </tr>
            <tr>
                <th>Tên khách</th>
                <td>{{ $booking->user?->full_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Homestay</th>
                <td>{{ $booking->homestay?->title ?? '-' }}</td>
            </tr>
            <tr>
                <th>Lịch trình</th>
                <td>{{ optional($booking->check_in)->format('d/m/Y') ?? '-' }} - {{ optional($booking->check_out)->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Số tiền</th>
                <td class="fw-bold text-success">{{ number_format((float) $booking->total_amount, 0, ',', '.') }}đ</td>
            </tr>
            <tr>
                <th>Trạng thái đơn hàng</th>
                <td><span class="admin-badge {{ $booking->statusBadgeClass() }}">{{ $booking->statusLabel() }}</span></td>
            </tr>
            <tr>
                <th>Trạng thái thanh toán</th>
                <td><span class="admin-badge {{ $paymentClass }}">{{ $paymentLabel }}</span></td>
            </tr>
        </tbody>
    </table>
</div>
