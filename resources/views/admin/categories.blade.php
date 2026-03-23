@extends('admin.layout.app')

@section('title', 'Điểm đến')
@section('page_title', 'Điểm đến homestay')
@section('page_kicker', 'Bản đồ nhu cầu đặt phòng')

@section('page_actions')
    <a href="#" class="admin-btn admin-btn-primary" data-modal-open="category-modal" data-modal-mode="add" data-modal-entity="điểm đến">
        <i class="bi bi-plus-circle"></i> Thêm điểm đến
    </a>
@endsection

@section('content')
@php
    $destinations = [
        [
            'name' => 'Ven biển',
            'slug' => 'ven-bien',
            'areas' => 'Phú Quốc · Nha Trang · Đà Nẵng',
            'count' => '68 homestay',
            'booking_share' => '41%',
            'favorite' => 'Khách đặt nhiều vào cuối tuần và kỳ nghỉ ngắn',
            'thumb' => 'is-beach',
        ],
        [
            'name' => 'Miền núi',
            'slug' => 'mien-nui',
            'areas' => 'Sa Pa · Đà Lạt · Hà Giang',
            'count' => '51 homestay',
            'booking_share' => '27%',
            'favorite' => 'Phù hợp cặp đôi và nhóm bạn săn view',
            'thumb' => 'is-mountain',
        ],
        [
            'name' => 'Gia đình',
            'slug' => 'gia-dinh',
            'areas' => 'Đà Lạt · Hội An · Vũng Tàu',
            'count' => '37 homestay',
            'booking_share' => '18%',
            'favorite' => 'Booking cao vào dịp lễ và nghỉ hè',
            'thumb' => 'is-family',
        ],
        [
            'name' => 'Cao cấp',
            'slug' => 'cao-cap',
            'areas' => 'Hạ Long · Phú Quốc · Đà Nẵng',
            'count' => '24 homestay',
            'booking_share' => '14%',
            'favorite' => 'Giá trị đơn hàng cao, thời gian lưu trú dài hơn',
            'thumb' => 'is-premium',
        ],
    ];
@endphp

<section class="admin-page-section">
    <div class="admin-mini-grid">
        <article class="admin-mini-card"><span>Tổng điểm đến</span><strong>12</strong><div class="admin-progress"><span style="width: 86%;"></span></div></article>
        <article class="admin-mini-card"><span>Tổng homestay theo nhóm vị trí</span><strong>180</strong><div class="admin-progress is-success"><span style="width: 74%;"></span></div></article>
        <article class="admin-mini-card"><span>Nhóm được đặt nhiều nhất</span><strong>Ven biển</strong><div class="admin-progress is-accent"><span style="width: 41%;"></span></div></article>
    </div>

    <section class="admin-filter-section">
        <div class="admin-filter-copy">
            <strong>Bộ lọc dữ liệu</strong>
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
            <select class="admin-select">
                <option>Tất cả nhóm vị trí</option>
                <option>Ven biển</option>
                <option>Miền núi</option>
                <option>Gia đình</option>
                <option>Cao cấp</option>
            </select>
            <select class="admin-select">
                <option>Tất cả khu vực</option>
                <option>Phú Quốc</option>
                <option>Đà Lạt</option>
                <option>Hạ Long</option>
            </select>
        </div>
    </section>

    <section class="admin-two-col">
        <article class="admin-card">
            <div class="admin-card-head">
                <div>
                    <h2>Tỷ lệ đặt phòng theo vị trí</h2>
                    <p>Biểu đồ tròn để nhìn nhanh người dùng đang đặt ở khu vực nào nhiều hơn.</p>
                </div>
            </div>

            <div class="admin-donut-wrap">
                <div class="admin-pie">
                    <svg viewBox="0 0 220 220" aria-label="Tỷ lệ đặt phòng theo vị trí">
                        <path d="M110 110 L110 0 A110 110 0 0 1 180.58 194.35 Z" fill="#1f4fa3">
                            <title>Ven biển: 41%</title>
                        </path>
                        <path d="M110 110 L180.58 194.35 A110 110 0 0 1 15.40 166.63 Z" fill="#5f93df">
                            <title>Miền núi: 27%</title>
                        </path>
                        <path d="M110 110 L15.40 166.63 A110 110 0 0 1 25.07 39.49 Z" fill="#ffb27f">
                            <title>Gia đình: 18%</title>
                        </path>
                        <path d="M110 110 L25.07 39.49 A110 110 0 0 1 110 0 Z" fill="#8f7fff">
                            <title>Cao cấp: 14%</title>
                        </path>
                    </svg>
                </div>
                <div class="admin-legend is-horizontal">
                    <div class="admin-legend-item"><span><i class="admin-dot dot-primary"></i> Ven biển</span><strong>41%</strong></div>
                    <div class="admin-legend-item"><span><i class="admin-dot dot-soft"></i> Miền núi</span><strong>27%</strong></div>
                    <div class="admin-legend-item"><span><i class="admin-dot dot-accent"></i> Gia đình</span><strong>18%</strong></div>
                    <div class="admin-legend-item"><span><i class="admin-dot" style="background:#8f7fff;"></i> Cao cấp</span><strong>14%</strong></div>
                </div>
            </div>

            <div class="admin-insight">
                <strong>Insight:</strong> Ven biển vẫn là điểm đến được đặt nhiều nhất trong khoảng lọc hiện tại.
            </div>
        </article>

        <article class="admin-card">
            <div class="admin-card-head">
                <div>
                    <h2>So sánh xu hướng theo vị trí</h2>
                    <p>Biểu đồ line mô phỏng mức tăng booking giữa các nhóm vị trí theo thời gian.</p>
                </div>
            </div>

            <div class="admin-bars">
                <div class="admin-bar-col"><div class="admin-bar-stack"><div class="admin-bar" style="height:48%;"></div></div><div class="admin-bar-label">T2</div></div>
                <div class="admin-bar-col"><div class="admin-bar-stack"><div class="admin-bar is-accent" style="height:62%;"></div></div><div class="admin-bar-label">T3</div></div>
                <div class="admin-bar-col"><div class="admin-bar-stack"><div class="admin-bar" style="height:58%;"></div></div><div class="admin-bar-label">T4</div></div>
                <div class="admin-bar-col"><div class="admin-bar-stack"><div class="admin-bar is-accent" style="height:74%;"></div></div><div class="admin-bar-label">T5</div></div>
                <div class="admin-bar-col"><div class="admin-bar-stack"><div class="admin-bar" style="height:88%;"></div></div><div class="admin-bar-label">T6</div></div>
                <div class="admin-bar-col"><div class="admin-bar-stack"><div class="admin-bar is-accent" style="height:93%;"></div></div><div class="admin-bar-label">T7</div></div>
                <div class="admin-bar-col"><div class="admin-bar-stack"><div class="admin-bar" style="height:81%;"></div></div><div class="admin-bar-label">CN</div></div>
            </div>
        </article>
    </section>

    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách điểm đến</h2>
                <p>Bảng thống kê tập trung vào khu vực nào đang có nhiều homestay và được đặt nhiều nhất.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Điểm đến</th>
                        <th>Slug</th>
                        <th>Khu vực nổi bật</th>
                        <th>Số homestay</th>
                        <th>Tỷ lệ booking</th>
                        <th>Insight</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($destinations as $destination)
                        <tr>
                            <td>
                                <div class="admin-location-cell">
                                    <div class="admin-location-thumb {{ $destination['thumb'] }}"></div>
                                    <div>
                                        <strong>{{ $destination['name'] }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $destination['slug'] }}</td>
                            <td>{{ $destination['areas'] }}</td>
                            <td>{{ $destination['count'] }}</td>
                            <td>{{ $destination['booking_share'] }}</td>
                            <td>{{ $destination['favorite'] }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="#" class="admin-btn admin-btn-sm admin-btn-edit" data-modal-open="category-modal" data-modal-mode="edit" data-modal-entity="điểm đến">Sửa</a>
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

<div class="admin-modal" id="category-modal" hidden>
    <div class="admin-modal-backdrop" data-modal-close></div>
    <div class="admin-modal-dialog">
        <div class="admin-modal-head">
            <h3 data-modal-dynamic-title>Thêm điểm đến</h3>
            <button type="button" class="admin-modal-close" data-modal-close><i class="bi bi-x-lg"></i></button>
        </div>
        <form class="admin-form-grid">
            <div class="admin-form-group"><label>Tên điểm đến</label><input type="text" value="Ven biển"></div>
            <div class="admin-form-group"><label>Slug</label><input type="text" value="ven-bien"></div>
            <div class="admin-form-group is-full"><label>Khu vực nổi bật</label><input type="text" value="Phú Quốc, Nha Trang, Đà Nẵng"></div>
            <div class="admin-form-group"><label>Ảnh đại diện</label><input type="text" value="Ảnh bãi biển chủ đạo"></div>
            <div class="admin-form-group"><label>Số homestay</label><input type="number" value="68"></div>
            <div class="admin-form-group is-full"><label>Insight mô tả</label><textarea rows="4">Nhóm điểm đến được đặt nhiều nhất vào cuối tuần và các dịp nghỉ ngắn.</textarea></div>
            <div class="admin-form-actions is-full">
                <button type="button" class="admin-btn admin-btn-secondary" data-modal-close>Hủy</button>
                <button type="submit" class="admin-btn admin-btn-primary">Lưu điểm đến</button>
            </div>
        </form>
    </div>
</div>
@endsection
