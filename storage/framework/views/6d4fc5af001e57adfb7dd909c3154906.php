

<?php $__env->startSection('title', 'Đăng ký'); ?>

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

        <form action="<?php echo e(route('auth.register')); ?>" method="POST" class="auth-form d-block" novalidate>
            <?php echo csrf_field(); ?>
            <h2 class="auth-title text-center text-uppercase fw-bold">Đăng ký tài khoản</h2>

            <label class="auth-label" for="register-fullname">Họ và tên</label>
            <input id="register-fullname" name="full_name" type="text" class="auth-input" 
                    placeholder="Nguyễn Văn A" autocomplete="name" 
                    value="<?php echo e(old('full_name')); ?>" required>
            <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: #b42318; font-size: 0.8rem;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <label class="auth-label" for="register-email">Email</label>
            <input id="register-email" name="email" type="email" class="auth-input" 
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

            <label class="auth-label" for="register-phone">Số điện thoại</label>
            <input id="register-phone" name="phone" type="tel" class="auth-input" 
                    placeholder="0912345678" autocomplete="tel" 
                    value="<?php echo e(old('phone')); ?>" required>
            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: #b42318; font-size: 0.8rem;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <label class="auth-label" for="register-gender">Giới tính</label>
            <select id="register-gender" name="gender" class="auth-input" required>
                <option value="" disabled <?php echo e(old('gender') ? '' : 'selected'); ?>>Chọn giới tính</option>
                <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>>Nam</option>
                <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>>Nữ</option>
                <option value="other" <?php echo e(old('gender') == 'other' ? 'selected' : ''); ?>>Khác</option>
            </select>
            <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: #b42318; font-size: 0.8rem;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <label class="auth-label" for="register-birthday">Ngày sinh</label>
            <input id="register-birthday" name="birthday" type="date" class="auth-input" 
                    value="<?php echo e(old('birthday')); ?>" required>
            <?php $__errorArgs = ['birthday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: #b42318; font-size: 0.8rem;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <label class="auth-label" for="register-password">Mật khẩu</label>
            <input id="register-password" name="password" type="password" class="auth-input" 
                    placeholder="Tối thiểu 8 ký tự" autocomplete="new-password" required>
            <small style="color: #6c757d; font-size: 0.75rem; display: block; margin-top: 0.25rem;">
                Yêu cầu: 8-32 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt (@$!%*?&#)
            </small>
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

            <label class="auth-label" for="register-password-confirm">Xác nhận mật khẩu</label>
            <input id="register-password-confirm" name="password_confirmation" type="password" 
                    class="auth-input" placeholder="Nhập lại mật khẩu" 
                    autocomplete="new-password" required>

            <a href="<?php echo e(route('auth.google')); ?>" class="auth-btn auth-btn--google my-3" style="text-decoration: none;">
                <img src="<?php echo e(asset('img/icon/google.svg')); ?>" alt="Google" class="google-icon">
                <span>Đăng ký bằng Google</span>
            </a>

            <button type="submit" class="auth-btn auth-btn--primary">Đăng ký</button>

            <p class="auth-switch">
                Đã có tài khoản?
                <a href="<?php echo e(route('login.page')); ?>" class="auth-switch-btn">Đăng nhập</a>
            </p>
        </form>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/register.blade.php ENDPATH**/ ?>