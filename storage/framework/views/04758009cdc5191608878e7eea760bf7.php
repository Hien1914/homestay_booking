<?php $__env->startSection('title', 'Thanh toán'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $homestayName = $booking->homestay_title ?? ($booking->homestay->name ?? 'Homestay Demo');
    $customerName = $booking->user->name ?? ($booking->user->full_name ?? 'Khách hàng Demo');
    $bookingCode = '#' . ($booking->id ?? 'DEMO');
    $amountToPay = $amountToPay ?? $booking->total_amount ?? 0;
    $totalAmount = $booking->total_amount ?? $amountToPay;
?>
<style>@import url('<?php echo e(asset('css/clients/payment.css')); ?>');</style>

<section class="payment-page container-setting py-5">
    <div class="row">
        <!-- Sidebar - Thông tin đơn hàng -->
        <aside class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="card-title fw-bold mb-4" style="color: #2E7D32;">Xác nhận đơn hàng</h4>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Homestay:</span>
                        <span class="fw-semibold text-end"><?php echo e($homestayName); ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Mã đặt phòng:</span>
                        <span class="fw-bold text-dark">#<?php echo e($booking->id); ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Khách hàng:</span>
                        <span class="fw-medium text-end"><?php echo e($customerName); ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Tổng tiền:</span>
                        <span class="fw-bold fs-5" style="color: #d32f2f;"><?php echo e(number_format($totalAmount, 0, ',', '.')); ?> đ</span>
                    </div>

                    <?php if($isDemo ?? false): ?>
                        <div class="alert alert-warning mt-3 mb-0" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Chế độ xem trước — Homestay mẫu
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mt-3 mb-0" role="alert">
                            <i class="fa-solid fa-circle-info me-2"></i> Vui lòng thanh toán để hoàn tất đặt phòng
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </aside>

        <!-- Main Content - Thanh toán QR -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 p-4 text-center">
                <h4 class="fw-bold mb-4">Quét mã QR để thanh toán</h3>
                
                <div id="qr-loading" class="my-5">
                    <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="mt-3 text-muted">Đang khởi tạo mã QR...</p>
                </div>

                <div id="qr-success-box" style="display: none;">
                    <!-- QR Code Image -->
                    <div class="d-flex justify-content-center mb-4">
                        <div class="p-3 bg-white border rounded shadow-sm" style="display: inline-block;">
                            <img id="qr-img" src="" alt="Mã QR Thanh toán" style="max-width: 300px; width: 100%; border-radius: 8px;">
                        </div>
                    </div>



                    <!-- Confirmation Form -->
                    <div class="p-4 border rounded-3 bg-white shadow-sm mx-auto" style="max-width: 500px;">
                        <div class="alert alert-warning text-start small mb-3">
                            <i class="fa-solid fa-circle-exclamation me-1"></i>
                            Lưu ý: Sau khi chuyển khoản qua mã QR thành công, bạn bắt buộc phải nhấn nút "TÔI ĐÃ THANH TOÁN" bên dưới để hệ thống ghi nhận thanh toán. Nếu không xác nhận, đơn đặt phòng sẽ không được xử lý!
                        </div>

                        <form id="payment-form" method="POST" action="<?php echo e(route('payment.confirm')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="booking_id" value="<?php echo e($booking->id); ?>">

                            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold" id="confirm-btn">
                                <span id="btn-text">TÔI ĐÃ THANH TOÁN</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div id="qr-error-box" class="alert alert-danger mx-auto mt-4" style="max-width: 400px; display: none;">
                    <i class="fa-solid fa-circle-xmark me-2"></i> <span>Không thể tải mã QR. Vui lòng tải lại trang.</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Fetch QR
    fetch("<?php echo e(route('generate.qr')); ?>", {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('qr-loading').style.display = 'none';
        
        if(data.success && data.qr_image) {
            document.getElementById('qr-success-box').style.display = 'block';
            
            // Generate proxy url to avoid CORS if necessary
            let qrUrl = "<?php echo e(route('proxy.qr')); ?>?url=" + encodeURIComponent(data.qr_image);
            document.getElementById('qr-img').src = qrUrl;
            

        } else {
            document.getElementById('qr-error-box').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Lỗi khi lấy mã QR:', error);
        document.getElementById('qr-loading').style.display = 'none';
        document.getElementById('qr-error-box').style.display = 'block';
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/payment.blade.php ENDPATH**/ ?>