<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <link rel="alternate icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <title><?php echo $__env->yieldContent('title', 'Quản trị'); ?> | NestAway Admin</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap.min.css')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/variable.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin/layout.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<?php if(!session('admin_verified')): ?>
    <script>window.location.href = '<?php echo e(route('admin.login')); ?>';</script>
<?php endif; ?>

<!-- Sidebar Overlay for Mobile -->
<div class="admin-sidebar-overlay" id="sidebarOverlay"></div>

<!-- Hamburger Menu Button -->
<button class="admin-hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu">
    <i class="bi bi-list"></i>
</button>

<?php echo $__env->make('admin.layout.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<main class="admin-main-content">
    <?php echo $__env->yieldContent('content'); ?>
</main>

<form id="admin-logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST" class="d-none">
    <?php echo csrf_field(); ?>
</form>


<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-4" id="toastContainer" style="z-index: 9999;"></div>

<script src="<?php echo e(asset('js/bootstrap.bundle.min.js')); ?>"></script>
<script>
// Toast Notification System
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer');
    const toastId = 'toast-' + Date.now();
    
    const iconMap = {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill'
    };
    
    const bgMap = {
        success: 'bg-success',
        error: 'bg-danger',
        warning: 'bg-warning',
        info: 'bg-info'
    };
    
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgMap[type]} border-0" role="alert" aria-live="assertive" aria-atomic="true" style="min-width: 350px; font-size: 16px; margin-bottom: 12px;">
            <div class="d-flex">
                <div class="toast-body" style="padding: 12px 16px; font-weight: 500;">
                    <i class="bi ${iconMap[type]} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

// Check for flash session messages
document.addEventListener('DOMContentLoaded', function() {
    <?php if(session('success')): ?>
        showToast('<?php echo e(session('success')); ?>', 'success');
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        showToast('<?php echo e(session('error')); ?>', 'error');
    <?php endif; ?>
    
    <?php if(session('warning')): ?>
        showToast('<?php echo e(session('warning')); ?>', 'warning');
    <?php endif; ?>
});

// Hamburger Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebar = document.querySelector('.admin-sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (hamburgerBtn && sidebar) {
        hamburgerBtn.addEventListener('click', function() {
            sidebar.classList.toggle('is-open');
            hamburgerBtn.classList.toggle('is-open');
            overlay.classList.toggle('is-open');
        });
        
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('is-open');
                hamburgerBtn.classList.remove('is-open');
                overlay.classList.remove('is-open');
            });
        }
        
        const navLinks = sidebar.querySelectorAll('.admin-nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 900) {
                    sidebar.classList.remove('is-open');
                    hamburgerBtn.classList.remove('is-open');
                    overlay.classList.remove('is-open');
                }
            });
        });
    }
});

// function showToast(message, type = 'success') {
//     const toastContainer = document.getElementById('toastContainer');
//     const toastId = 'toast-' + Date.now();
    
//     const iconMap = {
//         success: 'bi-check-circle-fill',
//         error: 'bi-x-circle-fill',
//         warning: 'bi-exclamation-triangle-fill',
//         info: 'bi-info-circle-fill'
//     };
    
//     const bgMap = {
//         success: 'bg-success',
//         error: 'bg-danger',
//         warning: 'bg-warning',
//         info: 'bg-info'
//     };
    
//     const toastHTML = `
//         <div id="${toastId}" class="toast align-items-center text-white ${bgMap[type]} border-0" role="alert" aria-live="assertive" aria-atomic="true">
//             <div class="d-flex">
//                 <div class="toast-body">
//                     <i class="bi ${iconMap[type]} me-2"></i>
//                     ${message}
//                 </div>
//                 <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
//             </div>
//         </div>
//     `;
    
//     toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
//     const toastElement = document.getElementById(toastId);
//     const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
//     toast.show();
    
//     toastElement.addEventListener('hidden.bs.toast', () => {
//         toastElement.remove();
//     });
// }

// // Form validation helper
// function validateForm(form) {
//     const requiredFields = form.querySelectorAll('[required]');
//     let firstInvalidField = null;
//     let isValid = true;
    
//     requiredFields.forEach(field => {
//         field.classList.remove('is-invalid');
        
//         if (!field.value.trim()) {
//             field.classList.add('is-invalid');
//             isValid = false;
//             if (!firstInvalidField) {
//                 firstInvalidField = field;
//             }
//         }
//     });
    
//     if (firstInvalidField) {
//         firstInvalidField.focus();
//         firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
//     }
    
//     return isValid;
// }

// // Clear invalid state on input
// document.addEventListener('input', function(e) {
//     if (e.target.classList.contains('is-invalid')) {
//         e.target.classList.remove('is-invalid');
//     }
// });

// // Check for flash toast message on page load
// document.addEventListener('DOMContentLoaded', function() {
//     const flashSuccess = sessionStorage.getItem('toast_success');
//     const flashError = sessionStorage.getItem('toast_error');
    
//     if (flashSuccess) {
//         showToast(flashSuccess, 'success');
//         sessionStorage.removeItem('toast_success');
//     }
    
//     if (flashError) {
//         showToast(flashError, 'error');
//         sessionStorage.removeItem('toast_error');
//     }
// });

function adminLogout(event) {
    if (event) event.preventDefault();
    document.getElementById('admin-logout-form').submit();
}
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/layout/app.blade.php ENDPATH**/ ?>