<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <link rel="alternate icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <title>Đăng nhập Admin | NestAway</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap.min.css')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/variable.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin/layout.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin/login.css')); ?>">
</head>
<body>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header admin-login-header text-center">
                <h4 class="mb-0"><i class="bi bi-shield-lock me-2"></i> Admin Login</h4>
            </div>
            <div class="card-body">
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('admin.login.key')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="secret_key" class="form-label">Admin Key</label>
                        <input type="password" class="form-control <?php $__errorArgs = ['secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="secret_key" name="secret_key" required autofocus
                               placeholder="Nhập admin key...">
                        <?php $__errorArgs = ['secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Đăng nhập
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-muted text-center">
                <small>Admin key được thiết lập trong file .env</small>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/login.blade.php ENDPATH**/ ?>