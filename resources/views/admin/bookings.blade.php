@extends('admin.layout.app')

@section('title', 'Đặt phòng')
@section('page_title', 'Quản lý đặt phòng')
@section('page_kicker', 'Booking flow')

@section('content')
@php
    $bookings = [
        ['code' => '#BK1024', 'guest' => 'Mai Hương', 'room' => 'Nest Signature Đà Lạt', 'category' => 'Villa', 'location' => 'Đà Lạt', 'amount' => '9.600.000đ', 'status' => ['pill-success', 'Đã thanh toán']],
        ['code' => '#BK1025', 'guest' => 'Đức Huy', 'room' => 'Sea Breeze Phú Quốc', 'category' => 'Biển', 'location' => 'Phú Quốc', 'amount' => '6.800.000đ', 'status' => ['pill-warning', 'Chờ xác nhận']],
        ['code' => '#BK1026', 'guest' => 'Hà My', 'room' => 'Urban Loft Sài Gòn', 'category' => 'City stay', 'location' => 'TP.HCM', 'amount' => '2.900.000đ', 'status' => ['pill-primary', 'Đã giữ chỗ']],
        ['code' => '#BK1027', 'guest' => 'Gia Hân', 'room' => 'Cloud Nine Sa Pa', 'category' => 'Núi', 'location' => 'Sa Pa', 'amount' => '5.800.000đ', 'status' => ['pill-danger', 'Cần xử lý']],
    ];
@endphp

<section class="admin-page-section">
    <div class="admin-grid-stats">
        <article class="admin-stat-card"><div class="admin-stat-label">Tổng booking hôm nay</div><div class="admin-stat-value">146</div><div class="admin-stat-meta is-up"><i class="bi bi-arrow-up-right"></i> +18 booking so với hôm qua</div></article>
        <article class="admin-stat-card"><div class="admin-stat-label">Tỷ lệ xác nhận</div><div class="admin-stat-value">87%</div><div class="admin-stat-meta is-up"><i class="bi bi-check2-circle"></i> Luồng xác nhận ổn định</div></article>
        <article class="admin-stat-card"><div class="admin-stat-label">Chờ xử lý</div><div class="admin-stat-value">29</div><div class="admin-stat-meta is-warm"><i class="bi bi-hourglass-split"></i> Tập trung tại nhóm phòng biển</div></article>
        <article class="admin-stat-card"><div class="admin-stat-label">Hủy trong ngày</div><div class="admin-stat-value">8</div><div class="admin-stat-meta is-down"><i class="bi bi-x-circle"></i> Chủ yếu từ nhóm khách mới</div></article>
    </div>

    <section class="admin-filter-section">
        <div class="admin-filter-copy">
            <strong>Bộ lọc dữ liệu</strong>
            <p>Lọc theo thời gian, danh mục phòng và vị trí đặt.</p>
        </div>
        <div class="admin-filters">
            <div class="admin-filter-group">
                <input type="date" class="admin-date-input" value="2026-03-15">
                <span class="admin-range-sep">đến</span>
                <input type="date" class="admin-date-input" value="2026-03-22">
            </div>
            <div class="admin-quick-filters">
                <button type="button" class="admin-quick-chip is-active">7 ngày</button>
                <button type="button" class="admin-quick-chip">15 ngày</button>
                <button type="button" class="admin-quick-chip">30 ngày</button>
            </div>
            <select class="admin-select">
                <option>Tất cả danh mục</option>
                <option>Villa</option>
                <option>Biển</option>
                <option>Núi</option>
                <option>City stay</option>
            </select>
            <select class="admin-select">
                <option>Tất cả vị trí</option>
                <option>Đà Lạt</option>
                <option>Phú Quốc</option>
                <option>TP.HCM</option>
                <option>Sa Pa</option>
            </select>
        </div>
    </section>

    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách đặt phòng</h2>
                <p>Dữ liệu chi tiết được mô phỏng theo thời gian, danh mục phòng và vị trí đặt.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã booking</th>
                        <th>Khách</th>
                        <th>Phòng</th>
                        <th>Danh mục</th>
                        <th>Vị trí</th>
                        <th>Giá trị</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td><strong>{{ $booking['code'] }}</strong></td>
                            <td>{{ $booking['guest'] }}</td>
                            <td>{{ $booking['room'] }}</td>
                            <td>{{ $booking['category'] }}</td>
                            <td>{{ $booking['location'] }}</td>
                            <td>{{ $booking['amount'] }}</td>
                            <td><span class="admin-pill {{ $booking['status'][0] }}">{{ $booking['status'][1] }}</span></td>
                            <td><a href="#" class="admin-link-chip">Mở chi tiết</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
</section>
@endsection
