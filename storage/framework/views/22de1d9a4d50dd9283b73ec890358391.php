

<?php $__env->startSection('title', 'Quản lý tiện nghi'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">Quản lý tiện nghi</h1>
        <p class="admin-page-subtitle">Quản lý tất cả tiện nghi trong hệ thống</p>
    </div>
    <div class="admin-page-actions">
        <button type="button" class="admin-btn admin-btn-primary" onclick="openAmenityModal()">
            <i class="bi bi-plus-lg"></i>
            Tạo mới
        </button>
    </div>
</div>

<!-- Statistics -->
<div class="admin-stats-grid">
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

<!-- Filters -->
<div class="admin-card admin-filters-card">
    <div class="admin-filters-row">
        <div class="admin-search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchAmenity" class="admin-search-input" placeholder="Tìm kiếm tiện nghi...">
        </div>
    </div>
</div>

<!-- Amenities Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-check2-square me-2"></i>Danh sách tiện nghi</h3>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table" id="amenitiesTable">
                <thead>
                    <tr>
                        <th>Tên tiện nghi</th>
                        <th>Số chỗ nghỉ sử dụng</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="amenitiesBody">
                    <?php $__empty_1 = true; $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr data-amenity-id="<?php echo e($amenity->id); ?>">
                            <td>
                                <div class="admin-amenity-cell">
                                    <i class="bi bi-check2-circle me-2"></i>
                                    <?php echo e($amenity->name); ?>

                                </div>
                            </td>
                            <td><span class="admin-badge admin-badge-info"><?php echo e($amenity->homestays_count ?? 0); ?></span></td>
                            <td><?php echo e(optional($amenity->created_at)->format('d/m/Y')); ?></td>
                            <td>
                                <div class="admin-actions d-flex gap-1">
                                    <button type="button" class="admin-action-btn" title="Chỉnh sửa" onclick="editAmenity(<?php echo e($amenity->id); ?>, '<?php echo e($amenity->name); ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="admin-action-btn admin-action-btn-danger" title="Xóa" onclick="deleteAmenity(<?php echo e($amenity->id); ?>)">
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
    </div>
</div>

<!-- Amenity Modal -->
<div class="admin-modal" id="amenityModal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h2 id="amenityModalTitle">Tạo tiện nghi mới</h2>
            <button type="button" class="admin-modal-close" onclick="closeAmenityModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="amenityForm" onsubmit="submitAmenity(event)">
            <div class="admin-form-group">
                <label for="amenityName">Tên tiện nghi</label>
                <input type="text" id="amenityName" name="name" class="admin-form-control" 
                    placeholder="VD: WiFi, Máy lạnh, Bếp..." required>
                <small id="duplicateWarning" class="text-danger" style="display:none;">Tiện nghi này đã tồn tại!</small>
            </div>
            <div class="admin-modal-actions">
                <button type="button" class="admin-btn admin-btn-outline" onclick="closeAmenityModal()">Hủy</button>
                <button type="submit" class="admin-btn admin-btn-primary" id="submitBtn">Lưu</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.admin-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.admin-modal.active {
    display: flex;
}

.admin-modal-content {
    background: white;
    border-radius: 8px;
    padding: 30px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.admin-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 2px solid #eee;
    padding-bottom: 15px;
}

.admin-modal-header h2 {
    margin: 0;
    font-size: 20px;
    color: #333;
}

.admin-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    padding: 0;
    width: 30px;
    height: 30px;
}

.admin-modal-close:hover {
    color: #000;
}

.admin-form-group {
    margin-bottom: 20px;
}

.admin-form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.admin-form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.admin-form-control:focus {
    outline: none;
    border-color: #003b0d;
    box-shadow: 0 0 0 3px rgba(0, 59, 13, 0.1);
}

.admin-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.text-danger {
    color: #dc3545;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let editingAmenityId = null;
const allAmenities = <?php echo json_encode($amenities->pluck('name', 'id'), 512) ?>;

function openAmenityModal() {
    editingAmenityId = null;
    document.getElementById('amenityModalTitle').textContent = 'Tạo tiện nghi mới';
    document.getElementById('amenityName').value = '';
    document.getElementById('duplicateWarning').style.display = 'none';
    document.getElementById('amenityModal').classList.add('active');
    document.getElementById('amenityName').focus();
}

function editAmenity(id, name) {
    editingAmenityId = id;
    document.getElementById('amenityModalTitle').textContent = 'Chỉnh sửa tiện nghi';
    document.getElementById('amenityName').value = name;
    document.getElementById('duplicateWarning').style.display = 'none';
    document.getElementById('amenityModal').classList.add('active');
    document.getElementById('amenityName').focus();
}

function closeAmenityModal() {
    document.getElementById('amenityModal').classList.remove('active');
    editingAmenityId = null;
    document.getElementById('amenityName').value = '';
}

function checkDuplicate() {
    const name = document.getElementById('amenityName').value.trim();
    const warning = document.getElementById('duplicateWarning');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!editingAmenityId && name) {
        const isDuplicate = Object.values(allAmenities).some(existingName => 
            existingName.toLowerCase() === name.toLowerCase()
        );
        
        if (isDuplicate) {
            warning.style.display = 'block';
            submitBtn.disabled = true;
            return true;
        }
    }
    
    warning.style.display = 'none';
    submitBtn.disabled = false;
    return false;
}

function submitAmenity(event) {
    event.preventDefault();
    
    if (checkDuplicate()) {
        showToast('Tiện nghi này đã tồn tại!', 'error');
        return;
    }
    
    const name = document.getElementById('amenityName').value;
    const url = editingAmenityId 
        ? `/admin/amenities/${editingAmenityId}` 
        : '/admin/amenities';
    
    const formData = new FormData();
    formData.append('name', name);
    if (editingAmenityId) {
        formData.append('_method', 'PUT');
    }
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Thành công!', 'success');
            closeAmenityModal();
            
            if (editingAmenityId) {
                location.reload();
            } else {
                addAmenityRow(data.amenity);
            }
        } else {
            showToast(data.message || 'Có lỗi xảy ra!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra: ' + error.message, 'error');
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
            <div class="admin-amenity-cell">
                <i class="bi bi-check2-circle me-2"></i>
                ${amenity.name}
            </div>
        </td>
        <td><span class="admin-badge admin-badge-info">0</span></td>
        <td>${new Date().toLocaleDateString('vi-VN')}</td>
        <td>
            <div class="admin-actions d-flex gap-1">
                <button type="button" class="admin-action-btn" title="Chỉnh sửa" onclick="editAmenity(${amenity.id}, '${amenity.name}')">
                    <i class="bi bi-pencil"></i>
                </button>
                <button type="button" class="admin-action-btn admin-action-btn-danger" title="Xóa" onclick="deleteAmenity(${amenity.id})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </td>
    `;
    tbody.appendChild(row);
    
    allAmenities[amenity.id] = amenity.name;
}

function deleteAmenity(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa?')) return;
    
    fetch(`/admin/amenities/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            '_method': 'DELETE'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Xóa thành công!', 'success');
            const row = document.querySelector(`tr[data-amenity-id="${id}"]`);
            row?.remove();
            delete allAmenities[id];
            
            if (document.querySelectorAll('tbody tr[data-amenity-id]').length === 0) {
                const tbody = document.getElementById('amenitiesBody');
                tbody.innerHTML = '<tr id="emptyRow"><td colspan="4" class="text-center text-muted py-4"><i class="bi bi-check2-square"></i> Chưa có tiện nghi nào</td></tr>';
            }
        } else {
            showToast(data.message || 'Có lỗi xảy ra!', 'error');
        }
    })
    .catch(error => {
        showToast('Có lỗi xảy ra!', 'error');
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const amenityNameInput = document.getElementById('amenityName');
    amenityNameInput.addEventListener('input', checkDuplicate);
    
    const searchInput = document.getElementById('searchAmenity');
    const table = document.getElementById('amenitiesTable');
    const rows = table.querySelectorAll('tbody tr');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        
        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return;
            const text = row.textContent.toLowerCase();
            const matchesSearch = text.includes(searchTerm);
            row.style.display = matchesSearch ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterTable);
});

document.getElementById('amenityModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAmenityModal();
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/amenities/index.blade.php ENDPATH**/ ?>