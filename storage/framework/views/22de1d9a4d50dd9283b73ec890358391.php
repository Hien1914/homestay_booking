

<?php $__env->startSection('title', 'Quản lý tiện nghi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-page-header">
        <div class="admin-page-header-content">
            <h1 class="admin-page-title">Quản lý tiện nghi</h1>
            <p class="admin-page-subtitle">Quản lý tất cả tiện nghi trong hệ thống</p>
        </div>
        <div class="admin-page-actions">
            <button type="button" class="admin-create-btn" onclick="openAmenityModal()">
                <i class="bi bi-plus-lg"></i>
                Tạo mới
            </button>
        </div>
    </div>

    <!-- Statistics -->
    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-primary">
                <i class="bi bi-check2-square"></i>
            </div>
            <div class="admin-stat-content">
                <div class="admin-stat-value"><?php echo e($stats['total']); ?></div>
                <div class="admin-stat-label">Tổng tiện nghi</div>
            </div>
        </div>
    </div>

    <!-- Amenities Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="card-title mb-0 fw-bold h6">
                <i class="bi bi-check2-square me-2 text-primary"></i>Danh sách tiện nghi
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table" id="amenitiesTable">
                    <thead>
                        <tr>
                            <th>Tên tiện nghi</th>
                            <th>Số chỗ nghỉ sử dụng</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="amenitiesBody">
                        <?php $__empty_1 = true; $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-amenity-id="<?php echo e($amenity->id); ?>">
                                <td>
                                    <div class="fw-bold text-dark">
                                        <i class="bi bi-check2-circle me-2 text-success"></i>
                                        <?php echo e($amenity->name); ?>

                                    </div>
                                </td>
                                <td><span class="admin-badge admin-badge-info"><?php echo e($amenity->homestays_count ?? 0); ?></span></td>
                                <td>
                                    <?php if($amenity->is_approved): ?>
                                        <span class="admin-badge admin-badge-success">Đã xác nhận</span>
                                    <?php else: ?>
                                        <span class="admin-badge admin-badge-pending">Chờ duyệt</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button type="button" class="admin-action-btn" title="Chỉnh sửa"
                                            onclick="editAmenity(<?php echo e($amenity->id); ?>, '<?php echo e($amenity->name); ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <?php if(!$amenity->is_approved): ?>
                                            <form action="<?php echo e(route('admin.amenities.approve', $amenity)); ?>" method="POST"
                                                class="d-inline" onsubmit="return confirm('Duyệt tiện nghi này?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <button type="submit" class="admin-action-btn"
                                                    style="color: var(--admin-success); border-color: var(--admin-success);"
                                                    title="Duyệt">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <form action="<?php echo e(route('admin.amenities.destroy', $amenity)); ?>" method="POST"
                                            class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tiện nghi này?');">
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
                            <tr id="emptyRow">
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-check2-square"></i> Chưa có tiện nghi nào
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <?php echo e($amenities->links()); ?>

            </div>
        </div>
    </div>

    <!-- Amenity Modal -->
    <div class="modal fade" id="amenityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" id="amenityModalTitle">Thêm tiện nghi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="amenityForm" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body p-4">
                        <div class="mb-0">
                            <label for="amenityName" class="form-label fw-bold mb-2">Tên tiện nghi <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="amenityName" class="form-control" required
                                placeholder="Ví dụ: WiFi, Hồ bơi, Chỗ đậu xe..."
                                style="border-radius: 12px; height: 50px; padding-left: 15px;">
                            <small id="duplicateWarning" class="text-danger mt-2" style="display:none;">Tiện nghi
                                này đã tồn tại!</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="admin-filter-clear-btn px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="admin-create-btn px-4" id="submitBtn">Lưu tiện nghi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script>
        window.allAmenitiesData = <?php echo json_encode($amenities->pluck('name', 'id'), 512) ?>;
        window.amenityStoreUrl = "<?php echo e(route('admin.amenities.store')); ?>";
    </script>
    <script src="<?php echo e(asset('js/admin/amenities-index.js?v=4')); ?>"></script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/amenities/index.blade.php ENDPATH**/ ?>