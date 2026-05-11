@extends('clients.layout.app')

@section('title', 'Chính sách đặt phòng & Hủy phòng')

@section('content')
<link rel="stylesheet" href="{{ asset('css/clients/policy.css') }}">
<div class="policy-page-wrapper">
    <div class="container-setting policy-container">
        <h1 class="policy-page-title">CHÍNH SÁCH ĐẶT PHÒNG & HỦY PHÒNG</h1>
        
        <div class="policy-content">
            <!-- 1. Giới thiệu -->
            <div class="policy-section">
                <h2 class="policy-section-title">1. Giới thiệu</h2>
                <p>Chính sách này quy định các điều kiện liên quan đến việc đặt phòng, thanh toán, thay đổi và hủy phòng trên nền tảng NestAway. Khi thực hiện đặt phòng, bạn đồng ý với các điều khoản được nêu dưới đây.</p>
            </div>

            <!-- 2. Chính sách đặt phòng -->
            <div class="policy-section">
                <h2 class="policy-section-title">2. Chính sách đặt phòng</h2>
                
                <h3 class="policy-subsection-title">2.1. Điều kiện đặt phòng</h3>
                <ul>
                    <li>Người dùng cần cung cấp đầy đủ và chính xác thông tin khi đặt phòng</li>
                    <li>Thông tin bao gồm: họ tên, số điện thoại, email, số lượng khách, thời gian lưu trú</li>
                    <li>Người dùng chịu trách nhiệm về tính chính xác của thông tin đã cung cấp</li>
                </ul>

                <h3 class="policy-subsection-title">2.2. Quy trình đặt phòng</h3>
                <p>Quy trình đặt phòng trên hệ thống gồm các bước:</p>
                <ul>
                    <li>Tìm kiếm và lựa chọn homestay phù hợp</li>
                    <li>Chọn ngày nhận phòng và trả phòng</li>
                    <li>Nhập thông tin khách hàng</li>
                    <li>Xác nhận thông tin đặt phòng</li>
                    <li>Thực hiện thanh toán (nếu có)</li>
                    <li>Nhận thông báo xác nhận đặt phòng</li>
                </ul>

                <h3 class="policy-subsection-title">2.3. Xác nhận đặt phòng</h3>
                <ul>
                    <li>Đặt phòng được xem là <strong>thành công</strong> khi:
                        <ul>
                            <li>Thanh toán đã được hoàn tất, hoặc</li>
                            <li>Hệ thống xác nhận đặt phòng</li>
                        </ul>
                    </li>
                    <li>Trong một số trường hợp:
                        <ul>
                            <li>NestAway có quyền từ chối nếu phòng không còn trống hoặc có sự cố phát sinh</li>
                            <li>Người dùng sẽ được thông báo và hoàn tiền (nếu đã thanh toán)</li>
                        </ul>
                    </li>
                </ul>

                <h3 class="policy-subsection-title">2.4. Giá phòng và chi phí</h3>
                <ul>
                    <li>Giá phòng được hiển thị trên hệ thống có thể thay đổi tùy theo:
                        <ul>
                            <li>Thời điểm đặt phòng</li>
                            <li>Ngày lễ, cuối tuần</li>
                        </ul>
                    </li>
                    <li>Một số chi phí có thể phát sinh:
                        <ul>
                            <li>Phí dịch vụ</li>
                            <li>Phí vệ sinh</li>
                            <li>Phụ thu thêm khách</li>
                        </ul>
                    </li>
                </ul>

                <h3 class="policy-subsection-title">2.5. Quy định Bắt buộc khi Nhận và Trả phòng</h3>
                <ul>
                    <li><strong>BẮT BUỘC 100%:</strong> Người dùng phải thực hiện xác nhận thao tác <strong>Nhận phòng (Check-in)</strong> và <strong>Trả phòng (Check-out)</strong> trực tiếp trên hệ thống NestAway tại phần <em>Lịch sử đặt phòng</em>.</li>
                    <li>Thao tác này là căn cứ hợp lệ để giải quyết mọi khiếu nại (nếu có) và đảm bảo quyền lợi của người dùng trong suốt quá trình lưu trú.</li>
                    <li>Trường hợp người dùng không thực hiện các thao tác xác nhận này trên hệ thống, NestAway có quyền từ chối hỗ trợ giải quyết các vấn đề phát sinh liên quan đến thời gian lưu trú, chất lượng phòng và thanh toán.</li>
                </ul>
            </div>

            <!-- 3. Chính sách thanh toán -->
            <div class="policy-section">
                <h2 class="policy-section-title">3. Chính sách thanh toán</h2>
                <ul>
                    <li>Người dùng có thể thanh toán qua các phương thức được hỗ trợ trên hệ thống</li>
                    <li>Thanh toán cần được thực hiện đúng thời hạn để giữ phòng</li>
                    <li>Trong trường hợp thanh toán không thành công:
                        <ul>
                            <li>Đặt phòng có thể bị hủy tự động</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- 4. Chính sách thay đổi đặt phòng -->
            <div class="policy-section">
                <h2 class="policy-section-title">4. Chính sách thay đổi đặt phòng</h2>
                
                <h3 class="policy-subsection-title">4.1. Thay đổi thông tin</h3>
                <ul>
                    <li>Người dùng có thể yêu cầu thay đổi:
                        <ul>
                            <li>Ngày nhận/trả phòng</li>
                            <li>Số lượng khách</li>
                        </ul>
                    </li>
                    <li>Việc thay đổi cần:
                        <ul>
                            <li>Được hệ thống chấp nhận</li>
                            <li>Có thể phát sinh chi phí</li>
                        </ul>
                    </li>
                </ul>

                <h3 class="policy-subsection-title">4.2. Điều kiện áp dụng</h3>
                <ul>
                    <li>Yêu cầu thay đổi cần được gửi trước thời gian nhận phòng</li>
                    <li>Không áp dụng với các đơn đặt phòng không cho phép thay đổi</li>
                </ul>
            </div>

            <!-- 5. Chính sách hủy phòng -->
            <div class="policy-section">
                <h2 class="policy-section-title">5. Chính sách hủy phòng</h2>
                
                <h3 class="policy-subsection-title">5.1. Điều kiện hủy phòng</h3>
                <ul>
                    <li>Người dùng có thể hủy đặt phòng theo chính sách của từng homestay</li>
                    <li>Thời gian hủy càng sớm, khả năng hoàn tiền càng cao</li>
                </ul>

                <h3 class="policy-subsection-title">5.2. Các mức hủy phổ biến</h3>
                <ol type="a">
                    <li><strong>Hủy miễn phí:</strong>
                        <ul>
                            <li>Áp dụng khi hủy trước thời gian quy định (ví dụ: 3 – 7 ngày trước ngày nhận phòng)</li>
                        </ul>
                    </li>
                    <li><strong>Hủy có phí:</strong>
                        <ul>
                            <li>Áp dụng khi hủy gần ngày nhận phòng</li>
                            <li>Có thể bị trừ một phần chi phí (ví dụ: 30% – 70%)</li>
                        </ul>
                    </li>
                    <li><strong>Không hoàn tiền:</strong>
                        <ul>
                            <li>Áp dụng khi:
                                <ul>
                                    <li>Hủy sát giờ nhận phòng</li>
                                    <li>Không đến nhận phòng (no-show)</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ol>

                <h3 class="policy-subsection-title">5.3. Quy trình hủy phòng</h3>
                <ul>
                    <li>Truy cập vào mục “Đơn đặt phòng”</li>
                    <li>Chọn đơn cần hủy</li>
                    <li>Gửi yêu cầu hủy</li>
                    <li>Xác nhận hủy theo hướng dẫn</li>
                </ul>
            </div>

            <!-- 6. Chính sách hoàn tiền -->
            <div class="policy-section">
                <h2 class="policy-section-title">6. Chính sách hoàn tiền</h2>
                <ul>
                    <li>Việc hoàn tiền phụ thuộc vào:
                        <ul>
                            <li>Thời gian hủy</li>
                            <li>Chính sách của homestay</li>
                        </ul>
                    </li>
                    <li>Thời gian xử lý hoàn tiền:
                        <ul>
                            <li>Từ 3 – 7 ngày làm việc</li>
                        </ul>
                    </li>
                    <li>Số tiền hoàn lại có thể bị trừ:
                        <ul>
                            <li>Phí dịch vụ</li>
                            <li>Phí xử lý (nếu có)</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- 7. Trách nhiệm của các bên -->
            <div class="policy-section">
                <h2 class="policy-section-title">7. Trách nhiệm của các bên</h2>
                
                <h3 class="policy-subsection-title">7.1. Người dùng</h3>
                <ul>
                    <li>Cung cấp thông tin chính xác</li>
                    <li>Tuân thủ quy định của homestay</li>
                    <li>Thanh toán đúng hạn</li>
                </ul>

                <h3 class="policy-subsection-title">7.2. Nền tảng NestAway</h3>
                <ul>
                    <li>Cung cấp hệ thống đặt phòng chất lượng</li>
                    <li>Đảm bảo thông tin homestay chính xác</li>
                    <li>Hỗ trợ xử lý khi có vấn đề phát sinh</li>
                </ul>
            </div>

            <!-- 8. Trường hợp bất khả kháng -->
            <div class="policy-section">
                <h2 class="policy-section-title">8. Trường hợp bất khả kháng</h2>
                <p>Trong các trường hợp ngoài kiểm soát như:</p>
                <ul>
                    <li>Thiên tai</li>
                    <li>Dịch bệnh</li>
                    <li>Sự cố kỹ thuật</li>
                </ul>
                <p>Việc hủy hoặc thay đổi đặt phòng sẽ được xem xét linh hoạt tùy từng trường hợp.</p>
            </div>

            <!-- 9. Lưu ý quan trọng -->
            <div class="policy-section">
                <h2 class="policy-section-title">9. Lưu ý quan trọng</h2>
                <ul>
                    <li>Người dùng cần đọc kỹ chính sách trước khi đặt phòng</li>
                    <li>Mỗi homestay có thể có quy định riêng bổ sung</li>
                    <li>Chính sách này có thể được cập nhật mà không cần thông báo trước</li>
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
