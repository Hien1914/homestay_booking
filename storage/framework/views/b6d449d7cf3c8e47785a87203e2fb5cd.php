<?php $__env->startSection('title', 'Quản lý điểm đến'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-page-header">
        <div class="admin-page-header-content">
            <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
            <p class="admin-page-subtitle">Quản lý tất cả điểm đến trong hệ thống</p>
        </div>
        <div class="admin-page-actions">
            <a href="<?php echo e(route('admin.destinations.create')); ?>" class="admin-create-btn">
                <i class="bi bi-plus-lg"></i>
                Tạo mới
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-primary">
                <i class="bi bi-geo-alt"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e($stats['total']); ?></div>
                <div class="admin-stat-label">Tổng điểm đến</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('admin.destinations')); ?>" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo e($fromDate ?? ''); ?>">
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo e($toDate ?? ''); ?>">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                    <a href="<?php echo e(route('admin.destinations')); ?>"
                        class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Pending Destinations Table -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="card-title mb-0 fw-bold h6">
                <i class="bi bi-clock-history me-2 text-warning"></i>Điểm đến chờ duyệt
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="admin-thumbnail-col">Ảnh đại diện</th>
                            <th>Tên điểm đến</th>
                            <th>Số chỗ nghỉ</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pendingDestinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <?php if($destination->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $destination->image)); ?>" alt="<?php echo e($destination->name); ?>"
                                            class="admin-thumbnail">
                                    <?php else: ?>
                                        <div class="admin-thumbnail d-flex align-items-center justify-content-center bg-light">
                                            <i class="bi bi-geo-alt text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo e($destination->name); ?></div>
                                </td>
                                <td>
                                    <span class="admin-badge admin-badge-info"><?php echo e($destination->homestays_count ?? 0); ?></span>
                                </td>
                                <td><?php echo e(optional($destination->created_at)->format('d/m/Y')); ?></td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="<?php echo e(route('admin.destinations.edit', $destination)); ?>" class="admin-action-btn"
                                            title="Chỉnh sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="admin-action-btn" data-bs-toggle="modal"
                                            data-bs-target="#destModal<?php echo e($destination->id); ?>" title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <form action="<?php echo e(route('admin.destinations.approve', $destination)); ?>" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn duyệt điểm đến này?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <button type="submit" class="admin-action-btn"
                                                style="color: var(--admin-success); border-color: var(--admin-success);"
                                                title="Duyệt">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('admin.destinations.destroy', $destination)); ?>" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa điểm đến này?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="admin-action-btn admin-action-btn-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-geo-alt"></i> Không có điểm đến nào chờ duyệt
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Phân trang -->
            <div class="mt-4 d-flex justify-content-center">
                <?php echo e($pendingDestinations->appends(request()->except('pending_page'))->links()); ?>

            </div>
        </div>
    </div>

    <!-- Approved Destinations Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="card-title mb-0 fw-bold h6">
                <i class="bi bi-geo-alt me-2 text-primary"></i>Điểm đến đang hiển thị
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="admin-thumbnail-col">Ảnh đại diện</th>
                            <th>Tên điểm đến</th>
                            <th>Số chỗ nghỉ</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $approvedDestinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <?php if($destination->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $destination->image)); ?>" alt="<?php echo e($destination->name); ?>"
                                            class="admin-thumbnail">
                                    <?php else: ?>
                                        <div class="admin-thumbnail d-flex align-items-center justify-content-center bg-light">
                                            <i class="bi bi-geo-alt text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo e($destination->name); ?></div>
                                </td>
                                <td>
                                    <span class="admin-badge admin-badge-info"><?php echo e($destination->homestays_count ?? 0); ?></span>
                                </td>
                                <td><?php echo e(optional($destination->created_at)->format('d/m/Y')); ?></td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="<?php echo e(route('admin.destinations.edit', $destination)); ?>" class="admin-action-btn"
                                            title="Chỉnh sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.destinations.destroy', $destination)); ?>" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa điểm đến này?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="admin-action-btn admin-action-btn-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-geo-alt"></i> Không tìm thấy điểm đến nào
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Phân trang -->
            <div class="mt-4 d-flex justify-content-center">
                <?php echo e($approvedDestinations->appends(request()->except('approved_page'))->links()); ?>

            </div>
        </div>
    </div>

    <?php $__currentLoopData = $pendingDestinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="modal fade" id="destModal<?php echo e($destination->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chi tiết đơn duyệt: <?php echo e($destination->name); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <?php if($destination->image): ?>
                                <img src="<?php echo e(asset('storage/' . $destination->image)); ?>" class="img-fluid rounded"
                                    alt="<?php echo e($destination->name); ?>" style="max-height: 200px;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center rounded mx-auto"
                                    style="height: 150px; width: 200px;">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h6 class="fw-bold">Tên điểm đến</h6>
                        <p><?php echo e($destination->name); ?></p>

                        <h6 class="fw-bold mt-3">Mô tả chi tiết</h6>
                        <p class="text-muted" style="white-space: pre-wrap;"><?php echo e($destination->description ?: 'Không có mô tả'); ?>

                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <form action="<?php echo e(route('admin.destinations.approve', $destination)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <button type="submit" class="btn btn-success">Duyệt điểm đến này</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/destinations/index.blade.php ENDPATH**/ ?>