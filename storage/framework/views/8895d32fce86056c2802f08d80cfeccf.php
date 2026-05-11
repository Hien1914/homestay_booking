

<?php $__env->startSection('title', 'Đăng ký trở thành Đối tác Chủ nhà'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/clients/apply-host.css')); ?>">

<section class="apply-host-hero my-lg-5 my-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 pe-lg-5 mb-5 mb-lg-0">
                <span class="badge rounded-pill mb-4 apply-host-badge">TRỞ THÀNH CHỦ NHÀ</span>
                <h1 class="apply-host-title">Đăng ký trở thành <br><span class="text-main">Đối tác Chủ nhà</span></h1>
                <p class="apply-host-subtitle">
                    Chào mừng bạn đến với cộng đồng NestAway. Để đảm bảo tính minh bạch và an toàn, chúng tôi cần xác minh danh tính và thông tin thanh toán của bạn trước khi bắt đầu hành trình đón khách.
                </p>
                <div class="row mt-4 d-flex flex-column gap-3">
                    <div class="col-12">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                            <div class="feature-content">
                                <h6>Bảo mật thông tin</h5>
                                <p>Dữ liệu cá nhân của bạn được mã hóa và bảo vệ theo tiêu chuẩn quốc tế nghiêm ngặt nhất.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-wallet" style="color: var(--green-600)"></i></div>
                            <div class="feature-content">
                                <h6>Thanh toán nhanh chóng</h6>
                                <p>Nhận doanh thu tự động qua tài khoản ngân hàng ngay sau khi khách hoàn tất thủ tục.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Section -->
            <div class="col-lg-6">
                <div class="card apply-form-card">
                    <div class="card-body p-4 p-md-5">
                        <?php if(session('error')): ?>
                            <div class="alert alert-danger rounded-4"><?php echo e(session('error')); ?></div>
                        <?php endif; ?>
                        <?php if(session('success')): ?>
                            <div class="alert alert-success rounded-4"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        <?php if(isset($pendingApp) && $pendingApp): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-hourglass-half fa-3x mb-3" style="color: var(--green-600)"></i>
                                <h4 class="fw-bold">Đơn đăng ký đang chờ duyệt</h4>
                                <p class="text-muted">Bạn đã gửi đơn đăng ký vào ngày <strong><?php echo e($pendingApp->created_at->format('d/m/Y')); ?></strong>. Vui lòng chờ admin xem xét và phản hồi.</p>
                                <a href="<?php echo e(route('home')); ?>" class="btn btn-apply text-white mt-3">
                                    <i class="fas fa-home me-2"></i> Về trang chủ
                                </a>
                            </div>
                        <?php else: ?>
                        <form action="<?php echo e(route('apply-host.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <!-- Thông tin định danh -->
                            <h4 class="form-section-title"><i class="fas fa-id-card"></i> Thông tin định danh</h4>
                            <div class="mb-4">
                                <label for="id_card" class="form-label">Số CMND / Căn cước công dân (CCCD) <span class="text-danger">*</span></label>
                                <input type="text" name="id_card" id="id_card" class="form-control" placeholder="Nhập số CMND hoặc CCCD" value="<?php echo e(old('id_card')); ?>" required>
                            </div>

                            <!-- Thông tin thanh toán -->
                            <h4 class="form-section-title"><i class="fas fa-university"></i> Thông tin thanh toán</h4>
                            <div class="mb-4">
                                <label for="bank_name" class="form-label">Ngân hàng <span class="text-danger">*</span></label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="VD: Vietcombank, Techcombank" value="<?php echo e(old('bank_name')); ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="bank_acc" class="form-label">Số tài khoản <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_acc" id="bank_acc" class="form-control" placeholder="Nhập số tài khoản" value="<?php echo e(old('bank_acc')); ?>" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="bank_holder" class="form-label">Chủ tài khoản <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_holder" id="bank_holder" class="form-control text-uppercase" placeholder="TEN KHONG DAU" value="<?php echo e(old('bank_holder')); ?>" required>
                                </div>
                            </div>

                            <div class="form-note">
                                <i class="fas fa-info-circle me-2"></i> Bằng cách nhấn nút dưới đây, bạn đồng ý với các 
                                <a href="<?php echo e(route('pages.terms')); ?>#host-terms" class="fw-bold text-decoration-none" target="_blank">Điều khoản dịch vụ</a> và 
                                <a href="<?php echo e(route('pages.privacy_policy')); ?>#host-privacy" class="fw-bold text-decoration-none" target="_blank">Chính sách bảo mật</a> 
                                của NestAway dành cho Chủ nhà.
                            </div>

                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger mt-3 mb-0">
                                    <ul class="mb-0">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <button type="submit" class="btn btn-apply text-white w-100 mt-4">
                                Gửi yêu cầu <i class="fas fa-arrow-right ms-2 transition-transform"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/apply-host.blade.php ENDPATH**/ ?>