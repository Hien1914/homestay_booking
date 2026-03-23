@extends('admin.layout.app')

@section('title', 'Blog')
@section('page_title', 'Quản lý blog')
@section('page_kicker', 'Nội dung & SEO')

@section('page_actions')
    <a href="#" class="admin-btn admin-btn-primary" data-modal-open="blog-modal" data-modal-mode="add" data-modal-entity="bài viết">
        <i class="bi bi-plus-circle"></i> Thêm bài viết
    </a>
@endsection

@section('content')
@php
    $blogs = [
        ['title' => '7 homestay ven biển đáng thử hè này', 'author' => 'Team Content', 'category' => 'Biển', 'location' => 'Phú Quốc · Nha Trang', 'status' => ['pill-success', 'Đã xuất bản']],
        ['title' => 'Kinh nghiệm chọn villa nhóm đông người', 'author' => 'Lan Anh', 'category' => 'Villa', 'location' => 'Đà Lạt · Đà Nẵng', 'status' => ['pill-primary', 'Chờ duyệt']],
        ['title' => 'Checklist tối ưu ảnh phòng để tăng booking', 'author' => 'Growth Team', 'category' => 'Premium', 'location' => 'Toàn quốc', 'status' => ['pill-warning', 'Đang biên tập']],
    ];
@endphp

<section class="admin-page-section">
    <div class="admin-three-col">
        <article class="admin-card"><div class="admin-stat-label">Bài viết đang live</div><div class="admin-stat-value">42</div><div class="admin-stat-meta is-up"><i class="bi bi-journal-check"></i> +6 bài trong tháng</div></article>
        <article class="admin-card"><div class="admin-stat-label">Lượt đọc trung bình</div><div class="admin-stat-value">18,4k</div><div class="admin-stat-meta is-up"><i class="bi bi-graph-up"></i> Tăng nhờ chủ đề biển</div></article>
        <article class="admin-card"><div class="admin-stat-label">Bài cần cập nhật</div><div class="admin-stat-value">9</div><div class="admin-stat-meta is-warm"><i class="bi bi-pencil-square"></i> Ưu tiên SEO quý này</div></article>
    </div>

    <section class="admin-filter-section">
        <div class="admin-filter-copy">
            <strong>Bộ lọc dữ liệu</strong>
            <p>Lọc theo thời gian, chủ đề bài viết và vị trí điểm đến.</p>
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
                <h2>Thư viện bài viết</h2>
                <p>Lọc theo thời gian, chủ đề danh mục và vị trí điểm đến.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tiêu đề</th>
                        <th>Tác giả</th>
                        <th>Danh mục</th>
                        <th>Vị trí</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blogs as $blog)
                        <tr>
                            <td><strong>{{ $blog['title'] }}</strong></td>
                            <td>{{ $blog['author'] }}</td>
                            <td>{{ $blog['category'] }}</td>
                            <td>{{ $blog['location'] }}</td>
                            <td><span class="admin-pill {{ $blog['status'][0] }}">{{ $blog['status'][1] }}</span></td>
                            <td>
                                <div class="admin-actions">
                                    <a href="#" class="admin-btn admin-btn-sm admin-btn-edit" data-modal-open="blog-modal" data-modal-mode="edit" data-modal-entity="bài viết">Sửa</a>
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

<div class="admin-modal" id="blog-modal" hidden>
    <div class="admin-modal-backdrop" data-modal-close></div>
    <div class="admin-modal-dialog">
        <div class="admin-modal-head">
            <h3 data-modal-dynamic-title>Thêm bài viết</h3>
            <button type="button" class="admin-modal-close" data-modal-close><i class="bi bi-x-lg"></i></button>
        </div>
        <form class="admin-form-grid">
            <div class="admin-form-group is-full"><label>Tiêu đề</label><input type="text" value="7 homestay ven biển đáng thử hè này"></div>
            <div class="admin-form-group"><label>Danh mục</label><select><option>Biển</option><option>Villa</option><option>Premium</option></select></div>
            <div class="admin-form-group"><label>Vị trí</label><input type="text" value="Phú Quốc, Nha Trang"></div>
            <div class="admin-form-group"><label>Tác giả</label><input type="text" value="Team Content"></div>
            <div class="admin-form-group"><label>Trạng thái</label><select><option>Đã xuất bản</option><option>Chờ duyệt</option><option>Đang biên tập</option></select></div>
            <div class="admin-form-group is-full"><label>Tóm tắt</label><textarea rows="4">Gợi ý các homestay ven biển nổi bật cho kỳ nghỉ hè.</textarea></div>
            <div class="admin-form-actions is-full">
                <button type="button" class="admin-btn admin-btn-secondary" data-modal-close>Hủy</button>
                <button type="submit" class="admin-btn admin-btn-primary">Lưu bài viết</button>
            </div>
        </form>
    </div>
</div>
@endsection
