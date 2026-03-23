@extends('admin.layout.app')

@section('title', 'Quản lý phòng')
@section('page_title', 'Quản lý phòng')
@section('page_kicker', 'Inventory & vận hành')

@section('page_actions')
    <a href="#" class="admin-btn admin-btn-primary" data-modal-open="homestay-modal" data-modal-mode="add" data-modal-entity="phòng">
        <i class="bi bi-plus-circle"></i> Thêm phòng
    </a>
@endsection

@section('content')
@php
    $rooms = [
        ['name' => 'Nest Signature Đà Lạt', 'location' => 'Đà Lạt', 'category' => 'Villa', 'type' => 'Villa 4PN', 'price' => '4.800.000đ', 'fill' => '96%', 'status' => ['pill-success', 'Đang khai thác']],
        ['name' => 'Sea Breeze Phú Quốc', 'location' => 'Phú Quốc', 'category' => 'Biển', 'type' => 'Suite đôi', 'price' => '3.400.000đ', 'fill' => '88%', 'status' => ['pill-primary', 'Bán tốt']],
        ['name' => 'Urban Loft Sài Gòn', 'location' => 'TP.HCM', 'category' => 'City stay', 'type' => 'Studio', 'price' => '1.450.000đ', 'fill' => '72%', 'status' => ['pill-warning', 'Cần tối ưu']],
        ['name' => 'Cloud Nine Sa Pa', 'location' => 'Sa Pa', 'category' => 'Núi', 'type' => 'Cabin view núi', 'price' => '2.900.000đ', 'fill' => '43%', 'status' => ['pill-danger', 'Đang rà soát']],
    ];
@endphp

<section class="admin-page-section">
    <div class="admin-mini-grid">
        <article class="admin-mini-card"><span>Tổng số phòng</span><strong>284</strong><div class="admin-progress"><span style="width: 84%;"></span></div></article>
        <article class="admin-mini-card"><span>Nhóm phòng premium</span><strong>116</strong><div class="admin-progress is-success"><span style="width: 61%;"></span></div></article>
        <article class="admin-mini-card"><span>Đang cần tối ưu giá</span><strong>38</strong><div class="admin-progress is-accent"><span style="width: 34%;"></span></div></article>
    </div>

    <section class="admin-filter-section">
        <div class="admin-filter-copy">
            <strong>Bộ lọc dữ liệu</strong>
            <p>Lọc theo thời gian, danh mục và vị trí của từng phòng.</p>
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
            <select class="admin-select"><option>Tất cả danh mục</option><option>Villa</option><option>Biển</option><option>Núi</option><option>City stay</option></select>
            <select class="admin-select"><option>Tất cả vị trí</option><option>Đà Lạt</option><option>Phú Quốc</option><option>TP.HCM</option><option>Sa Pa</option></select>
        </div>
    </section>

    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách phòng</h2>
                <p>Lọc theo thời gian, danh mục và vị trí để theo dõi từng nhóm phòng chi tiết hơn.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Phòng</th>
                        <th>Danh mục</th>
                        <th>Loại</th>
                        <th>Giá/đêm</th>
                        <th>Lấp đầy</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                        <tr>
                            <td><div class="admin-table-user"><div class="admin-avatar">{{ strtoupper(substr($room['name'], 0, 1)) }}</div><div><h3>{{ $room['name'] }}</h3><p>{{ $room['location'] }}</p></div></div></td>
                            <td>{{ $room['category'] }}</td>
                            <td>{{ $room['type'] }}</td>
                            <td>{{ $room['price'] }}</td>
                            <td>{{ $room['fill'] }}</td>
                            <td><span class="admin-pill {{ $room['status'][0] }}">{{ $room['status'][1] }}</span></td>
                            <td>
                                <div class="admin-actions">
                                    <a href="#" class="admin-btn admin-btn-sm admin-btn-edit" data-modal-open="homestay-modal" data-modal-mode="edit" data-modal-entity="phòng">Sửa</a>
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

<div class="admin-modal" id="homestay-modal" hidden>
    <div class="admin-modal-backdrop" data-modal-close></div>
    <div class="admin-modal-dialog">
        <div class="admin-modal-head">
            <h3 data-modal-dynamic-title>Thêm phòng</h3>
            <button type="button" class="admin-modal-close" data-modal-close><i class="bi bi-x-lg"></i></button>
        </div>
        <form class="admin-form-grid">
            <div class="admin-form-group"><label>Tên phòng</label><input type="text" value="Nest Signature Đà Lạt"></div>
            <div class="admin-form-group"><label>Danh mục</label><select><option>Villa</option><option>Biển</option><option>Núi</option></select></div>
            <div class="admin-form-group"><label>Loại phòng</label><input type="text" value="Villa 4PN"></div>
            <div class="admin-form-group"><label>Vị trí</label><input type="text" value="Đà Lạt"></div>
            <div class="admin-form-group"><label>Giá/đêm</label><input type="text" value="4800000"></div>
            <div class="admin-form-group"><label>Trạng thái</label><select><option>Đang khai thác</option><option>Tạm ẩn</option></select></div>
            <div class="admin-form-group is-full"><label>Mô tả ngắn</label><textarea rows="4">Villa cao cấp dành cho nhóm gia đình và khách nghỉ dưỡng.</textarea></div>
            <div class="admin-form-actions is-full">
                <button type="button" class="admin-btn admin-btn-secondary" data-modal-close>Hủy</button>
                <button type="submit" class="admin-btn admin-btn-primary">Lưu phòng</button>
            </div>
        </form>
    </div>
</div>
@endsection
