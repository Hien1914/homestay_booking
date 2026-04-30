@extends('clients.layout.app')

@section('title', 'Điều khoản dịch vụ')

@section('content')
<link rel="stylesheet" href="{{ asset('css/clients/policy.css') }}">
<div class="policy-page-wrapper">
    <div class="container-setting policy-container">
        <h1 class="policy-page-title">ĐIỀU KHOẢN DỊCH VỤ</h1>
        
        <div class="policy-content">
            <div class="policy-section">
                <h2 class="policy-section-title">1. Giới thiệu</h2>
                <p>Chào mừng bạn đến với nền tảng đặt phòng homestay NestAway. Khi truy cập và sử dụng website, bạn đồng ý tuân thủ các điều khoản và điều kiện được quy định dưới đây. Nếu bạn không đồng ý với bất kỳ nội dung nào, vui lòng ngừng sử dụng dịch vụ.</p>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">2. Định nghĩa</h2>
                
                <ul>
                    <li>Người dùng: Cá nhân hoặc tổ chức sử dụng dịch vụ để tìm kiếm và đặt phòng.</li>
                    <li>Hệ thống/Nền tảng: Website NestAway – nơi cung cấp dịch vụ đặt phòng homestay.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">3. Phạm vi dịch vụ</h2>
                <p>NestAway cung cấp nền tảng trung gian cho phép:</p>
                <ul>
                    <li>Tìm kiếm và xem thông tin homestay</li>
                    <li>Đặt phòng trực tuyến</li>
                    <li>Thanh toán </li>
                    <li>Đánh giá và phản hồi sau khi lưu trú</li>
                </ul>
                <p>Chúng tôi không trực tiếp sở hữu hoặc vận hành các homestay.</p>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">4. Tài khoản người dùng</h2>
                
                <h3 class="policy-subsection-title">4.1. Đăng ký tài khoản</h3>
                <ul>
                    <li>Người dùng cần cung cấp thông tin chính xác, đầy đủ</li>
                    <li>Không sử dụng thông tin giả mạo hoặc của người khác</li>
                </ul>

                <h3 class="policy-subsection-title">4.2. Bảo mật tài khoản</h3>
                <ul>
                    <li>Người dùng tự chịu trách nhiệm bảo mật thông tin đăng nhập</li>
                    <li>Không chia sẻ tài khoản cho người khác</li>
                </ul>

                <h3 class="policy-subsection-title">4.3. Vi phạm tài khoản</h3>
                <p>Chúng tôi có quyền tạm khóa hoặc chấm dứt tài khoản nếu:</p>
                <ul>
                    <li>Phát hiện hành vi gian lận</li>
                    <li>Vi phạm pháp luật</li>
                    <li>Gây ảnh hưởng đến hệ thống hoặc người dùng khác</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">5. Quy định đặt phòng</h2>
                
                <ul>
                    <li>Người dùng cần kiểm tra kỹ thông tin homestay trước khi đặt</li>
                    <li>Đặt phòng chỉ có hiệu lực khi thanh toán thành công hoặc hệ thống xác nhận</li>
                    <li>NestAway có quyền từ chối đặt phòng trong một số trường hợp hợp lý</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">6. Thanh toán</h2>
                <ul>
                    <li>Người dùng thực hiện thanh toán qua các phương thức được hỗ trợ</li>
                    <li>Giá hiển thị có thể chưa bao gồm các chi phí phát sinh</li>
                    <li>Việc thanh toán không thành công có thể dẫn đến hủy đặt phòng</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">7. Hủy và hoàn tiền</h2>
                
                <ul>
                    <li>Việc hủy và hoàn tiền tuân theo chính sách riêng của từng homestay</li>
                    <li>Người dùng cần đọc kỹ trước khi đặt</li>
                    <li>Hệ thống chỉ hỗ trợ xử lý, không quyết định trực tiếp chính sách hoàn tiền</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">8. Quyền và nghĩa vụ của người dùng</h2>
                <p>Người dùng cam kết:</p>
                <ul>
                    <li>Sử dụng dịch vụ đúng mục đích</li>
                    <li>Không thực hiện hành vi gian lận, lừa đảo</li>
                    <li>Tôn trọng tài sản và quy định của homestay</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">9. Nội dung và đánh giá</h2>
                <ul>
                    <li>Người dùng có thể đăng đánh giá sau khi lưu trú</li>
                    <li>Nội dung phải trung thực, không vi phạm pháp luật</li>
                    <li>Chúng tôi có quyền xóa nội dung không phù hợp</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">10. Giới hạn trách nhiệm</h2>
                <ul>
                    <li>Chúng tôi không đảm bảo mọi thông tin luôn chính xác tuyệt đối</li>
                    <li>NestAway có quyền thay đổi hoặc cập nhật thông tin homestay khi cần thiết</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">11. Bảo mật thông tin</h2>
                <p>Thông tin người dùng được thu thập và bảo vệ theo <a href="{{ route('pages.privacy_policy') }}" class="fw-bold text-decoration-none">Chính sách bảo mật</a> của chúng tôi.</p>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">12. Chấm dứt dịch vụ</h2>
                <p>Chúng tôi có quyền:</p>
                <ul>
                    <li>Tạm ngừng hoặc chấm dứt cung cấp dịch vụ</li>
                    <li>Hủy tài khoản nếu phát hiện vi phạm</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">13. Liên hệ</h2>
                <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ:</p>
                <ul>
                    <li>📞 0967 798 825</li>
                    <li>📧 support@nestaway.vn</li>
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
