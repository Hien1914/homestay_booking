@extends('host.layout.app')

@section('title', 'Yêu cầu rút tiền')

@section('content')
<div class="admin-page-header mb-4">
    <a href="{{ route('host.payouts.index') }}" class="btn btn-link text-decoration-none p-0 mb-2">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
    <h1 class="admin-page-title">Yêu cầu rút tiền</h1>
    <p class="admin-page-subtitle">Nhập số tiền bạn muốn rút về tài khoản</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="admin-">Thông tin rút tiền</h5>
            </div>
            <div class="admin-card-body p-4">
                <div class="mb-4 p-3 rounded-3" style="background: rgba(59, 130, 246, 0.05); border: 1px dashed var(--primary-color);">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Số dư khả dụng hiện tại:</span>
                        <h4 class="mb-0 fw-bold text-primary">{{ number_format($available) }}đ</h4>
                    </div>
                </div>

                <form action="{{ route('host.payouts.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold">Số tiền muốn rút (VNĐ)</label>
                        <div class="input-group">
                            <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" 
                                placeholder="Tối thiểu 100,000đ" min="100000" max="{{ $available }}" required>
                            <span class="input-group-text">đ</span>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-2">
                            <i class="bi bi-info-circle"></i> Số tiền rút tối thiểu là 100,000đ.
                        </div>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm rounded-3">
                        <h6 class="alert-heading fw-bold"><i class="bi bi-shield-check"></i> Lưu ý:</h6>
                        <ul class="mb-0 small ps-3">
                            <li>Yêu cầu sẽ được admin kiểm tra và xử lý trong vòng 24h-48h làm việc.</li>
                            <li>Tiền sẽ được chuyển về tài khoản ngân hàng bạn đã đăng ký.</li>
                            <li>Đảm bảo thông tin tài khoản của bạn là chính xác.</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg py-3 rounded-3" {{ $available < 100000 ? 'disabled' : '' }}>
                            <i class="bi bi-send-fill me-2"></i> Gửi yêu cầu rút tiền
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h5 class="admin-">Hướng dẫn & Quy định</h5>
            </div>
            <div class="admin-card-body p-4">
                <div class="d-flex flex-column gap-4">
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; color: var(--primary-color);">
                                1
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Kiểm tra số dư</h6>
                            <p class="text-muted small mb-0">Chỉ có các đơn đặt phòng đã hoàn tất (Completed) mới được tính vào thu nhập khả dụng của bạn.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; color: var(--primary-color);">
                                2
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Gửi yêu cầu</h6>
                            <p class="text-muted small mb-0">Nhập số tiền bạn muốn rút. Hệ thống sẽ tạm giữ số tiền này cho đến khi admin xử lý xong.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; color: var(--primary-color);">
                                3
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Duyệt & Chuyển tiền</h6>
                            <p class="text-muted small mb-0">Admin sẽ kiểm tra lịch sử giao dịch và thực hiện lệnh chuyển khoản qua ngân hàng. Trạng thái sẽ chuyển thành "Thành công".</p>
                        </div>
                    </div>
                    
                    <div class="mt-auto p-3 bg-light rounded-3">
                        <p class="small mb-0 text-center">Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ <a href="mailto:support@nestaway.com" class="text-primary fw-bold">Bộ phận hỗ trợ</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

