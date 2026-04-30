<?php
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
?>

<section class="admin-sidebar d-flex flex-column">
    <div class="admin-brand d-flex align-items-center">
        <div class="admin-brand-mark d-flex align-items-center justify-content-center">
            <?php if (isset($component)) { $__componentOriginal5d812effded182e3b6dbacddab5bc7dd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d812effded182e3b6dbacddab5bc7dd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo-icon','data' => ['width' => '22','height' => '22','ariaHidden' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('logo-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => '22','height' => '22','aria-hidden' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5d812effded182e3b6dbacddab5bc7dd)): ?>
<?php $attributes = $__attributesOriginal5d812effded182e3b6dbacddab5bc7dd; ?>
<?php unset($__attributesOriginal5d812effded182e3b6dbacddab5bc7dd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5d812effded182e3b6dbacddab5bc7dd)): ?>
<?php $component = $__componentOriginal5d812effded182e3b6dbacddab5bc7dd; ?>
<?php unset($__componentOriginal5d812effded182e3b6dbacddab5bc7dd); ?>
<?php endif; ?>
        </div>
        <div>
            <h6 class="m-0 fw-bold text-white">Chủ nhà</h6>
            <small class="text-white-50"><?php echo e(Auth::user()->full_name); ?></small>
        </div>
    </div>

    <nav class="admin-nav d-flex flex-column">
        <?php $__currentLoopData = $navGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="admin-nav-group">
                <div class="admin-nav-group-title"><?php echo e($group['title']); ?></div>
                <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route($item['route'])); ?>" class="admin-nav-link <?php echo e(request()->routeIs($item['route'] . '*') ? 'is-active' : ''); ?>">
                        <i class="bi <?php echo e($item['icon']); ?>"></i>
                        <span><?php echo e($item['label']); ?></span>
                        <?php if(isset($item['badge']) && $item['badge'] > 0): ?>
                            <span class="ms-auto badge bg-danger rounded-pill"><?php echo e($item['badge']); ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>

    <div class="admin-sidebar-footer">
        <a href="<?php echo e(route('home')); ?>" class="admin-nav-link mb-2" style="opacity: 0.9; background: rgba(255,255,255,0.1); border-radius: 8px;">
            <i class="bi bi-person-circle"></i>
            <span>Chuyển sang Người dùng</span>
        </a>
        <a href="#" onclick="hostLogout(event)" class="admin-logout-btn">
            <i class="bi bi-box-arrow-left"></i>
            Đăng xuất
        </a>
    </div>
</section>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/layout/sidebar.blade.php ENDPATH**/ ?>