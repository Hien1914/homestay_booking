@php
    $displayDate = $payment->paid_at ?? $payment->created_at;
    $paymentLabel = $payment->payment_status === \App\Models\Payment::STATUS_PENDING && $payment->paid_at
        ? 'Chờ duyệt'
        : $payment->statusLabel();
@endphp

<div class="admin-table-wrap">
    <table class="admin-table">
        <tbody>
            <tr>
                <th>Mã đơn</th>
                <td><span class="admin-id-badge">#{{ $payment->booking?->id ?? '-' }}</span></td>
            </tr>
            <tr>
                <th>Người dùng</th>
                <td>{{ $payment->booking?->user?->full_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tên homestay</th>
                <td>{{ $payment->booking?->homestay?->title ?? '-' }}</td>
            </tr>
            <tr>
                <th>Số tiền</th>
                <td class="fw-bold text-success">{{ number_format((float) $payment->amount, 0, ',', '.') }} đ</td>
            </tr>
            <tr>
                <th>Trạng thái</th>
                <td><span class="admin-badge {{ $payment->statusBadgeClass() }}">{{ $paymentLabel }}</span></td>
            </tr>
            <tr>
                <th>Ngày</th>
                <td>{{ optional($displayDate)->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
</div>
