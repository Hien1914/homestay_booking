@php
    $navItems = [
        ['route' => 'admin.dashboard', 'icon' => 'bi-grid-1x2-fill', 'label' => 'Tổng quan'],
        ['route' => 'admin.users', 'icon' => 'bi-people-fill', 'label' => 'Người dùng'],
        ['route' => 'admin.homestays', 'icon' => 'bi-house-door-fill', 'label' => 'Chỗ nghỉ', 'badge' => $pendingHomestays ?? 0],
        ['route' => 'admin.bookings', 'icon' => 'bi-calendar-check-fill', 'label' => 'Đặt phòng'],
        ['route' => 'admin.amenities', 'icon' => 'bi-stars', 'label' => 'Tiện nghi'],
        ['route' => 'admin.promotions', 'icon' => 'bi-tag-fill', 'label' => 'Ưu đãi'],
        ['route' => 'admin.destinations', 'icon' => 'bi-geo-alt-fill', 'label' => 'Điểm đến'],
        ['route' => 'admin.reports', 'icon' => 'bi-bar-chart-fill', 'label' => 'Báo cáo'],
    ];
@endphp

<section class="admin-sidebar d-flex flex-column">
    <div class="admin-brand d-flex align-items-center">
        <div class="admin-brand-mark d-flex align-items-center justify-content-center">
            <x-logo-icon width="22" height="22" aria-hidden="true" />
        </div>
        <div>
            <h6 class="m-0 fw-bold">Quản trị viên</h6>
        </div>
    </div>

    <nav class="admin-nav d-flex flex-column">
        @foreach ($navItems as $item)
            <a href="{{ route($item['route']) }}" class="admin-nav-link {{ request()->routeIs($item['route'] . '*') ? 'is-active' : '' }}">
                <i class="bi {{ $item['icon'] }}"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="admin-sidebar-footer">
        <a href="#" onclick="adminLogout(event)" class="admin-logout-btn">
            <i class="bi bi-box-arrow-left"></i>
            Đăng xuất
        </a>
    </div>
</section>
