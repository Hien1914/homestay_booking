@extends('clients.layout.app')

@section('title', 'Thanh toán')

@section('content')
@php
    $homestayName = $booking->homestay_title ?? ($booking->homestay->name ?? 'Homestay Demo');
    $customerName = $booking->user->name ?? ($booking->user->full_name ?? 'Khách hàng Demo');
    $bookingCode = '#' . ($booking->id ?? 'DEMO');
    $amountToPay = $amountToPay ?? $booking->total_amount ?? 0;
    $totalAmount = $booking->total_amount ?? $amountToPay;
@endphp
<style>@import url('{{ asset('css/clients/payment.css') }}');</style>

<section class="payment-page container-setting py-5">
    <div class="row">
        <!-- Sidebar - Thông tin đơn hàng -->
        <aside class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="card-title fw-bold mb-4" style="color: #2E7D32;">Xác nhận đơn hàng</h4>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Homestay:</span>
                        <span class="fw-semibold text-end">{{ $homestayName }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Mã đặt phòng:</span>
                        <span class="fw-bold text-dark">#{{ $booking->id }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Khách hàng:</span>
                        <span class="fw-medium text-end">{{ $customerName }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Tổng tiền:</span>
                        <span class="fw-bold fs-5" style="color: #d32f2f;">{{ number_format($totalAmount, 0, ',', '.') }} đ</span>
                    </div>

                    @if($isDemo ?? false)
                        <div class="alert alert-warning mt-3 mb-0" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Chế độ xem trước — Homestay mẫu
                        </div>
                    @else
                        <div class="alert alert-info mt-3 mb-0" role="alert">
                            <i class="fa-solid fa-circle-info me-2"></i> Vui lòng thanh toán để hoàn tất đặt phòng
                        </div>
                    @endif
                </div>
            </div>
        </aside>

        <!-- Main Content - Thanh toán QR -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 p-4 text-center">
                <h3 class="fw-bold mb-4">Quét mã QR để hoàn tất thanh toán</h3>
                
                <div id="qr-loading" class="my-5">
                    <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="mt-3 text-muted">Đang khởi tạo mã QR...</p>
                </div>

                <div id="qr-success-box" style="display: none;">
                    <!-- QR Code Image -->
                    <div class="d-flex justify-content-center mb-4">
                        <div class="p-3 bg-white border rounded shadow-sm" style="display: inline-block;">
                            <img id="qr-img" src="" alt="Mã QR Thanh toán" style="max-width: 300px; width: 100%; border-radius: 8px;">
                        </div>
                    </div>

                    <!-- Bank Info -->
                    <div class="bg-light p-3 rounded-3 mx-auto mb-4 text-start" style="max-width: 400px; border: 1px solid #dee2e6;">
                        <p class="mb-1"><span class="text-muted">Ngân hàng:</span> <strong id="bank-name"></strong></p>
                        <p class="mb-1"><span class="text-muted">Số tài khoản:</span> <strong id="bank-account"></strong></p>
                        <p class="mb-1"><span class="text-muted">Chủ tài khoản:</span> <strong id="account-name"></strong></p>
                        <p class="mb-1"><span class="text-muted">Số tiền cần chuyển:</span> <strong id="amount-text" class="text-danger fs-5"></strong></p>
                    </div>

                    <!-- Confirmation Form -->
                    <div class="p-4 border rounded-3 bg-white shadow-sm mx-auto" style="max-width: 500px;">
                        <div class="alert alert-warning text-start small mb-3">
                            <i class="fa-solid fa-circle-exclamation me-1"></i>
                            Nội dung chuyển khoản được tạo tự động theo mã đặt phòng. Bạn chỉ cần sao chép đúng nội dung này vào app ngân hàng, sau đó nhấn xác nhận để hệ thống kiểm tra.
                        </div>

                        <form id="payment-form" method="POST" action="{{ route('payment.confirm') }}">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <label for="transaction-code" class="d-block mb-2 fw-bold text-dark text-start">Nội dung chuyển khoản tự động</label>
                            
                            <div class="input-group mb-4">
                                <input type="text" 
                                    id="transaction-code" 
                                    name="transaction_code"
                                    class="form-control text-center text-uppercase fw-bold fs-5" 
                                    style="border: 2px dashed #d32f2f; color: #d32f2f; font-family: monospace; cursor: pointer;"
                                    readonly>
                                <button class="btn btn-outline-danger" type="button" id="copy-btn">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold" id="confirm-btn">
                                <span id="btn-text">TÔI ĐÃ THANH TOÁN</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div id="qr-error-box" class="alert alert-danger mx-auto mt-4" style="max-width: 400px; display: none;">
                    <i class="fa-solid fa-circle-xmark me-2"></i> <span>Không thể tải mã QR. Vui lòng tải lại trang.</span>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Fetch QR
    fetch("{{ route('generate.qr') }}", {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('qr-loading').style.display = 'none';
        
        if(data.success && data.qr_image) {
            document.getElementById('qr-success-box').style.display = 'block';
            
            // Generate proxy url to avoid CORS if necessary
            let qrUrl = "{{ route('proxy.qr') }}?url=" + encodeURIComponent(data.qr_image);
            document.getElementById('qr-img').src = qrUrl;
            
            // Fill Bank Info
            document.getElementById('bank-name').innerText = data.bank_info.bank;
            document.getElementById('bank-account').innerText = data.bank_info.account;
            document.getElementById('account-name').innerText = data.bank_info.account_name;
            document.getElementById('amount-text').innerText = new Intl.NumberFormat('vi-VN').format(data.bank_info.amount) + ' đ';
            
            // Fill Transfer Code
            document.getElementById('transaction-code').value = data.transfer_code;
        } else {
            document.getElementById('qr-error-box').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Lỗi khi lấy mã QR:', error);
        document.getElementById('qr-loading').style.display = 'none';
        document.getElementById('qr-error-box').style.display = 'block';
    });

    // 2. Logic cho nút copy
    const copyBtn = document.getElementById('copy-btn');
    const transactionInput = document.getElementById('transaction-code');
    
    if (copyBtn && transactionInput) {
        copyBtn.addEventListener('click', function() {
            transactionInput.select();
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            
            // Đổi icon
            copyBtn.innerHTML = '<i class="fa-solid fa-check"></i>';
            copyBtn.classList.replace('btn-outline-danger', 'btn-danger');
            
            setTimeout(() => {
                copyBtn.innerHTML = '<i class="fa-regular fa-copy"></i>';
                copyBtn.classList.replace('btn-danger', 'btn-outline-danger');
            }, 2000);
            
            toastMessage({ title: "Thành công", message: "Đã sao chép thẻ nội dung chuyển khoản!", type: "success" });
        });

        transactionInput.addEventListener('click', function() {
            transactionInput.select();
            document.execCommand('copy');
            toastMessage({ title: "Thành công", message: "Đã sao chép thẻ nội dung chuyển khoản!", type: "success" });
        });
    }
});
</script>
@endpush
@endsection
