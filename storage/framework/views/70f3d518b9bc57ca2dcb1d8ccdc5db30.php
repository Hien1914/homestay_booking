<?php $__env->startSection('title', 'Điều khoản dịch vụ dành cho Chủ nhà'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/clients/policy.css')); ?>">
<div class="policy-page-wrapper">
    <div class="container-setting policy-container">
        <h1 class="policy-page-title">ĐIỀU KHOẢN DỊCH VỤ DÀNH CHO CHỦ NHÀ (HOST)</h1>
        
        <div class="policy-content">
            <div class="policy-section">
                <h2 class="policy-section-title">1. Giới thiệu chung</h2>
                <p>Chào mừng bạn đến với chương trình Đối tác Chủ nhà (Host) của NestAway. Khi đăng ký và sử dụng hệ thống của chúng tôi để quản lý và cho thuê chỗ nghỉ, bạn đồng ý với toàn bộ các điều khoản được quy định dưới đây. Vui lòng đọc kỹ để hiểu rõ quyền lợi và trách nhiệm của bạn.</p>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">2. Điều kiện đăng ký và tài khoản</h2>
                <ul>
                    <li>Người đăng ký phải từ đủ 18 tuổi trở lên và có đầy đủ năng lực hành vi dân sự.</li>
                    <li>Bạn cam kết cung cấp thông tin cá nhân, giấy tờ tùy thân và thông tin ngân hàng chính xác, hợp pháp.</li>
                    <li>Tài khoản Chủ nhà không được phép chuyển nhượng, cho mượn hoặc bán lại dưới mọi hình thức.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">3. Quyền và trách nhiệm của Chủ nhà</h2>
                <h3 class="policy-subsection-title">3.1. Quản lý chỗ nghỉ</h3>
                <ul>
                    <li>Chủ nhà phải cung cấp hình ảnh và mô tả chính xác 100% về chỗ nghỉ (tiện nghi, diện tích, quy định chung).</li>
                    <li>Đảm bảo phòng ốc sạch sẽ, an toàn và sẵn sàng đón khách vào đúng thời gian đã đặt.</li>
                    <li>Luôn cập nhật lịch trống và giá cả để tránh tình trạng "Overbooking" (đặt trùng phòng).</li>
                </ul>
                
                <h3 class="policy-subsection-title">3.2. Chăm sóc khách hàng</h3>
                <ul>
                    <li>Hỗ trợ khách trong suốt quá trình lưu trú.</li>
                    <li>Giải quyết kịp thời các vấn đề phát sinh tại chỗ nghỉ theo nguyên tắc tôn trọng và lịch sự.</li>
                    <li><strong>Nhắc nhở và hướng dẫn khách thực hiện thao tác nhận/trả phòng trực tiếp trên hệ thống NestAway để hoàn tất đơn đặt phòng một cách hợp lệ.</strong></li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">4. Chính sách tài chính và Thanh toán</h2>
                <ul>
                    <li><strong>Phí hoa hồng:</strong> NestAway sẽ thu một khoản phí dịch vụ (hoa hồng) trên tổng giá trị mỗi đơn đặt phòng thành công (tỷ lệ được thỏa thuận riêng hoặc theo chính sách mặc định hiển thị trên Dashboard).</li>
                    <li><strong>Chu kỳ thanh toán:</strong> Doanh thu sẽ được đối soát và thanh toán vào tài khoản ngân hàng của Chủ nhà sau khi khách hoàn tất trả phòng và đơn hàng chuyển sang trạng thái "Hoàn thành".</li>
                    <li>Mọi sai sót về thông tin tài khoản ngân hàng do Chủ nhà cung cấp dẫn đến việc thanh toán thất bại, Chủ nhà sẽ tự chịu trách nhiệm.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">5. Xử lý vi phạm</h2>
                <p>NestAway có quyền tạm khóa hoặc xóa tài khoản Đối tác vĩnh viễn nếu phát hiện các hành vi sau:</p>
                <ul>
                    <li>Cung cấp thông tin giả mạo hoặc lừa đảo khách hàng.</li>
                    <li>Hủy phòng của khách không có lý do chính đáng liên tục nhiều lần.</li>
                    <li>Chất lượng phòng không đúng như mô tả, nhận nhiều phản hồi tiêu cực từ khách hàng.</li>
                    <li>Vi phạm nghiêm trọng chính sách cộng đồng và pháp luật Việt Nam.</li>
                </ul>
            </div>
            
            <div class="policy-section">
                <h2 class="policy-section-title">6. Cập nhật điều khoản</h2>
                <p>NestAway có quyền cập nhật, sửa đổi các điều khoản này bất cứ lúc nào. Việc bạn tiếp tục sử dụng nền tảng sau khi có thay đổi đồng nghĩa với việc bạn chấp nhận các điều khoản mới.</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/pages/host-terms.blade.php ENDPATH**/ ?>