

<?php $__env->startSection('title', 'Đăng nhập'); ?>

<?php $__env->startSection('content'); ?>
<style>
    @import url('<?php echo e(asset('css/clients/auth.css')); ?>');
</style>

<section class="auth-page container-setting">
    <div class="auth-card">
        <?php if(session('error')): ?>
            <div class="auth-message auth-message--error" role="alert">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>
        
        <?php if(session('success')): ?>
            <div class="auth-message auth-message--success" role="status">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('auth.login')); ?>" method="POST" class="auth-form d-block" novalidate>
            <?php echo csrf_field(); ?>
            <h2 class="auth-title text-center text-uppercase fw-bold">Đăng nhập</h2>

            <label class="auth-label" for="login-email">Email</label>
            <input id="login-email" name="email" type="email" class="auth-input" 
                    placeholder="you@example.com" autocomplete="email" 
                    value="<?php echo e(old('email')); ?>" required>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: #b42318; font-size: 0.8rem;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <label class="auth-label" for="login-password">Mật khẩu</label>
            <input id="login-password" name="password" type="password" class="auth-input" 
                    placeholder="••••••••" autocomplete="current-password" required>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: #b42318; font-size: 0.8rem;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <a href="<?php echo e(route('auth.google')); ?>" class="auth-btn auth-btn--google my-3" style="text-decoration: none;">
                <img src="<?php echo e(asset('img/icon/google.svg')); ?>" alt="Google" class="google-icon">
                <span>Đăng nhập bằng Google</span>
            </a>

            <button type="submit" class="auth-btn auth-btn--primary">Đăng nhập</button>

            <p class="auth-switch">
                Chưa có tài khoản?
                <a href="<?php echo e(route('register.page')); ?>" class="auth-switch-btn">Đăng ký</a>
            </p>
        </form>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/login.blade.php ENDPATH**/ ?>