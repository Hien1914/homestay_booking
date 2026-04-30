@php
    $statusClass = match ($booking->status) {
        \App\Models\Booking::STATUS_PENDING => 'admin-badge-pending',
        \App\Models\Booking::STATUS_CONFIRMED => 'admin-badge-confirmed',
        \App\Models\Booking::STATUS_CHECKED_IN => 'admin-badge-ongoing',
        \App\Models\Booking::STATUS_COMPLETED => 'admin-badge-success',
        \App\Models\Booking::STATUS_CANCELLED => 'admin-badge-cancelled',
        default => 'admin-badge-secondary',
    };

    $paymentClass = 'admin-badge-secondary';
    $paymentLabel = 'Chưa thanh toán';
    if ($booking->payment) {
        $paymentClass = match ($booking->payment->payment_status) {
            \App\Models\Payment::STATUS_SUCCESS => 'admin-badge-success',
            \App\Models\Payment::STATUS_PENDING => ($booking->payment->paid_at ? 'admin-badge-info' : 'admin-badge-pending'),
            default => 'admin-badge-secondary',
        };
        $paymentLabel = $booking->payment->payment_status === \App\Models\Payment::STATUS_PENDING && $booking->payment->paid_at
            ? 'Chờ duyệt'
            : $booking->payment->statusLabel();
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
                <td><span class="admin-badge {{ $statusClass }}">{{ $booking->statusLabel() }}</span></td>
            </tr>
            <tr>
                <th>Trạng thái thanh toán</th>
                <td><span class="admin-badge {{ $paymentClass }}">{{ $paymentLabel }}</span></td>
            </tr>
        </tbody>
    </table>
</div>
