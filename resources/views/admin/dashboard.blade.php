@extends('admin.layout.app')

@section('title', 'Tổng quan')
@section('page_title', 'Bảng điều khiển tổng')
@section('page_kicker', 'Điều phối vận hành')

@section('page_actions')
    <a href="{{ route('admin.bookings') }}" class="admin-btn admin-btn-primary">
        <i class="bi bi-graph-up-arrow"></i>
        Theo dõi đặt phòng
    </a>
@endsection

@section('content')
@php
    $stats = [
        ['label' => 'Doanh thu tháng này', 'value' => '1,86 tỷ', 'meta' => '+12,4% so với tháng trước', 'class' => 'is-up'],
        ['label' => 'Tổng số phòng hiển thị', 'value' => '284', 'meta' => '36 phòng mới trong 30 ngày', 'class' => 'is-up'],
        ['label' => 'Đơn đặt phòng mới', 'value' => '1.248', 'meta' => '145 đơn đang chờ xác nhận', 'class' => 'is-warm'],
        ['label' => 'Tỷ lệ lấp đầy', 'value' => '92%', 'meta' => '-2% ở nhóm phòng gia đình', 'class' => 'is-down'],
    ];
    $bars = [
        ['label' => 'T2', 'value' => 56, 'accent' => false],
        ['label' => 'T3', 'value' => 74, 'accent' => false],
        ['label' => 'T4', 'value' => 68, 'accent' => true],
        ['label' => 'T5', 'value' => 88, 'accent' => false],
        ['label' => 'T6', 'value' => 93, 'accent' => false],
        ['label' => 'T7', 'value' => 80, 'accent' => true],
        ['label' => 'CN', 'value' => 72, 'accent' => false],
    ];
    $topRooms = [
        ['name' => 'Nest Signature Đà Lạt', 'meta' => 'Villa 4 phòng ngủ · 96% lấp đầy · Danh mục villa', 'status' => 'pill-success', 'label' => 'Hiệu suất cao'],
        ['name' => 'Sea Breeze Phú Quốc', 'meta' => 'Suite đôi · 84 booking/tháng · Danh mục biển', 'status' => 'pill-primary', 'label' => 'Ổn định'],
        ['name' => 'Horizon Nha Trang', 'meta' => 'Căn hộ biển · 4,9/5 đánh giá · Danh mục premium', 'status' => 'pill-accent', 'label' => 'Đề xuất đẩy quảng bá'],
    ];
    $activities = [
        ['title' => 'Cập nhật giá cuối tuần cho nhóm phòng biển', 'time' => '10 phút trước', 'tag' => 'Vận hành', 'tagClass' => 'pill-primary'],
        ['title' => 'Tạo chiến dịch Summer Escape giảm 18%', 'time' => '42 phút trước', 'tag' => 'Ưu đãi', 'tagClass' => 'pill-accent'],
        ['title' => 'Duyệt 12 bài blog hướng dẫn du lịch ngắn ngày', 'time' => '1 giờ trước', 'tag' => 'Nội dung', 'tagClass' => 'pill-warning'],
        ['title' => 'Khóa 3 tài khoản có tỷ lệ hủy bất thường', 'time' => '2 giờ trước', 'tag' => 'An toàn', 'tagClass' => 'pill-danger'],
    ];
@endphp

<section class="admin-grid-stats">
    @foreach($stats as $stat)
        <article class="admin-stat-card">
            <div class="admin-stat-label">{{ $stat['label'] }}</div>
            <div class="admin-stat-value">{{ $stat['value'] }}</div>
            <div class="admin-stat-meta {{ $stat['class'] }}">
                <i class="bi bi-activity"></i>
                {{ $stat['meta'] }}
            </div>
        </article>
    @endforeach
</section>

<section class="admin-filter-section">
    <div class="admin-filter-copy">
        <strong>Bộ lọc thời gian</strong>
        <p>Mặc định đang hiển thị dữ liệu trong 7 ngày gần nhất.</p>
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
    </div>
</section>

<section class="admin-two-col">
    <article class="admin-card admin-chart-card">
        <div class="admin-card-head">
            <div>
                <h2>Doanh thu theo thời gian</h2>
                <p>Khoảng lọc đang hiển thị mặc định là 7 ngày gần nhất, có thể đổi nhanh bằng các mốc 7, 15 hoặc 30 ngày.</p>
            </div>
            <span class="admin-pill pill-primary">Cập nhật mỗi 15 phút</span>
        </div>

        <div class="admin-bars">
            @foreach($bars as $bar)
                <div class="admin-bar-col">
                    <div class="admin-bar-stack">
                        <div class="admin-bar {{ $bar['accent'] ? 'is-accent' : '' }}" style="height: {{ $bar['value'] }}%;"></div>
                    </div>
                    <div class="admin-bar-label">{{ $bar['label'] }}</div>
                </div>
            @endforeach
        </div>
    </article>

    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Cơ cấu phòng hiện tại</h2>
                <p>Phân bổ giữa phòng premium, tiêu chuẩn và nhóm đang tối ưu.</p>
            </div>
        </div>

        <div class="admin-donut-wrap">
            <div class="admin-donut" data-label="61%\nPremium"></div>
            <div class="admin-legend">
                <div class="admin-legend-item">
                    <span><i class="admin-dot dot-primary"></i> Premium / bestseller</span>
                    <strong>173 phòng</strong>
                </div>
                <div class="admin-legend-item">
                    <span><i class="admin-dot dot-soft"></i> Tiêu chuẩn / ổn định</span>
                    <strong>81 phòng</strong>
                </div>
                <div class="admin-legend-item">
                    <span><i class="admin-dot dot-accent"></i> Đang tối ưu</span>
                    <strong>30 phòng</strong>
                </div>
            </div>
        </div>
    </article>
</section>

<section class="admin-two-col">
    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Phòng nổi bật theo kỳ lọc</h2>
                <p>Các homestay có hiệu suất tốt nhất trong mốc thời gian đang chọn.</p>
            </div>
        </div>

        <div class="admin-list">
            @foreach($topRooms as $room)
                <div class="admin-list-item">
                    <div>
                        <h3>{{ $room['name'] }}</h3>
                        <p>{{ $room['meta'] }}</p>
                    </div>
                    <span class="admin-pill {{ $room['status'] }}">{{ $room['label'] }}</span>
                </div>
            @endforeach
        </div>
    </article>

    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Hoạt động gần nhất</h2>
                <p>Timeline tác vụ phát sinh theo mốc thời gian đã chọn.</p>
            </div>
        </div>

        <div class="admin-list">
            @foreach($activities as $item)
                <div class="admin-list-item">
                    <div>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['time'] }}</p>
                    </div>
                    <span class="admin-pill {{ $item['tagClass'] }}">{{ $item['tag'] }}</span>
                </div>
            @endforeach
        </div>
    </article>
</section>
@endsection
