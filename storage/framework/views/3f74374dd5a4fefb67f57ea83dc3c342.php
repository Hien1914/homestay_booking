

<?php $__env->startSection('title', 'Quản lý khách hàng'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Danh sách khách hàng đã đặt phòng tại chỗ nghỉ của bạn</p>
    </div>
</div>

<div class="admin-card admin-filters-card">
    <form method="GET" action="<?php echo e(route('host.customers.index')); ?>" class="admin-filters-row">
        <div class="admin-search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" class="admin-search-input" placeholder="Tìm theo tên, email, số điện thoại" value="<?php echo e(request('search')); ?>">
        </div>
        <div class="admin-form-actions">
            <button type="submit" class="admin-btn admin-btn-primary">Tìm kiếm</button>
            <a href="<?php echo e(route('host.customers.index')); ?>" class="admin-btn admin-btn-secondary">Xóa lọc</a>
        </div>
    </form>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-people-fill me-2"></i>Danh sách khách hàng</h3>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên khách hàng</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Số lần đặt</th>
                        <th>Tổng chi tiêu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($customer->id); ?></td>
                            <td><?php echo e($customer->full_name); ?></td>
                            <td><?php echo e($customer->email); ?></td>
                            <td><?php echo e($customer->phone ?? 'Chưa cập nhật'); ?></td>
                            <td class="text-center"><?php echo e($customer->bookings_count); ?></td>
                            <td class="text-center"><?php echo e(number_format($customer->total_spent ?? 0)); ?>đ</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted">Chưa có khách hàng nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-center">
            <?php echo e($customers->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/customers/index.blade.php ENDPATH**/ ?>