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
    <link rel="stylesheet" href="<?php echo e(asset('css/admin/page.css')); ?>">
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
    <?php if(session('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof toastr !== 'undefined') {
                    toastr.success('<?php echo e(session('success')); ?>');
                } else {
                    alert('<?php echo e(session('success')); ?>');
                }
            });
        </script>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('<?php echo e(session('error')); ?>');
                } else {
                    alert('<?php echo e(session('error')); ?>');
                }
            });
        </script>
    <?php endif; ?>
    
    <?php echo $__env->yieldContent('content'); ?>
</main>

<form id="admin-logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST" class="d-none">
    <?php echo csrf_field(); ?>
</form>

<script src="<?php echo e(asset('js/bootstrap.bundle.min.js')); ?>"></script>
<script>
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

function adminLogout(event) {
    if (event) event.preventDefault();
    document.getElementById('admin-logout-form').submit();
}
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>

<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/layout/app.blade.php ENDPATH**/ ?>