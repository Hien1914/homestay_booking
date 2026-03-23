@extends('admin.layout.app')

@section('title', 'Khách hàng')
@section('page_title', 'Quản lý khách hàng')
@section('page_kicker', 'CRM nội bộ')

@section('page_actions')
    <a href="#" class="admin-btn admin-btn-primary" data-modal-open="user-modal" data-modal-mode="add" data-modal-entity="khách hàng">
        <i class="bi bi-plus-circle"></i> Thêm khách
    </a>
@endsection

@section('content')
@php
    $customers = [
        ['name' => 'Nhi Vương', 'email' => 'nhi@example.com', 'phone' => '0988 234 567', 'bookings' => '18 booking', 'status' => ['pill-success', 'Khách thân thiết']],
        ['name' => 'Tuấn Anh', 'email' => 'tuananh@example.com', 'phone' => '0912 887 642', 'bookings' => '6 booking', 'status' => ['pill-primary', 'Đang hoạt động']],
        ['name' => 'Minh Châu', 'email' => 'minhchau@example.com', 'phone' => '0971 551 326', 'bookings' => '2 booking', 'status' => ['pill-warning', 'Cần chăm sóc']],
        ['name' => 'Khánh Linh', 'email' => 'khanhlinh@example.com', 'phone' => '0905 611 221', 'bookings' => '0 booking', 'status' => ['pill-danger', 'Nguy cơ rời bỏ']],
    ];
@endphp

<section class="admin-page-section">
    <div class="admin-mini-grid">
        <article class="admin-mini-card"><span>Tổng khách hàng</span><strong>8.420</strong><div class="admin-progress"><span style="width: 76%;"></span></div></article>
        <article class="admin-mini-card"><span>Khách quay lại</span><strong>2.146</strong><div class="admin-progress is-success"><span style="width: 58%;"></span></div></article>
        <article class="admin-mini-card"><span>VIP / hạng cao</span><strong>284</strong><div class="admin-progress is-accent"><span style="width: 24%;"></span></div></article>
    </div>

    <section class="admin-filter-section">
        <div class="admin-filter-copy">
            <strong>Bộ lọc dữ liệu</strong>
            <p>Tách riêng phần tìm kiếm và phân khúc khách hàng.</p>
        </div>
        <div class="admin-filters">
            <label class="admin-search">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Tìm khách theo tên, email, số điện thoại">
            </label>
            <select class="admin-select"><option>Tất cả phân khúc</option><option>Khách thân thiết</option><option>Đang hoạt động</option><option>Cần chăm sóc</option></select>
        </div>
    </section>

    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách khách hàng</h2>
                <p>Giao diện bảng ưu tiên đọc nhanh tình trạng booking và phân khúc khách.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Khách hàng</th>
                        <th>Liên hệ</th>
                        <th>Lịch sử</th>
                        <th>Phân khúc</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td><div class="admin-table-user"><div class="admin-avatar">{{ strtoupper(substr($customer['name'], 0, 1)) }}</div><div><h3>{{ $customer['name'] }}</h3><p>{{ $customer['email'] }}</p></div></div></td>
                            <td>{{ $customer['phone'] }}</td>
                            <td>{{ $customer['bookings'] }}</td>
                            <td><span class="admin-pill {{ $customer['status'][0] }}">{{ $customer['status'][1] }}</span></td>
                            <td>
                                <div class="admin-actions">
                                    <a href="#" class="admin-btn admin-btn-sm admin-btn-edit" data-modal-open="user-modal" data-modal-mode="edit" data-modal-entity="khách hàng">Sửa</a>
                                    <a href="#" class="admin-btn admin-btn-sm admin-btn-danger">Xóa</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
</section>

<div class="admin-modal" id="user-modal" hidden>
    <div class="admin-modal-backdrop" data-modal-close></div>
    <div class="admin-modal-dialog">
        <div class="admin-modal-head">
            <h3 data-modal-dynamic-title>Thêm khách hàng</h3>
            <button type="button" class="admin-modal-close" data-modal-close><i class="bi bi-x-lg"></i></button>
        </div>
        <form class="admin-form-grid">
            <div class="admin-form-group"><label>Họ tên</label><input type="text" value="Nhi Vương"></div>
            <div class="admin-form-group"><label>Email</label><input type="email" value="nhi@example.com"></div>
            <div class="admin-form-group"><label>Số điện thoại</label><input type="text" value="0988 234 567"></div>
            <div class="admin-form-group"><label>Phân khúc</label><select><option>Khách thân thiết</option><option>Đang hoạt động</option><option>Cần chăm sóc</option></select></div>
            <div class="admin-form-group is-full"><label>Ghi chú nội bộ</label><textarea rows="4">Ưu tiên gợi ý nhóm phòng gia đình và villa nghỉ dưỡng.</textarea></div>
            <div class="admin-form-actions is-full">
                <button type="button" class="admin-btn admin-btn-secondary" data-modal-close>Hủy</button>
                <button type="submit" class="admin-btn admin-btn-primary">Lưu khách hàng</button>
            </div>
        </form>
    </div>
</div>
@endsection
