

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

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('admin.amenities')); ?>" class="row g-3 align-items-end">
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
                    <a href="<?php echo e(route('admin.amenities')); ?>"
                        class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
                </div>
            </form>
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
                                        <button type="button" class="admin-action-btn admin-action-btn-danger" title="Xóa"
                                            onclick="deleteAmenity(<?php echo e($amenity->id); ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
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
                <form id="amenityForm">
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
        let editingAmenityId = null;
        const allAmenities = <?php echo json_encode($amenities->pluck('name', 'id'), 512) ?>;

        const bootstrapModal = new bootstrap.Modal(document.getElementById('amenityModal'));
        function notify(message, type = 'success') {
            if (typeof window.showToast === 'function') {
                window.showToast(message, type);
                return;
            }
            if (typeof window.toastr !== 'undefined') {
                if (type === 'error') window.toastr.error(message);
                else window.toastr.success(message);
                return;
            }
            if (type === 'error') {
                console.error(message);
            } else {
                console.log(message);
            }
        }

        function openAmenityModal() {
            editingAmenityId = null;
            document.getElementById('amenityModalTitle').textContent = 'Tạo tiện nghi mới';
            document.getElementById('amenityName').value = '';
            document.getElementById('duplicateWarning').style.display = 'none';
            document.getElementById('submitBtn').disabled = false;
            bootstrapModal.show();
            setTimeout(() => document.getElementById('amenityName').focus(), 500);
        }

        function editAmenity(id, name) {
            editingAmenityId = id;
            document.getElementById('amenityModalTitle').textContent = 'Chỉnh sửa tiện nghi';
            document.getElementById('amenityName').value = name;
            document.getElementById('duplicateWarning').style.display = 'none';
            document.getElementById('submitBtn').disabled = false;
            bootstrapModal.show();
            setTimeout(() => document.getElementById('amenityName').focus(), 500);
        }

        function closeAmenityModal() {
            bootstrapModal.hide();
            editingAmenityId = null;
            document.getElementById('amenityName').value = '';
            document.getElementById('duplicateWarning').style.display = 'none';
            document.getElementById('submitBtn').disabled = false;
        }

        function checkDuplicate() {
            const name = document.getElementById('amenityName').value.trim();
            const warning = document.getElementById('duplicateWarning');
            const submitBtn = document.getElementById('submitBtn');
            const normalizedName = name.toLowerCase();

            if (!name) {
                warning.style.display = 'none';
                submitBtn.disabled = false;
                return false;
            }

            const isDuplicate = Object.entries(allAmenities).some(([id, existingName]) => {
                if (editingAmenityId && Number(id) === Number(editingAmenityId)) return false;
                return String(existingName).toLowerCase() === normalizedName;
            });

            warning.style.display = isDuplicate ? 'block' : 'none';
            submitBtn.disabled = isDuplicate;
            return isDuplicate;
        }

        function submitAmenity(event) {
            event.preventDefault();

            const nameInput = document.getElementById('amenityName');
            const name = nameInput.value.trim();

            if (!name) {
                notify('Vui lòng nhập tên tiện nghi!', 'error');
                return;
            }

            const url = editingAmenityId
                ? `/admin/amenities/${editingAmenityId}`
                : '/admin/amenities';
            const currentEditingId = editingAmenityId;
            const isEditing = Boolean(currentEditingId);

            const formData = new FormData();
            formData.append('name', name);
            if (isEditing) {
                formData.append('_method', 'PUT');
            }
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

            const submitBtn = document.getElementById('submitBtn');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Đang lưu...';

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        if (response.status === 422) {
                            const errors = data.errors;
                            const firstError = Object.values(errors)[0][0];
                            throw new Error(firstError);
                        }
                        throw new Error(data.message || 'Có lỗi xảy ra!');
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        if (isEditing) {
                            updateAmenityRow(currentEditingId, data.amenity);
                        } else {
                            addAmenityRow(data.amenity);
                        }

                        notify(data.message || 'Thành công!', 'success');
                        closeAmenityModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    notify(error.message, 'error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
        }

        function addAmenityRow(amenity) {
            const tbody = document.getElementById('amenitiesBody');
            const emptyRow = document.getElementById('emptyRow');
            if (emptyRow) emptyRow.remove();

            const row = document.createElement('tr');
            row.setAttribute('data-amenity-id', amenity.id);
            row.innerHTML = `
            <td>
                <div class="fw-bold text-dark">
                    <i class="bi bi-check2-circle me-2 text-success"></i>
                    ${amenity.name}
                </div>
            </td>
            <td><span class="admin-badge admin-badge-info">0</span></td>
            <td>
                <span class="admin-badge admin-badge-success">Đã xác nhận</span>
            </td>
            <td>
                <div class="d-flex gap-1 justify-content-center">
                    <button type="button" class="admin-action-btn" title="Chỉnh sửa" onclick="editAmenity(${amenity.id}, ${JSON.stringify(amenity.name)})">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="admin-action-btn admin-action-btn-danger" title="Xóa" onclick="deleteAmenity(${amenity.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        `;
            tbody.prepend(row);

            allAmenities[amenity.id] = amenity.name;
        }

        function updateAmenityRow(id, amenity) {
            const row = document.querySelector(`tr[data-amenity-id="${id}"]`);
            if (!row) return;
            const nameCell = row.querySelector('td .fw-bold');
            if (nameCell) {
                nameCell.innerHTML = `<i class="bi bi-check2-circle me-2 text-success"></i> ${amenity.name}`;
            }
            allAmenities[id] = amenity.name;
        }

        function deleteAmenity(id) {
            if (!confirm('Bạn có chắc chắn muốn xóa?')) return;

            fetch(`/admin/amenities/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Có lỗi xảy ra!');
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        notify(data.message || 'Xóa thành công!', 'success');
                        const row = document.querySelector(`tr[data-amenity-id="${id}"]`);
                        row?.remove();
                        delete allAmenities[id];

                        if (document.querySelectorAll('tbody tr[data-amenity-id]').length === 0) {
                            const tbody = document.getElementById('amenitiesBody');
                            tbody.innerHTML = '<tr id="emptyRow"><td colspan="4" class="text-center text-muted py-4"><i class="bi bi-check2-square"></i> Chưa có tiện nghi nào</td></tr>';
                        }
                    } else {
                        notify(data.message || 'Có lỗi xảy ra!', 'error');
                    }
                })
                .catch(error => {
                    notify(error.message || 'Có lỗi xảy ra!', 'error');
                    console.error('Error:', error);
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const amenityForm = document.getElementById('amenityForm');
            const amenityNameInput = document.getElementById('amenityName');
            amenityForm?.addEventListener('submit', submitAmenity);
            amenityNameInput.addEventListener('input', checkDuplicate);
            document.getElementById('amenityModal')?.addEventListener('hidden.bs.modal', function () {
                editingAmenityId = null;
                document.getElementById('amenityName').value = '';
                document.getElementById('duplicateWarning').style.display = 'none';
                document.getElementById('submitBtn').disabled = false;
            });
        });

        document.getElementById('amenityModal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeAmenityModal();
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/amenities/index.blade.php ENDPATH**/ ?>