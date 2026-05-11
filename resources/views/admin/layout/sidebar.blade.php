@php
    $navGroups = [
        [
            'title' => 'Tổng quát',
            'items' => [
                ['route' => 'admin.dashboard', 'icon' => 'bi-grid-1x2-fill', 'label' => 'Bảng điều khiển', 'badge' => 0],
                ['route' => 'admin.users', 'icon' => 'bi-people-fill', 'label' => 'Người dùng', 'badge' => 0],
                ['route' => 'admin.host-applications', 'icon' => 'bi-person-badge', 'label' => 'Đơn đăng ký host', 'badge' => \App\Models\HostApplication::where('status', 'pending')->count()],
            ],
        ],
        [
            'title' => 'Vận hành homestay',
            'items' => [
                ['route' => 'admin.homestays', 'icon' => 'bi-house-door-fill', 'label' => 'Chỗ nghỉ', 'badge' => \App\Models\Homestay::where('is_approved', false)->count()],
                ['route' => 'admin.destinations', 'icon' => 'bi-geo-alt-fill', 'label' => 'Điểm đến', 'badge' => \App\Models\Destination::where('is_approved', false)->count()],
                ['route' => 'admin.amenities', 'icon' => 'bi-stars', 'label' => 'Tiện nghi', 'badge' => \App\Models\Amenity::where('is_approved', false)->count()],
                ['route' => 'admin.reviews', 'icon' => 'bi-chat-left-text-fill', 'label' => 'Đánh giá', 'badge' => 0],
            ],
        ],
        [
            'title' => 'Đặt phòng và thanh toán',
            'items' => [
                ['route' => 'admin.bookings', 'icon' => 'bi-calendar-check-fill', 'label' => 'Đặt phòng', 'badge' => \App\Models\Booking::where('status', \App\Models\Booking::STATUS_PENDING)->count()],

                ['route' => 'admin.payouts', 'icon' => 'bi-cash-stack', 'label' => 'Yêu cầu rút tiền', 'badge' => \App\Models\Payout::where('status', 'pending')->count()],

            ],
        ],
        [
            'title' => 'Nội dung',
            'items' => [
                ['route' => 'admin.posts.index', 'icon' => 'bi-newspaper', 'label' => 'Bài viết', 'badge' => 0],
            ],
        ],
    ];
@endphp

<section class="admin-sidebar d-flex flex-column">
    <div class="admin-brand d-flex align-items-center">
        <div class="admin-brand-mark d-flex align-items-center justify-content-center">
            <x-logo-icon width="22" height="22" aria-hidden="true" />
        </div>
        <div>
            <h6 class="m-0 fw-bold text-white">Quản trị viên</h6>
        </div>
    </div>

    <nav class="admin-nav d-flex flex-column">
        @foreach ($navGroups as $group)
            <div class="admin-nav-group">
                <div class="admin-nav-group-title">{{ $group['title'] }}</div>
                @foreach ($group['items'] as $item)
                    <a href="{{ route($item['route']) }}" class="admin-nav-link {{ request()->routeIs($item['route'] . '*') ? 'is-active' : '' }}">
                        <i class="bi {{ $item['icon'] }}"></i>
                        <span>{{ $item['label'] }}</span>
                        @if(isset($item['badge']) && $item['badge'] > 0)
                            <span class="admin-sidebar-badge">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    <div class="admin-sidebar-footer">
        <a href="#" onclick="adminLogout(event)" class="admin-logout-btn">
            <i class="bi bi-box-arrow-left"></i>
            Đăng xuất
        </a>
    </div>
</section>
