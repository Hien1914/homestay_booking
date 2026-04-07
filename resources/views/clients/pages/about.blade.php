@extends('clients.layout.app')

@section('title', 'Về chúng tôi')

@section('content')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">

<div class="about-page-wrapper">
    <!-- Hero/Story Section -->
    <section class="about-hero">
        <div class="container container-setting">
            <div class="about-hero-content">
                <div class="about-hero-text">
                    <span class="about-hero-badge">Câu chuyện thương hiệu</span>
                    <h1 class="about-hero-title">Khởi nguồn từ tiếng gọi của đại ngàn</h1>
                    <p class="about-hero-subtitle">NestAway được tạo ra từ một mong muốn đơn giản: giúp mọi người tìm được một nơi ở phù hợp cho mỗi chuyến đi.</p>
                    <p class="about-hero-desc">Chúng tôi hiểu rằng, một không gian lưu trú không chỉ là nơi nghỉ chân, mà còn là nơi mang lại cảm giác thoải mái và những trải nghiệm đáng nhớ. Vì vậy, NestAway ra đời để kết nối du khách với những homestay đa dạng, tiện nghi và đáng tin cậy.</p>
                    <p class="about-hero-desc">NestAway không chỉ là nền tảng đặt phòng, mà còn là nơi bắt đầu cho những hành trình trọn vẹn.</p>
                </div>
                <div class="about-hero-image">
                    <img src="https://images.unsplash.com/photo-1510798831971-661eb04b3739?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="NestAway Story" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="about-mission-vision">
        <div class="container container-setting">
            <div class="row">
                <div class="col-md-6">
                    <div class="mission-vision-card">
                        <div class="card-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3>Sứ Mệnh</h3>
                        <p>Kết nối du khách với những homestay chất lượng cao, giúp mỗi chuyến du lịch trở thành một trải nghiệm đáng nhớ, an toàn và đầy ý nghĩa.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mission-vision-card">
                        <div class="card-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3>Tầm Nhìn</h3>
                        <p>Trở thành nền tảng ứng dụng hàng đầu tại Việt Nam trong lĩnh vực cho thuê homestay, tạo ra một cộng đồng du khách và chủ nhà tin cậy và lâu dài.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="about-values-section">
        <div class="container container-setting">
            <div class="values-header">
                <h2 class="values-title">Giá Trị Cốt Lõi</h2>
                <p class="values-subtitle">Nền tảng của sự tin cậy và phát triển tại NestAway</p>
            </div>
            <div class="values-grid">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Sự Chân Thực</h3>
                    <p>Mỗi homestay trên NestAway đều được xác minh kỹ lưỡng, mang đến trải nghiệm chân thực nhất cho du khách.</p>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Bền Vững</h3>
                    <p>Chúng tôi cam kết phát triển du lịch có trách nhiệm, bảo vệ môi trường và văn hóa địa phương.</p>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Sự Tin Cậy</h3>
                    <p>Xây dựng niềm tin thông qua dịch vụ minh bạch, hỗ trợ tận tâm và cam kết chất lượng.</p>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Cộng Đồng</h3>
                    <p>Kết nối du khách với chủ nhà, tạo nên một cộng đồng yêu du lịch và chia sẻ trải nghiệm.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="about-cta-section">
        <div class="container container-setting">
            <div class="about-cta-box">
                <h2 class="about-cta-title">Sẵn sàng viết nên câu chuyện của riêng bạn?</h2>
                <p class="about-cta-subtitle">Hãy để chúng tôi đưa bạn đến những góc khuất tuyệt đẹp của Việt Nam, nơi thiên nhiên và tâm hồn hòa làm một.</p>
                <a href="{{ route('rooms.search') }}" class="about-cta-btn">
                    <i class="fas fa-search me-2"></i> Khám phá các homestay ngay
                </a>
            </div>
        </div>
    </section>
</div>

@endsection
