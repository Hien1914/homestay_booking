@php
    $adminUser = session('admin_user', ['name' => 'Admin', 'login_method' => 'key']);
    $navItems = [
        ['route' => 'admin.dashboard', 'icon' => 'bi-grid-1x2-fill', 'label' => 'Tổng quan'],
        ['route' => 'admin.categories', 'icon' => 'bi-geo-alt-fill', 'label' => 'Điểm đến'],
        ['route' => 'admin.homestays', 'icon' => 'bi-house-door-fill', 'label' => 'Phòng'],
        ['route' => 'admin.bookings', 'icon' => 'bi-calendar-check-fill', 'label' => 'Đặt phòng'],
        ['route' => 'admin.users', 'icon' => 'bi-people-fill', 'label' => 'Khách hàng'],
        ['route' => 'admin.promotions', 'icon' => 'bi-megaphone-fill', 'label' => 'Ưu đãi'],
        ['route' => 'admin.blogs', 'icon' => 'bi-journal-richtext', 'label' => 'Blog'],
        ['route' => 'admin.reviews', 'icon' => 'bi-star-fill', 'label' => 'Đánh giá'],
        ['route' => 'admin.tickets', 'icon' => 'bi-headset', 'label' => 'Hỗ trợ'],
        ['route' => 'admin.faqs', 'icon' => 'bi-patch-question-fill', 'label' => 'FAQ'],
    ];
@endphp

<section class="admin-sidebar">
    <div class="admin-brand">
        <div class="admin-brand-mark">
            <i class="bi bi-house-door-fill"></i>
        </div>
        <div>
            <strong>NestAway</strong>
            <small>Admin Panel</small>
        </div>
    </div>

    <div class="admin-user-card">
        <div class="admin-user-avatar">{{ strtoupper(substr($adminUser['name'] ?? 'A', 0, 1)) }}</div>
        <div>
            <h2>{{ $adminUser['name'] ?? 'Admin' }}</h2>
        </div>
    </div>

    <nav class="admin-nav">
        @foreach ($navItems as $item)
            <a href="{{ route($item['route']) }}" class="admin-nav-link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}">
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
