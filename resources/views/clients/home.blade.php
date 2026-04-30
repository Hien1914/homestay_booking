@extends('clients.layout.app')

@section('title', 'Trang chủ')

@section('content')

<style>
  @import url('{{ asset('css/clients/home.css') }}');
</style>

<section class="hero-section position-relative">
  <div class="hero-bg-slider position-absolute top-0 start-0 w-100 h-100">
    <div class="hero-slide active position-absolute top-0 start-0 w-100 h-100">
      <img src="{{ asset('img/hero1.jpg') }}" class="w-100 h-100 object-fit-cover" alt="Hero 1">
    </div>
    <div class="hero-slide position-absolute top-0 start-0 w-100 h-100">
      <img src="{{ asset('img/hero2.webp') }}" class="w-100 h-100 object-fit-cover" alt="Hero 2">
    </div>
    <div class="hero-slide position-absolute top-0 start-0 w-100 h-100">
      <img src="{{ asset('img/hero3.jpg') }}" class="w-100 h-100 object-fit-cover" alt="Hero 3">
    </div>
    <div class="hero-slide position-absolute top-0 start-0 w-100 h-100">
      <img src="{{ asset('img/hero4.jpg') }}" class="w-100 h-100 object-fit-cover" alt="Hero 4">
    </div>
  </div>

  <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>

  <div class="container-setting position-relative" style="z-index: 2;">
    <div class="row hero-row align-items-center justify-content-center py-5">
      <div class="col-12 col-lg-11 col-xl-10 text-center">
        <h1 class="hero-title display-3 text-white mb-3">
          Tìm tổ ấm của bạn <br> ở mọi nơi trên đất Việt
        </h1>

        <p class="hero-sub lead text-white text-opacity-85 mb-4 mx-auto" style="max-width: 600px;">
          Khám phá những homestay độc đáo, gần gũi thiên nhiên và đầy cảm hứng cho chuyến đi của bạn.
        </p>

      </div>
    </div>
  </div>
</section>

<section class="container-setting py-5">
    <div class="row g-5 g-lg-5 align-items-center">
      <div class="col-lg-6">
        <img src="{{ asset('img/anh_about.png') }}" alt="NestAway trải nghiệm homestay" class="about-img-main w-100" width="700" height="875" loading="lazy">
      </div>
      <div class="col-lg-6 about-section-copy">
        <h4 class="text-uppercase fw-bold mb-2" style="color: var(--green-700)">Về NestAway</h4>
        <h3 class="display-6 mb-4 about-section-title" style="font-weight: 500; font-family: 'Google Sans', sans-serif;">
          Nền tảng kết nối cho người Việt du lịch
        </h3>
        <p class="text-muted mb-4 about-section-lead">
          NestAway kết nối du khách với những homestay có cá tính rõ ràng, thông tin minh bạch và trải nghiệm gần gũi hơn với địa phương.
        </p>

        <ul class="about-features list-unstyled mb-4">
          <li class="about-feat">
            <span class="about-feat-icon" aria-hidden="true">
              <img src="{{ asset('img/icon/khoa.svg') }}" alt="" width="24" height="28">
            </span>
            <div class="about-feat-body">
              <h3 class="about-feat-title">Thanh toán bảo mật 100%</h3>
              <p class="about-feat-desc">Mọi giao dịch được mã hóa SSL, rõ ràng trạng thái thanh toán và lịch sử đặt phòng.</p>
            </div>
          </li>
          <li class="about-feat">
            <span class="about-feat-icon" aria-hidden="true">
              <img src="{{ asset('img/icon/done.svg') }}" alt="" width="33" height="33">
            </span>
            <div class="about-feat-body">
              <h3 class="about-feat-title">Nguồn phòng được kiểm duyệt</h3>
              <p class="about-feat-desc">Thông tin homestay, tiện ích, hình ảnh và giá đều được đồng bộ từ cơ sở dữ liệu quản trị.</p>
            </div>
          </li>
          <li class="about-feat">
            <span class="about-feat-icon" aria-hidden="true">
              <img src="{{ asset('img/icon/hotro.svg') }}" alt="" width="24" height="24">
            </span>
            <div class="about-feat-body">
              <h3 class="about-feat-title">Hỗ trợ 24/7 tiếng Việt</h3>
              <p class="about-feat-desc">Từ lúc chọn phòng đến lúc nhận phòng, người dùng luôn có kênh hỗ trợ rõ ràng và nhanh chóng.</p>
            </div>
          </li>
        </ul>
        <div class="text-center">
          <a href="{{ route('pages.about') }}" 
            class="btn" 
            style="color: white; background-color: var(--green-900); padding: 12px 26px; border-radius: 32px;">
              Tìm hiểu thêm
          </a>
      </div>
      </div>
  </div>
</section>

@include('clients.layout.partials.destination-carousel')

@include('clients.layout.partials.homestay-carousel')

<section class="container-setting bg-white">
    <div class="text-center mb-5">
      <h4 class="text-success text-uppercase fw-bold mb-2">Quy trình</h4>
      <h2 class="display-6 fw-bold" style="font-family: 'Google Sans', sans-serif;">
        Đặt phòng chỉ với<br/>3 bước đơn giản
      </h2>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="text-center p-4 bg-white rounded shadow-sm h-100">
          <div class="step-num">1</div>
          <h5 class="fw-semibold mb-3" style="font-family: 'Google Sans', sans-serif;">Tìm kiếm và khám phá</h5>
          <p class="text-muted small">Lọc theo địa điểm, ngày đi, số khách và ngân sách để thấy ngay những homestay phù hợp.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="text-center p-4 bg-white rounded shadow-sm h-100">
          <div class="step-num">2</div>
          <h5 class="fw-semibold mb-3" style="font-family: 'Google Sans', sans-serif;">So sánh và chọn phòng</h5>
          <p class="text-muted small">Xem ảnh thật, giá theo đêm, tiện nghi và đánh giá trước khi đưa ra quyết định.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="text-center p-4 bg-white rounded shadow-sm h-100">
          <div class="step-num">3</div>
          <h5 class="fw-semibold mb-3" style="font-family: 'Google Sans', sans-serif;">Đặt chỗ và tận hưởng</h5>
          <p class="text-muted small">Nhận xác nhận nhanh, liên hệ chủ nhà dễ dàng và bắt đầu chuyến đi theo đúng kế hoạch.</p>
        </div>
      </div>
    </div>
</section>

@include('clients.layout.partials.testimonial-carousel')

<section class="container-setting py-5 bg-white text-black text-center">
    <h2 class="display-5 fw-light mb-3" style="font-family: 'Google Sans', sans-serif;">
      Bắt đầu hành trình của bạn ngay hôm nay
    </h2>
    <p class="lead mb-4">
      Từ những căn nhà nhỏ giữa đồi thông đến villa sát biển, mọi lựa chọn đều đang sẵn sàng trong hệ thống.
    </p>
    <a href="{{ route('pages.search') }}" class="btn btn-success btn-lg rounded-pill px-5 fw-semibold shadow-sm">Tìm homestay ngay →</a>
</section>

@push('scripts')
<script src="{{ asset('js/carousel.js') }}"></script>
<script>
(function() {
  const slides = document.querySelectorAll('.hero-slide');
  let currentSlide = 0;

  function nextSlide() {
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % slides.length;
    slides[currentSlide].classList.add('active');
  }

  setInterval(nextSlide, 3000);
})();

const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.1 });
</script>
@endpush

@endsection
