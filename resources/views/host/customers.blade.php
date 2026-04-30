@extends('host.layout.app')

@section('title', 'Quản lý khách hàng')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Danh sách khách hàng đã đặt phòng tại chỗ nghỉ của bạn</p>
    </div>
</div>

<!-- No search bar as per request -->
<div class="mb-4"></div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-people me-2 text-primary"></i>Danh sách khách hàng
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Khách hàng</th>
                        <th>Email</th>
                        <th class="text-center">Số điện thoại</th>
                        <th class="text-center">Ngân hàng</th>
                        <th class="text-center">Số tài khoản</th>
                        <th class="text-center">Số lần đặt</th>
                        <th class="text-end pe-4">Tổng chi tiêu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td class="ps-4"><span class="admin-id-badge">#{{ $customer->id }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $customer->full_name }}</div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $customer->email }}</div>
                            </td>
                            <td class="text-center">
                                <div class="text-muted">{{ $customer->phone ?? 'Chưa cập nhật' }}</div>
                            </td>
                            <td class="text-center">
                                <div class="text-muted">{{ $customer->bank_name ?? 'Chưa cập nhật' }}</div>
                            </td>
                            <td class="text-center">
                                <div class="text-muted">{{ $customer->bank_account_number ?? 'Chưa cập nhật' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="admin-badge admin-badge-success">{{ $customer->bookings_count }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="fw-bold text-success">{{ number_format($customer->total_spent ?? 0) }}đ</div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-5">Chưa có khách hàng nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
            <div class="p-4 border-top">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
