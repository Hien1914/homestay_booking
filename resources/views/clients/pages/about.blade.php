@extends('clients.layout.app')

@section('title', 'Về chúng tôi')

@section('content')
<link rel="stylesheet" href="{{ asset('css/clients/about.css') }}">

<div class="about-page-wrapper">
    <!-- Hero/Story Section -->
    <section class="about-hero py-5">
        <div class="container container-setting">
            <div class="row align-items-center g-5">
                <div class="col-lg-8 about-hero-text">
                    <span class="about-hero-badge mb-3 d-inline-block">Câu chuyện thương hiệu</span>
                    <h1 class="about-hero-title mb-4">Khởi nguồn từ tiếng gọi của đại ngàn</h1>
                    <p class="about-hero-subtitle mb-3">NestAway được tạo ra từ một mong muốn đơn giản: giúp mọi người tìm được một nơi ở phù hợp cho mỗi chuyến đi.</p>
                    <p class="about-hero-desc mb-3">Chúng tôi hiểu rằng, một không gian lưu trú không chỉ là nơi nghỉ chân, mà còn là nơi mang lại cảm giác thoải mái và những trải nghiệm đáng nhớ. Vì vậy, NestAway ra đời để kết nối du khách với những homestay đa dạng, tiện nghi và đáng tin cậy.</p>
                    <p class="about-hero-desc">NestAway không chỉ là nền tảng đặt phòng, mà còn là nơi bắt đầu cho những hành trình trọn vẹn.</p>
                </div>
                <div class="col-lg-4 about-hero-image">
                    <img src="https://images.unsplash.com/photo-1510798831971-661eb04b3739?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="NestAway Story" class="img-fluid rounded-4 shadow" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="about-mission-vision py-5">
        <div class="container container-setting">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mission-vision-card h-100 p-4 border rounded-4 text-center">
                        <div class="card-icon mb-3">
                            <i class="fas fa-bullseye fa-2x text-success"></i>
                        </div>
                        <h3 class="mb-3">Sứ Mệnh</h3>
                        <p class="mb-0">Kết nối du khách với những homestay chất lượng cao, giúp mỗi chuyến du lịch trở thành một trải nghiệm đáng nhớ, an toàn và đầy ý nghĩa.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mission-vision-card h-100 p-4 border rounded-4 text-center">
                        <div class="card-icon mb-3">
                            <i class="fas fa-eye fa-2x text-success"></i>
                        </div>
                        <h3 class="mb-3">Tầm Nhìn</h3>
                        <p class="mb-0">Trở thành nền tảng ứng dụng hàng đầu tại Việt Nam trong lĩnh vực cho thuê homestay, tạo ra một cộng đồng du khách và chủ nhà tin cậy và lâu dài.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="about-values-section py-5 bg-light">
        <div class="container container-setting">
            <div class="values-header text-center mb-5">
                <h2 class="values-title display-6 fw-bold mb-3">Giá Trị Cốt Lõi</h2>
                <p class="values-subtitle text-muted lead">Nền tảng của sự tin cậy và phát triển tại NestAway</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="value-item p-4 text-center">
                        <div class="value-icon mb-3 text-success">
                            <i class="fas fa-heart fa-2x"></i>
                        </div>
                        <h3 class="h5 fw-bold">Sự Chân Thực</h3>
                        <p class="small text-muted mb-0">Mỗi homestay trên NestAway đều được xác minh kỹ lưỡng, mang đến trải nghiệm chân thực nhất cho du khách.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="value-item p-4 text-center">
                        <div class="value-icon mb-3 text-success">
                            <i class="fas fa-leaf fa-2x"></i>
                        </div>
                        <h3 class="h5 fw-bold">Bền Vững</h3>
                        <p class="small text-muted mb-0">Chúng tôi cam kết phát triển du lịch có trách nhiệm, bảo vệ môi trường và văn hóa địa phương.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="value-item p-4 text-center">
                        <div class="value-icon mb-3 text-success">
                            <i class="fas fa-shield-alt fa-2x"></i>
                        </div>
                        <h3 class="h5 fw-bold">Sự Tin Cậy</h3>
                        <p class="small text-muted mb-0">Xây dựng niềm tin thông qua dịch vụ minh bạch, hỗ trợ tận tâm và cam kết chất lượng.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="value-item p-4 text-center">
                        <div class="value-icon mb-3 text-success">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h3 class="h5 fw-bold">Cộng Đồng</h3>
                        <p class="small text-muted mb-0">Kết nối du khách với chủ nhà, tạo nên một cộng đồng yêu du lịch và chia sẻ trải nghiệm.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="about-cta-section py-5">
        <div class="container container-setting">
            <div class="about-cta-box text-center p-5 bg-success text-white rounded-4 shadow">
                <h2 class="about-cta-title display-6 fw-bold mb-3">Sẵn sàng viết nên câu chuyện của riêng bạn?</h2>
                <p class="about-cta-subtitle mb-4 lead">Hãy để chúng tôi đưa bạn đến những góc khuất tuyệt đẹp của Việt Nam, nơi thiên nhiên và tâm hồn hòa làm một.</p>
                <a href="{{ route('pages.search') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-bold">
                    <i class="fas fa-search me-2"></i> Khám phá các homestay ngay
                </a>
            </div>
        </div>
    </section>
</div>

@endsection
