<?php $__env->startSection('title', 'Chính sách bảo mật dành cho Chủ nhà'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/clients/policy.css')); ?>">
<div class="policy-page-wrapper">
    <div class="container-setting policy-container">
        <h1 class="policy-page-title">CHÍNH SÁCH BẢO MẬT DÀNH CHO CHỦ NHÀ (HOST)</h1>
        
        <div class="policy-content">
            <div class="policy-section">
                <h2 class="policy-section-title">1. Giới thiệu</h2>
                <p>NestAway cam kết bảo vệ quyền riêng tư và dữ liệu cá nhân của các Đối tác Chủ nhà. Chính sách này giải thích cách chúng tôi thu thập, sử dụng và bảo vệ thông tin của bạn khi đăng ký và hoạt động trên nền tảng.</p>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">2. Thông tin chúng tôi thu thập</h2>
                <p>Khi bạn đăng ký trở thành Host, chúng tôi thu thập:</p>
                <ul>
                    <li><strong>Thông tin định danh:</strong> Họ tên, CMND/CCCD/Hộ chiếu, ngày sinh, địa chỉ thường trú.</li>
                    <li><strong>Thông tin liên lạc:</strong> Số điện thoại, địa chỉ email.</li>
                    <li><strong>Thông tin tài chính:</strong> Tên ngân hàng, số tài khoản, tên chủ tài khoản để phục vụ việc thanh toán doanh thu.</li>
                    <li><strong>Thông tin chỗ nghỉ:</strong> Địa chỉ, hình ảnh, mô tả chi tiết và các giấy phép kinh doanh (nếu có yêu cầu).</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">3. Mục đích sử dụng thông tin</h2>
                <ul>
                    <li>Xác minh danh tính và sự minh bạch của Đối tác Chủ nhà nhằm bảo vệ an toàn cho hệ thống và khách hàng.</li>
                    <li>Xử lý và chuyển khoản doanh thu đặt phòng định kỳ cho Chủ nhà.</li>
                    <li>Hiển thị thông tin cơ bản về chỗ nghỉ cho người dùng (Khách hàng) tìm kiếm và đặt phòng.</li>
                    <li>Gửi các thông báo quan trọng về hệ thống, chính sách mới hoặc hỗ trợ kỹ thuật.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">4. Chia sẻ thông tin</h2>
                <p>Chúng tôi cam kết <strong>KHÔNG BÁN</strong> hoặc trao đổi thông tin cá nhân của Chủ nhà cho bên thứ ba vì mục đích thương mại. Thông tin chỉ được chia sẻ trong các trường hợp sau:</p>
                <ul>
                    <li>Cung cấp thông tin liên hệ (tên, số điện thoại) cho Khách hàng đã đặt phòng thành công để tiện liên lạc nhận phòng.</li>
                    <li>Chia sẻ với các đối tác thanh toán, ngân hàng để thực hiện giao dịch tài chính.</li>
                    <li>Khi có yêu cầu hợp pháp từ cơ quan nhà nước có thẩm quyền.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">5. Bảo vệ và Lưu trữ dữ liệu</h2>
                <ul>
                    <li>Tất cả dữ liệu định danh và tài chính đều được mã hóa bằng công nghệ bảo mật tiêu chuẩn quốc tế.</li>
                    <li>Chỉ những nhân viên có thẩm quyền của NestAway mới được quyền tiếp cận và xử lý hồ sơ Đối tác.</li>
                    <li>Dữ liệu của Chủ nhà sẽ được lưu trữ trong suốt thời gian duy trì tài khoản. Nếu bạn yêu cầu xóa tài khoản, chúng tôi sẽ hủy dữ liệu cá nhân theo quy định của pháp luật.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">6. Quyền của Chủ nhà</h2>
                <ul>
                    <li>Bạn có quyền truy cập, chỉnh sửa hoặc yêu cầu xóa thông tin cá nhân của mình bất cứ lúc nào thông qua phần Cài đặt tài khoản.</li>
                    <li>Mọi thắc mắc về vấn đề bảo mật thông tin có thể gửi trực tiếp đến bộ phận hỗ trợ Đối tác của NestAway để được giải quyết.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/pages/host-privacy-policy.blade.php ENDPATH**/ ?>