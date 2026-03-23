@extends('admin.layout.app')

@section('title', 'Ưu đãi')
@section('page_title', 'Quản lý ưu đãi')
@section('page_kicker', 'Marketing & chuyển đổi')

@section('page_actions')
    <a href="#" class="admin-btn admin-btn-primary" data-modal-open="promotion-modal" data-modal-mode="add" data-modal-entity="ưu đãi">
        <i class="bi bi-plus-circle"></i> Thêm ưu đãi
    </a>
@endsection

@section('content')
@php
    $promotions = [
        ['name' => 'Summer Escape', 'desc' => 'Giảm 18% cho nhóm phòng biển từ thứ 2 đến thứ 5.', 'category' => 'Biển', 'location' => 'Phú Quốc · Nha Trang', 'tag' => ['pill-accent', 'Đang chạy']],
        ['name' => 'Stay Longer Save More', 'desc' => 'Ở từ 3 đêm trở lên giảm 12% cho villa gia đình.', 'category' => 'Villa', 'location' => 'Đà Lạt · Đà Nẵng', 'tag' => ['pill-success', 'Hiệu quả cao']],
        ['name' => 'Flash Sale cuối tuần', 'desc' => 'Ưu đãi trong 6 giờ cho nhóm phòng còn trống dưới 40%.', 'category' => 'Premium', 'location' => 'Toàn quốc', 'tag' => ['pill-warning', 'Sắp kích hoạt']],
    ];
@endphp

<section class="admin-page-section">
    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Tác động doanh thu</h2>
                <p>Biểu đồ tiến độ tĩnh cho từng nhóm chiến dịch.</p>
            </div>
        </div>
        <div class="admin-list">
            <div class="admin-mini-card"><span>Chiến dịch biển</span><strong>+22% booking</strong><div class="admin-progress"><span style="width: 78%;"></span></div></div>
            <div class="admin-mini-card"><span>Chiến dịch villa</span><strong>+16% doanh thu</strong><div class="admin-progress is-success"><span style="width: 66%;"></span></div></div>
            <div class="admin-mini-card"><span>Flash sale</span><strong>+9% lấp đầy</strong><div class="admin-progress is-accent"><span style="width: 48%;"></span></div></div>
        </div>
    </article>

    <section class="admin-filter-section">
        <div class="admin-filter-copy">
            <strong>Bộ lọc dữ liệu</strong>
            <p>Lọc theo thời gian, nhóm ưu đãi và vị trí áp dụng.</p>
        </div>
        <div class="admin-filters">
            <div class="admin-filter-group"><input type="date" class="admin-date-input" value="2026-03-15"><span class="admin-range-sep">đến</span><input type="date" class="admin-date-input" value="2026-03-22"></div>
            <div class="admin-quick-filters"><button type="button" class="admin-quick-chip is-active">7 ngày</button><button type="button" class="admin-quick-chip">15 ngày</button><button type="button" class="admin-quick-chip">30 ngày</button></div>
            <select class="admin-select"><option>Tất cả danh mục</option><option>Biển</option><option>Villa</option><option>Premium</option></select>
            <select class="admin-select"><option>Tất cả vị trí</option><option>Phú Quốc</option><option>Đà Lạt</option><option>Toàn quốc</option></select>
        </div>
    </section>

    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách ưu đãi</h2>
                <p>Lọc theo thời gian, danh mục và vị trí áp dụng của chiến dịch.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tên ưu đãi</th>
                        <th>Danh mục</th>
                        <th>Vị trí</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($promotions as $promotion)
                        <tr>
                            <td><strong>{{ $promotion['name'] }}</strong></td>
                            <td>{{ $promotion['category'] }}</td>
                            <td>{{ $promotion['location'] }}</td>
                            <td>{{ $promotion['desc'] }}</td>
                            <td><span class="admin-pill {{ $promotion['tag'][0] }}">{{ $promotion['tag'][1] }}</span></td>
                            <td>
                                <div class="admin-actions">
                                    <a href="#" class="admin-btn admin-btn-sm admin-btn-edit" data-modal-open="promotion-modal" data-modal-mode="edit" data-modal-entity="ưu đãi">Sửa</a>
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

<div class="admin-modal" id="promotion-modal" hidden>
    <div class="admin-modal-backdrop" data-modal-close></div>
    <div class="admin-modal-dialog">
        <div class="admin-modal-head">
            <h3 data-modal-dynamic-title>Thêm ưu đãi</h3>
            <button type="button" class="admin-modal-close" data-modal-close><i class="bi bi-x-lg"></i></button>
        </div>
        <form class="admin-form-grid">
            <div class="admin-form-group"><label>Tên ưu đãi</label><input type="text" value="Summer Escape"></div>
            <div class="admin-form-group"><label>Danh mục</label><select><option>Biển</option><option>Villa</option><option>Premium</option></select></div>
            <div class="admin-form-group"><label>Vị trí áp dụng</label><input type="text" value="Phú Quốc, Nha Trang"></div>
            <div class="admin-form-group"><label>Trạng thái</label><select><option>Đang chạy</option><option>Sắp kích hoạt</option><option>Tạm dừng</option></select></div>
            <div class="admin-form-group"><label>Mức giảm (%)</label><input type="number" value="18"></div>
            <div class="admin-form-group"><label>Mã ưu đãi</label><input type="text" value="SUMMER18"></div>
            <div class="admin-form-group is-full"><label>Mô tả</label><textarea rows="4">Giảm 18% cho nhóm phòng biển từ thứ 2 đến thứ 5.</textarea></div>
            <div class="admin-form-actions is-full">
                <button type="button" class="admin-btn admin-btn-secondary" data-modal-close>Hủy</button>
                <button type="submit" class="admin-btn admin-btn-primary">Lưu ưu đãi</button>
            </div>
        </form>
    </div>
</div>
@endsection
