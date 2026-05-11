let editingAmenityId = null;
const allAmenities = window.allAmenitiesData || {};

let bootstrapModal = null;
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('amenityModal');
    if (modalEl) {
        bootstrapModal = new bootstrap.Modal(modalEl);
    }
});

window.openAmenityModal = function() {
    editingAmenityId = null;
    document.getElementById('amenityModalTitle').textContent = 'Tạo tiện nghi mới';
    document.getElementById('amenityName').value = '';
    document.getElementById('duplicateWarning').style.display = 'none';
    document.getElementById('submitBtn').disabled = false;
    
    const form = document.getElementById('amenityForm');
    form.action = window.amenityStoreUrl;
    
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();

    if (bootstrapModal) bootstrapModal.show();
    setTimeout(() => document.getElementById('amenityName').focus(), 500);
}

window.editAmenity = function(id, name) {
    editingAmenityId = id;
    document.getElementById('amenityModalTitle').textContent = 'Chỉnh sửa tiện nghi';
    document.getElementById('amenityName').value = name;
    document.getElementById('duplicateWarning').style.display = 'none';
    document.getElementById('submitBtn').disabled = false;
    
    const form = document.getElementById('amenityForm');
    form.action = window.amenityStoreUrl + '/' + id;
    
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        form.appendChild(methodInput);
    }
    methodInput.value = 'PUT';

    if (bootstrapModal) bootstrapModal.show();
    setTimeout(() => document.getElementById('amenityName').focus(), 500);
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

document.addEventListener('DOMContentLoaded', function () {
    const amenityForm = document.getElementById('amenityForm');
    const amenityNameInput = document.getElementById('amenityName');
    
    amenityForm?.addEventListener('submit', function(e) {
        if (checkDuplicate()) {
            e.preventDefault();
            return false;
        }
        
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Đang lưu...';
    });
    
    amenityNameInput?.addEventListener('input', checkDuplicate);
});