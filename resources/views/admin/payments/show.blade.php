@extends('admin.layout.app')

@section('title', 'Chi tiết thanh toán')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Xem nhanh thông tin thanh toán theo dạng bảng.</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.payments') }}" class="admin-btn admin-btn-secondary">
            Quay lại
        </a>
    </div>
</div>

@php
    $displayDate = $payment->paid_at ?? $payment->created_at;
    $paymentLabel = $payment->payment_status === \App\Models\Payment::STATUS_PENDING && $payment->paid_at
        ? 'Chờ duyệt'
        : $payment->statusLabel();
@endphp

<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <h3>Thông tin thanh toán</h3>
        @if($payment->payment_status === \App\Models\Payment::STATUS_PENDING && $payment->paid_at && $payment->booking)
            <form action="{{ route('admin.payments.confirm', $payment->booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="admin-btn admin-btn-primary" onclick="return confirm('Xác nhận admin đã nhận được khoản chuyển này?');">
                    Xác nhận đã nhận tiền
                </button>
            </form>
        @endif
    </div>
    <div class="admin-card-body">
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
    </div>
</div>
@endsection

