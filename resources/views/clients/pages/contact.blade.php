@extends('clients.layout.app')

@section('title', 'Liên hệ hỗ trợ')

@section('content')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
<div class="contact-page-wrapper">
    <div class="container container-setting">
        <div class="contact-container">
            <!-- Left Column: Info Setup -->
            <div class="contact-info-col">
                <div class="contact-header">
                    <h1 class="contact-title">Liên hệ hỗ trợ</h1>
                    <p class="contact-desc">Chúng tôi luôn sẵn sàng hỗ trợ bạn trong suốt quá trình sử dụng dịch vụ. Nếu bạn gặp bất kỳ vấn đề nào liên quan đến đặt phòng, thanh toán hoặc trải nghiệm lưu trú, hãy liên hệ với chúng tôi để được hỗ trợ nhanh chóng.</p>
                </div>
                
                <div class="contact-details">
                    <div class="contact-detail-item">
                        <i class="fas fa-phone-alt"></i>
                        <div class="contact-detail-content">
                            <h4>Hotline</h4>
                            <p><a href="tel:0967798825">0967 798 825</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-detail-item">
                        <i class="fas fa-envelope"></i>
                        <div class="contact-detail-content">
                            <h4>Email</h4>
                            <p><a href="mailto:support@nestaway.vn">support@nestaway.vn</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-detail-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="contact-detail-content">
                            <h4>Địa chỉ</h4>
                            <p>Hà Nội, Việt Nam</p>
                        </div>
                    </div>
                    
                    <div class="contact-detail-item">
                        <i class="fas fa-clock"></i>
                        <div class="contact-detail-content">
                            <h4>Thời gian hỗ trợ</h4>
                            <p>8:00 – 22:00<br><small>(tất cả các ngày trong tuần)</small></p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-support-list">
                    <h4>Các vấn đề chúng tôi hỗ trợ:</h4>
                    <ul>
                        <li>Hướng dẫn đặt phòng</li>
                        <li>Xử lý sự cố khi thanh toán</li>
                        <li>Hỗ trợ thay đổi hoặc hủy đặt phòng</li>
                        <li>Giải quyết vấn đề trong quá trình lưu trú</li>
                        <li>Tiếp nhận phản hồi và góp ý</li>
                    </ul>
                </div>
            </div>
            
            <!-- Right Column: Form Setup -->
            <div class="contact-form-col">
                <h2 class="contact-form-title">Gửi yêu cầu hỗ trợ</h2>
                <p class="contact-form-subtitle">Bạn cũng có thể gửi yêu cầu trực tiếp qua biểu mẫu liên hệ bên dưới. Sau khi gửi, chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
                
                <form action="#" method="POST" class="contact-form">
                    @csrf
                    <div class="form-group">
                        <label for="fullname">Họ và tên</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Nhập họ và tên của bạn" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" id="phone" name="phone" class="form-control" placeholder="Số điện thoại liên hệ" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Địa chỉ email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Nội dung cần hỗ trợ</label>
                        <textarea id="message" name="message" class="form-control" placeholder="Vui lòng mô tả chi tiết vấn đề bạn đang gặp phải..." required></textarea>
                    </div>
                    
                    <button type="submit" class="contact-submit-btn">
                        <i class="fas fa-paper-plane"></i> Gửi yêu cầu hỗ trợ
                    </button>
                    
                    <div class="contact-commitments">
                        <div class="commitment-item">
                            <i class="fas fa-bolt"></i> Phản hồi nhanh chóng
                        </div>
                        <div class="commitment-item">
                            <i class="fas fa-heart"></i> Hỗ trợ tận tình
                        </div>
                        <div class="commitment-item">
                            <i class="fas fa-shield-alt"></i> Bảo mật thông tin khách hàng
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- CTA -->
        <div class="contact-cta-wrapper">
            <h3>Bạn cần hỗ trợ ngay?</h3>
            <p>Hãy liên hệ với chúng tôi để được giải quyết nhanh chóng qua đường dây nóng.</p>
            <a href="tel:0967798825" class="btn btn-primary" style="background-color: var(--bs-primary); border: none; padding: 12px 30px; font-weight: 600; border-radius: 50px;">
                <i class="fas fa-phone-alt me-2"></i> Gọi 0967 798 825
            </a>
        </div>
    </div>
</div>
@endsection
