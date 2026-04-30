

<?php $__env->startSection('title', 'Quản lý khách hàng'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Danh sách khách hàng đã đặt phòng tại chỗ nghỉ của bạn</p>
    </div>
</div>

<!-- No search bar as per request -->
<div class="mb-4"></div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-people me-2 text-primary"></i>Danh sách khách hàng
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Khách hàng</th>
                        <th>Email</th>
                        <th class="text-center">Số điện thoại</th>
                        <th class="text-center">Số lần đặt</th>
                        <th class="text-end pe-4">Tổng chi tiêu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4"><span class="admin-id-badge">#<?php echo e($customer->id); ?></span></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo e($customer->full_name); ?></div>
                            </td>
                            <td>
                                <div class="text-dark"><?php echo e($customer->email); ?></div>
                            </td>
                            <td class="text-center">
                                <div class="text-muted"><?php echo e($customer->phone ?? 'Chưa cập nhật'); ?></div>
                            </td>
                            <td class="text-center">
                                <span class="admin-badge admin-badge-success"><?php echo e($customer->bookings_count); ?></span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="fw-bold text-success"><?php echo e(number_format($customer->total_spent ?? 0)); ?>đ</div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-5">Chưa có khách hàng nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($customers->hasPages()): ?>
            <div class="p-4 border-top">
                <?php echo e($customers->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/customers.blade.php ENDPATH**/ ?>