@extends('clients.layout.app')

@section('title', 'Thanh toán')

@section('content')
@php
    $homestayName = $booking->homestay_name ?? ($booking->homestay->name ?? '');
    $bookingCode = $booking->booking_code ?? '';
    $amountToPay = $amountToPay ?? $booking->total_amount ?? 0;
    $totalAmount = $booking->total_amount ?? $amountToPay;
@endphp
<style>@import url('{{ asset('css/clients/payment.css') }}');</style>

<section class="payment-page section-py">
  <div class="container-setting">
    <h1 class="payment-page-title">Thanh toán</h1>

    <div class="payment-layout">
      <div class="payment-main">
        <div class="payment-card">
          <h2 class="payment-card-title">Chọn phương thức thanh toán</h2>
          <div class="payment-methods">
            <a href="{{ request()->fullUrlWithQuery(['method' => 'vnpay']) }}" class="payment-method {{ ($paymentMethod ?? '') === 'vnpay' ? 'is-active' : '' }}">
              <i class="fa-solid fa-credit-card"></i>
              <span>VNPay</span>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['method' => 'momo']) }}" class="payment-method {{ ($paymentMethod ?? '') === 'momo' ? 'is-active' : '' }}">
              <i class="fa-solid fa-wallet"></i>
              <span>MoMo</span>
            </a>
          </div>

          <div class="payment-qr-section">
            <p class="payment-qr-label">Quét mã QR để thanh toán</p>
            <div class="payment-qr-wrap">
              <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode('NestAway|' . $bookingCode . '|' . number_format($amountToPay, 0, '', '') . '|' . ($paymentMethod ?? 'vnpay')) }}" alt="QR thanh toán" class="payment-qr-img">
            </div>
            <p class="payment-qr-amount">{{ number_format($amountToPay, 0, ',', '.') }}đ</p>
            @if($isDeposit ?? false)
              <p class="payment-qr-note">Đặt cọc — Thanh toán phần còn lại khi nhận phòng</p>
            @endif
            @if($isDemo ?? false)
              <p class="payment-qr-demo">Chế độ xem trước — Homestay mẫu</p>
            @endif
          </div>
        </div>
      </div>

      <aside class="payment-sidebar">
        <div class="payment-summary-card">
          <h3 class="payment-summary-title">Thông tin đơn hàng</h3>
          <p class="payment-summary-name">{{ $homestayName }}</p>
          <p class="payment-summary-code">Mã đặt phòng: <strong>{{ $bookingCode }}</strong></p>
          <div class="payment-summary-rows">
            <div class="payment-summary-row">
              <span>Tổng tiền</span>
              <span>{{ number_format($totalAmount, 0, ',', '.') }}đ</span>
            </div>
            @if($isDeposit ?? false)
              <div class="payment-summary-row payment-summary-row--highlight">
                <span>Thanh toán ngay (đặt cọc)</span>
                <span>{{ number_format($amountToPay, 0, ',', '.') }}đ</span>
              </div>
              <div class="payment-summary-row payment-summary-row--muted">
                <span>Còn lại khi nhận phòng</span>
                <span>{{ number_format($totalAmount - $amountToPay, 0, ',', '.') }}đ</span>
              </div>
            @else
              <div class="payment-summary-row payment-summary-row--highlight">
                <span>Thanh toán</span>
                <span>{{ number_format($amountToPay, 0, ',', '.') }}đ</span>
              </div>
            @endif
          </div>
          <a href="{{ route('home') }}" class="btn btn-outline-secondary payment-back-btn">Về trang chủ</a>
        </div>
      </aside>
    </div>
  </div>
</section>
@endsection
