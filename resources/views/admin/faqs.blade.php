@extends('admin.layout.app')

@section('title', 'FAQ')
@section('page_title', 'Quản lý FAQ')
@section('page_kicker', 'Nội dung trợ giúp')

@section('page_actions')
    <a href="#" class="admin-btn admin-btn-primary" data-modal-open="faq-modal" data-modal-mode="add" data-modal-entity="FAQ">
        <i class="bi bi-plus-circle"></i> Thêm FAQ
    </a>
@endsection

@section('content')
@php
    $faqs = [
        ['q' => 'Làm sao đổi ngày nhận phòng sau khi đã đặt?', 'a' => 'Khách có thể gửi yêu cầu qua trung tâm hỗ trợ hoặc chat nhanh trong vòng 24 giờ trước check-in.', 'category' => 'Booking', 'location' => 'Toàn quốc'],
        ['q' => 'Khi nào hệ thống giữ cọc tự động?', 'a' => 'Hệ thống giữ cọc ngay khi booking được xác nhận và trả về trạng thái thanh toán hợp lệ.', 'category' => 'Thanh toán', 'location' => 'Toàn quốc'],
        ['q' => 'Có thể ghép ưu đãi với mã giảm giá khác không?', 'a' => 'Chỉ một mã ưu đãi được áp dụng trên mỗi booking để đảm bảo chính sách giá rõ ràng.', 'category' => 'Ưu đãi', 'location' => 'Đà Lạt · Phú Quốc'],
    ];
@endphp

<section class="admin-page-section">
    <section class="admin-filter-section">
        <div class="admin-filter-copy">
            <strong>Bộ lọc dữ liệu</strong>
            <p>Lọc theo thời gian, nhóm nội dung và khu vực áp dụng.</p>
        </div>
        <div class="admin-filters">
            <div class="admin-filter-group"><input type="date" class="admin-date-input" value="2026-03-15"><span class="admin-range-sep">đến</span><input type="date" class="admin-date-input" value="2026-03-22"></div>
            <div class="admin-quick-filters"><button type="button" class="admin-quick-chip is-active">7 ngày</button><button type="button" class="admin-quick-chip">15 ngày</button><button type="button" class="admin-quick-chip">30 ngày</button></div>
            <select class="admin-select"><option>Tất cả danh mục</option><option>Booking</option><option>Thanh toán</option><option>Ưu đãi</option></select>
            <select class="admin-select"><option>Tất cả vị trí</option><option>Toàn quốc</option><option>Đà Lạt</option><option>Phú Quốc</option></select>
        </div>
    </section>

    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách FAQ</h2>
                <p>Lọc theo thời gian, nhóm nội dung và khu vực áp dụng.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Câu hỏi</th>
                        <th>Danh mục</th>
                        <th>Vị trí</th>
                        <th>Trả lời</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faqs as $faq)
                        <tr>
                            <td><strong>{{ $faq['q'] }}</strong></td>
                            <td>{{ $faq['category'] }}</td>
                            <td>{{ $faq['location'] }}</td>
                            <td>{{ $faq['a'] }}</td>
                            <td><span class="admin-pill pill-primary">Đang hiển thị</span></td>
                            <td>
                                <div class="admin-actions">
                                    <a href="#" class="admin-btn admin-btn-sm admin-btn-edit" data-modal-open="faq-modal" data-modal-mode="edit" data-modal-entity="FAQ">Sửa</a>
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

<div class="admin-modal" id="faq-modal" hidden>
    <div class="admin-modal-backdrop" data-modal-close></div>
    <div class="admin-modal-dialog">
        <div class="admin-modal-head">
            <h3 data-modal-dynamic-title>Thêm FAQ</h3>
            <button type="button" class="admin-modal-close" data-modal-close><i class="bi bi-x-lg"></i></button>
        </div>
        <form class="admin-form-grid">
            <div class="admin-form-group is-full"><label>Câu hỏi</label><input type="text" value="Làm sao đổi ngày nhận phòng sau khi đã đặt?"></div>
            <div class="admin-form-group"><label>Danh mục</label><select><option>Booking</option><option>Thanh toán</option><option>Ưu đãi</option></select></div>
            <div class="admin-form-group"><label>Vị trí áp dụng</label><input type="text" value="Toàn quốc"></div>
            <div class="admin-form-group is-full"><label>Trả lời</label><textarea rows="5">Khách có thể gửi yêu cầu qua trung tâm hỗ trợ hoặc chat nhanh trong vòng 24 giờ trước check-in.</textarea></div>
            <div class="admin-form-actions is-full">
                <button type="button" class="admin-btn admin-btn-secondary" data-modal-close>Hủy</button>
                <button type="submit" class="admin-btn admin-btn-primary">Lưu FAQ</button>
            </div>
        </form>
    </div>
</div>
@endsection
