@extends('admin.layout.app')

@section('title', 'Đánh giá')
@section('page_title', 'Quản lý đánh giá')
@section('page_kicker', 'Chất lượng dịch vụ')

@section('content')
@php
    $reviews = [
        ['guest' => 'Ngọc Mai', 'room' => 'Sea Breeze Phú Quốc', 'category' => 'Biển', 'location' => 'Phú Quốc', 'score' => '4.9/5', 'comment' => 'Phòng đẹp, nhân viên phản hồi nhanh, rất đáng quay lại.', 'tag' => ['pill-success', 'Tích cực']],
        ['guest' => 'Thiên Phúc', 'room' => 'Urban Loft Sài Gòn', 'category' => 'City stay', 'location' => 'TP.HCM', 'score' => '4.2/5', 'comment' => 'Vị trí tốt, cần cải thiện thêm phần check-in muộn.', 'tag' => ['pill-primary', 'Cần phản hồi']],
        ['guest' => 'Minh Đức', 'room' => 'Cloud Nine Sa Pa', 'category' => 'Núi', 'location' => 'Sa Pa', 'score' => '3.6/5', 'comment' => 'View đẹp nhưng đường đi ban đêm hơi khó tìm.', 'tag' => ['pill-warning', 'Theo dõi']],
    ];
@endphp

<section class="admin-page-section">
    <div class="admin-three-col">
        <article class="admin-card"><div class="admin-stat-label">Điểm trung bình</div><div class="admin-stat-value">4.7/5</div><div class="admin-stat-meta is-up"><i class="bi bi-star-fill"></i> 92% phản hồi tích cực</div></article>
        <article class="admin-card"><div class="admin-stat-label">Đánh giá mới</div><div class="admin-stat-value">84</div><div class="admin-stat-meta is-up"><i class="bi bi-chat-square-heart"></i> Tăng mạnh ở nhóm biển</div></article>
        <article class="admin-card"><div class="admin-stat-label">Cần chăm sóc</div><div class="admin-stat-value">11</div><div class="admin-stat-meta is-warm"><i class="bi bi-exclamation-circle"></i> Ưu tiên phản hồi trong 2 giờ</div></article>
    </div>

    <section class="admin-filter-section">
        <div class="admin-filter-copy">
            <strong>Bộ lọc dữ liệu</strong>
            <p>Lọc theo thời gian, danh mục phòng và vị trí đánh giá.</p>
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
                <option>Biển</option>
                <option>Núi</option>
                <option>City stay</option>
            </select>
            <select class="admin-select">
                <option>Tất cả vị trí</option>
                <option>Phú Quốc</option>
                <option>TP.HCM</option>
                <option>Sa Pa</option>
            </select>
        </div>
    </section>

    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách đánh giá</h2>
                <p>Lọc theo thời gian, danh mục phòng và vị trí để theo dõi phản hồi chất lượng.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Khách</th>
                        <th>Phòng</th>
                        <th>Danh mục</th>
                        <th>Vị trí</th>
                        <th>Điểm</th>
                        <th>Nội dung</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td><strong>{{ $review['guest'] }}</strong></td>
                            <td>{{ $review['room'] }}</td>
                            <td>{{ $review['category'] }}</td>
                            <td>{{ $review['location'] }}</td>
                            <td>{{ $review['score'] }}</td>
                            <td>{{ $review['comment'] }}</td>
                            <td><span class="admin-pill {{ $review['tag'][0] }}">{{ $review['tag'][1] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
</section>
@endsection
