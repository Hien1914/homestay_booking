<?php $__env->startSection('title', 'Quản lý tiện nghi'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Quản lý danh sách tiện nghi bạn cung cấp</p>
    </div>
    <div class="admin-page-actions">
        <button type="button" class="admin-btn admin-btn-primary" onclick="openAmenityModal()">
            <i class="bi bi-plus-lg"></i>
            Thêm nhanh
        </button>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-stars me-2"></i>Danh sách tiện nghi của tôi</h3>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên tiện nghi</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="admin-id-badge">#<?php echo e($loop->iteration); ?></span></td>
                            <td><strong><?php echo e($amenity->name); ?></strong></td>
                            <td>
                                <?php if($amenity->is_approved): ?>
                                    <span class="admin-status-badge status-active">Đã duyệt</span>
                                <?php else: ?>
                                    <span class="admin-status-badge status-pending">Chờ duyệt</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($amenity->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <div class="admin-actions">
                                    <button type="button" class="admin-action-btn" title="Sửa" 
                                            onclick="openAmenityModal(<?php echo e($amenity->id); ?>, '<?php echo e(addslashes($amenity->name)); ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="<?php echo e(route('host.amenities.destroy', $amenity->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa?')">
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
                            <td colspan="5" class="text-center py-4 text-muted">Chưa có tiện nghi nào.</td>
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

<!-- Quick Amenity Modal -->
<div class="modal fade" id="amenityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content admin-card">
            <div class="modal-header admin-card-header">
                <h5 class="modal-title" id="amenityModalTitle">Thêm tiện nghi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="amenityForm" method="POST">
                <?php echo csrf_field(); ?>
                <div id="methodField"></div>
                <div class="modal-body admin-card-body">
                    <div class="admin-form-group mb-0">
                        <label for="amenityName">Tên tiện nghi <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="amenityName" class="admin-form-control" required placeholder="Ví dụ: WiFi, Hồ bơi, Chỗ đậu xe...">
                    </div>
                </div>
                <div class="modal-footer admin-form-actions">
                    <button type="button" class="admin-btn admin-btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="admin-btn admin-btn-primary" id="submitBtn">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const modal = new bootstrap.Modal(document.getElementById('amenityModal'));
    const form = document.getElementById('amenityForm');
    const title = document.getElementById('amenityModalTitle');
    const nameInput = document.getElementById('amenityName');
    const methodField = document.getElementById('methodField');

    function openAmenityModal(id = null, name = '') {
        if (id) {
            title.innerText = 'Sửa tiện nghi';
            form.action = `/host/amenities/${id}`;
            methodField.innerHTML = '<?php echo method_field("PUT"); ?>';
            nameInput.value = name;
        } else {
            title.innerText = 'Thêm tiện nghi mới';
            form.action = '<?php echo e(route("host.amenities.store")); ?>';
            methodField.innerHTML = '';
            nameInput.value = '';
        }
        modal.show();
        setTimeout(() => nameInput.focus(), 500);
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('host.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/host/amenities/index.blade.php ENDPATH**/ ?>