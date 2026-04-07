<?php
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
            <h6 class="m-0 fw-bold">Quản trị viên</h6>
        </div>
    </div>

    <nav class="admin-nav d-flex flex-column">
        <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route($item['route'])); ?>" class="admin-nav-link <?php echo e(request()->routeIs($item['route'] . '*') ? 'is-active' : ''); ?>">
                <i class="bi <?php echo e($item['icon']); ?>"></i>
                <span><?php echo e($item['label']); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>

    <div class="admin-sidebar-footer">
        <a href="#" onclick="adminLogout(event)" class="admin-logout-btn">
            <i class="bi bi-box-arrow-left"></i>
            Đăng xuất
        </a>
    </div>
</section>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/layout/sidebar.blade.php ENDPATH**/ ?>