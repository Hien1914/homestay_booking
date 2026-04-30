@php
    $navGroups = [
        [
            'title' => 'Tổng quát',
            'items' => [
                ['route' => 'host.dashboard', 'icon' => 'bi-grid-1x2-fill', 'label' => 'Bảng điều khiển'],
            ],
        ],
        [
            'title' => 'Chỗ nghỉ & Nội dung',
            'items' => [
                ['route' => 'host.homestays.index', 'icon' => 'bi-house-door-fill', 'label' => 'Homestay của tôi'],
                ['route' => 'host.amenities', 'icon' => 'bi-stars', 'label' => 'Tiện nghi'],
                ['route' => 'host.promotions.index', 'icon' => 'bi-tag-fill', 'label' => 'Mã giảm giá'],
                ['route' => 'host.reviews', 'icon' => 'bi-chat-left-text-fill', 'label' => 'Đánh giá & Bình luận'],
            ],
        ],
        [
            'title' => 'Vận hành & Khách hàng',
            'items' => [
                ['route' => 'host.bookings.index', 'icon' => 'bi-calendar-check-fill', 'label' => 'Danh sách đặt phòng', 'badge' => \App\Models\Booking::whereHas('homestay', function($q) { $q->where('owner_id', auth()->id()); })->where('status', 'pending')->count()],
                ['route' => 'host.customers.index', 'icon' => 'bi-people-fill', 'label' => 'Danh sách khách hàng'],
            ],
        ],
        [
            'title' => 'Tài chính',
            'items' => [
                ['route' => 'host.earnings.index', 'icon' => 'bi-wallet2', 'label' => 'Thống kê doanh thu'],
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
            <h6 class="m-0 fw-bold text-white">Chủ nhà</h6>
            <small class="text-white-50">{{ Auth::user()->full_name }}</small>
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
                            <span class="ms-auto badge bg-danger rounded-pill">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    <div class="admin-sidebar-footer">
        <a href="{{ route('home') }}" class="admin-nav-link mb-2" style="opacity: 0.9; background: rgba(255,255,255,0.1); border-radius: 8px;">
            <i class="bi bi-person-circle"></i>
            <span>Chuyển sang Người dùng</span>
        </a>
        <a href="#" onclick="hostLogout(event)" class="admin-logout-btn">
            <i class="bi bi-box-arrow-left"></i>
            Đăng xuất
        </a>
    </div>
</section>
