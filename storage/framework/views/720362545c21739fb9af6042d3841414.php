<div class="card-body p-0" id="users-table-container">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Người dùng</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>SĐT</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Lượt đặt</th>
                    <th>Ngày ĐK</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="admin-id-badge">#<?php echo e($user->id); ?></span></td>
                        <td class="fw-bold"><?php echo e($user->full_name); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td>
                            <?php if($user->role === 'host'): ?>
                                <span class="admin-badge admin-badge-success">Host</span>
                            <?php else: ?>
                                <span class="admin-badge admin-badge-info">User</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($user->phone ?: '-'); ?></td>
                        <td>
                            <?php
                                $genderText = match ($user->gender) {
                                    'male' => 'Nam',
                                    'female' => 'Nữ',
                                    'other' => 'Khác',
                                    default => '-',
                                };
                            ?>
                            <?php echo e($genderText); ?>

                        </td>
                        <td><?php echo e(optional($user->birthday)->format('d/m/Y') ?: '-'); ?></td>
                        <td><span class="admin-badge admin-badge-ongoing"><?php echo e($user->bookings_count); ?></span></td>
                        <td><?php echo e(optional($user->created_at)->format('d/m/Y')); ?></td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="admin-action-btn js-open-user-modal" title="Xem chi tiết"
                                    data-bs-toggle="modal" data-bs-target="#userDetailModal"
                                    data-user-id="<?php echo e($user->id); ?>" data-user-name="<?php echo e($user->full_name); ?>"
                                    data-user-email="<?php echo e($user->email); ?>"
                                    data-user-role="<?php echo e($user->role === 'host' ? 'Host' : 'User'); ?>"
                                    data-user-phone="<?php echo e($user->phone ?: '-'); ?>" data-user-gender="<?php echo e($genderText); ?>"
                                    data-user-bank-name="<?php echo e($user->bank_name ?: '-'); ?>"
                                    data-user-bank-account="<?php echo e($user->bank_account_number ?: '-'); ?>"
                                    data-user-birthday="<?php echo e(optional($user->birthday)->format('d/m/Y') ?: '-'); ?>"
                                    data-user-bookings="<?php echo e($user->bookings_count); ?>"
                                    data-user-created-at="<?php echo e(optional($user->created_at)->format('d/m/Y')); ?>">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Chưa có tài khoản nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <?php echo e($users->links()); ?>

    </div>
</div>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/partials/users_table.blade.php ENDPATH**/ ?>