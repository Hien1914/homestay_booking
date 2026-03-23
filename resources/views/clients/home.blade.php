@extends('clients.layout.app')

@section('title', 'Trang chủ')

@section('content')

<style>
  @import url('{{ asset('css/clients/home.css') }}');
</style>

<!-- HERO SECTION -->
<section class="hero-section position-relative">
  <!-- Background Slider -->
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
  
  <!-- Overlay -->
  <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
  
  <!-- Content với container-setting -->
  <div class="container-setting position-relative" style="z-index: 2;">
    <div class="row hero-row align-items-center justify-content-center py-5">
      <div class="col-12 col-lg-11 col-xl-10 text-center">
        <div class="hero-badge d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-pill px-4 py-2 mb-4 text-white small fw-medium">
          <span class="bg-primary rounded-circle d-inline-block" style="width: 6px; height: 6px;"></span>
          Hơn 2,000+ homestay trên toàn quốc
        </div>
        
        <h1 class="hero-title display-3 fw-light text-white mb-3">
          Tìm <span class="text-soft-blue">tổ ấm</span> của bạn<br/>ở mọi nơi trên đất Việt
        </h1>
        
        <p class="hero-sub lead text-white text-opacity-85 mb-5 mx-auto" style="max-width: 600px;">
          Khám phá những homestay độc đáo, gần gũi thiên nhiên và đầy cảm hứng cho chuyến đi của bạn.
        </p>
        
        @include('clients.partials.booking-form')
      </div>
    </div>
  </div>
</section>

<!-- ABOUT SECTION -->
<section class="section-py bg-white reveal about-section">
  <div class="container-setting">
    <div class="row g-5 g-lg-5 align-items-center">
      <div class="col-lg-6">
        <img src="{{ asset('img/anh_about.png') }}" alt="NestAway — trải nghiệm homestay" class="about-img-main w-100" width="700" height="875" loading="lazy">
      </div>
      <div class="col-lg-6 about-section-copy">
        <h4 class="text-primary text-uppercase fw-bold mb-2">Về NestAway</h4>
        <h2 class="display-5 fw-light mb-4 about-section-title" style="font-family: 'Google Sans', sans-serif;">
          Nền tảng kết nối<br/><span class="text-soft-blue">tin cậy nhất</span> cho người Việt du lịch
        </h2>
        <p class="text-muted mb-4 about-section-lead">NestAway ra đời từ niềm đam mê du lịch và mong muốn mang đến cho mỗi du khách Việt Nam một trải nghiệm lưu trú chân thực, ấm áp và đáng nhớ.</p>

        <ul class="about-features list-unstyled mb-4">
          <li class="about-feat">
            <span class="about-feat-icon" aria-hidden="true">
              <img src="{{ asset('img/icon/khoa.svg') }}" alt="" width="24" height="28">
            </span>
            <div class="about-feat-body">
              <h3 class="about-feat-title">Thanh toán bảo mật 100%</h3>
              <p class="about-feat-desc">Mọi giao dịch được mã hóa SSL, hoàn tiền dễ dàng nếu có sự cố.</p>
            </div>
          </li>
          <li class="about-feat">
            <span class="about-feat-icon" aria-hidden="true">
              <img src="{{ asset('img/icon/done.svg') }}" alt="" width="33" height="33">
            </span>
            <div class="about-feat-body">
              <h3 class="about-feat-title">Homestay được kiểm duyệt</h3>
              <p class="about-feat-desc">Đội ngũ NestAway xác thực từng căn nhà trước khi đăng lên nền tảng.</p>
            </div>
          </li>
          <li class="about-feat">
            <span class="about-feat-icon" aria-hidden="true">
              <img src="{{ asset('img/icon/hotro.svg') }}" alt="" width="24" height="24">
            </span>
            <div class="about-feat-body">
              <h3 class="about-feat-title">Hỗ trợ 24/7 tiếng Việt</h3>
              <p class="about-feat-desc">Đội ngũ chăm sóc khách hàng luôn sẵn sàng qua chat, gọi điện hoặc email.</p>
            </div>
          </li>
        </ul>

        <button type="button" class="btn btn-primary btn-lg rounded-pill px-4 about-more-btn">Tìm hiểu thêm →</button>
      </div>
    </div>
  </div>
</section>

@include('clients.partials.home-categories')

<!-- FEATURED ROOMS - Nền trắng -->
@include('clients.partials.home-featured-rooms')

<!-- AMENITIES - Nền xanh nhạt rõ ràng -->
<section class="section-py bg-light-blue reveal">
  <div class="container-setting">
    <div class="text-center mb-5">
      <h4 class="text-primary text-uppercase fw-bold mb-2">Tiện nghi</h4>
      <h2 class="display-6 fw-light mb-3" style="font-family: 'Google Sans', sans-serif;">
        Tất cả tiện nghi bạn<br/><span class="text-soft-blue">cần cho kỳ nghỉ</span> hoàn hảo
      </h2>
      <p class="text-muted mx-auto" style="max-width: 600px;">Mỗi homestay trên NestAway đều được gắn nhãn tiện nghi rõ ràng.</p>
    </div>
    
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="amen-card card border bg-white h-100">
          <div class="card-body">
            <div class="amen-icon mb-3">📶</div>
            <h6 class="fw-semibold mb-2">WiFi tốc độ cao</h6>
            <p class="small text-muted mb-0">Kết nối internet ổn định, phù hợp làm việc và giải trí.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="amen-card card border bg-white h-100">
          <div class="card-body">
            <div class="amen-icon mb-3">❄️</div>
            <h6 class="fw-semibold mb-2">Điều hoà nhiệt độ</h6>
            <p class="small text-muted mb-0">Hệ thống điều hòa 2 chiều, mát vào mùa hè – ấm vào mùa đông.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="amen-card card border bg-white h-100">
          <div class="card-body">
            <div class="amen-icon mb-3">🍳</div>
            <h6 class="fw-semibold mb-2">Bếp đầy đủ tiện nghi</h6>
            <p class="small text-muted mb-0">Lò vi sóng, bếp từ, nồi cơm điện và các dụng cụ nấu ăn cơ bản.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="amen-card card border bg-white h-100">
          <div class="card-body">
            <div class="amen-icon mb-3">🏊</div>
            <h6 class="fw-semibold mb-2">Hồ bơi riêng</h6>
            <p class="small text-muted mb-0">Hồ bơi sạch, an toàn – điểm nhấn hoàn hảo cho kỳ nghỉ gia đình.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS - Nền xanh lá nhạt rõ ràng -->
<section class="section-py bg-white reveal">
  <div class="container-setting">
    <div class="text-center mb-5">
      <h4 class="text-success text-uppercase fw-bold mb-2">Quy trình</h4>
      <h2 class="display-6 fw-light" style="font-family: 'Google Sans', sans-serif;">
        Đặt phòng chỉ với<br/><span class="text-soft-blue">3 bước đơn giản</span>
      </h2>
    </div>
    
    <div class="row g-4">
      <div class="col-md-4">
        <div class="text-center p-4 bg-white rounded shadow-sm h-100">
          <div class="step-num">1</div>
          <h5 class="fw-semibold mb-3" style="font-family: 'Google Sans', sans-serif;">Tìm kiếm & Khám phá</h5>
          <p class="text-muted small">Nhập địa điểm, ngày và số lượng khách. Duyệt qua hàng nghìn homestay độc đáo được lọc theo nhu cầu của bạn.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="text-center p-4 bg-white rounded shadow-sm h-100">
          <div class="step-num">2</div>
          <h5 class="fw-semibold mb-3" style="font-family: 'Google Sans', sans-serif;">Chọn & Đặt phòng</h5>
          <p class="text-muted small">Xem ảnh, tiện nghi và đánh giá thực tế. Nhấn đặt phòng và thanh toán an toàn qua cổng thanh toán được mã hóa.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="text-center p-4 bg-white rounded shadow-sm h-100">
          <div class="step-num">3</div>
          <h5 class="fw-semibold mb-3" style="font-family: 'Google Sans', sans-serif;">Check-in & Tận hưởng</h5>
          <p class="text-muted small">Nhận xác nhận ngay lập tức, liên hệ với chủ nhà và bắt đầu hành trình đáng nhớ của bạn.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section-py reveal testimonials-showcase">
  <div class="container-setting">
    @php
      $testimonials = [
        ['avatar' => 'L', 'name' => 'Linh Nguyễn', 'role' => 'Graphic Designer', 'comment' => 'Trải nghiệm đặt phòng rất mượt. Homestay đúng mô tả, không gian sạch và chủ nhà hỗ trợ nhanh.'],
        ['avatar' => 'M', 'name' => 'Minh Trần', 'role' => 'Software Engineer', 'comment' => 'Mình đặt cho gia đình đi nghỉ cuối tuần, mọi thứ đúng như ảnh. Check-in đơn giản và tiện nghi đầy đủ.'],
        ['avatar' => 'A', 'name' => 'An Nhiên', 'role' => 'Model', 'comment' => 'Không gian chill và riêng tư, rất phù hợp để nghỉ dưỡng. Sẽ quay lại vào dịp gần nhất.'],
        ['avatar' => 'H', 'name' => 'Hương Phạm', 'role' => 'Content Creator', 'comment' => 'Đội hỗ trợ phản hồi nhanh, xử lý yêu cầu tốt. Mình rất yên tâm khi đặt qua nền tảng.'],
        ['avatar' => 'Q', 'name' => 'Quang Huy', 'role' => 'Photographer', 'comment' => 'View đẹp hơn mong đợi, chụp ảnh lên rất ổn. Giá hợp lý so với chất lượng nhận được.'],
        ['avatar' => 'T', 'name' => 'Thảo My', 'role' => 'Marketing Specialist', 'comment' => 'Thao tác tìm phòng và lọc phòng dễ dùng. Mình tìm được căn đúng nhu cầu chỉ trong vài phút.'],
      ];
    @endphp

    <div class="tsm-shell" id="testimonial-slider">
      <div class="tsm-top">
        <h3 class="tsm-title">Testimonials</h3>
        <div class="tsm-nav">
          <button type="button" class="tsm-nav-btn" data-tsm-prev aria-label="Đánh giá trước">
            <i class="fa-solid fa-chevron-left"></i>
          </button>
          <button type="button" class="tsm-nav-btn" data-tsm-next aria-label="Đánh giá tiếp theo">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
        </div>
      </div>

      <div class="tsm-viewport" data-tsm-viewport>
        <div class="tsm-track" data-tsm-track>
          @foreach($testimonials as $t)
            <article class="tsm-slide">
              <div class="tsm-card">
                <div class="tsm-avatar">{{ $t['avatar'] }}</div>
                <div class="tsm-head">
                  <div>
                    <div class="tsm-name">{{ $t['name'] }}</div>
                    <div class="tsm-role">{{ $t['role'] }}</div>
                  </div>
                  <div class="tsm-stars" aria-label="5 sao">
                    @for($i = 0; $i < 5; $i++)
                      <img src="{{ asset('img/icon/star.svg') }}" alt="" class="tsm-star-icon">
                    @endfor
                  </div>
                </div>
                <p class="tsm-comment">{{ $t['comment'] }}</p>
              </div>
            </article>
          @endforeach
        </div>
      </div>

      <div class="tsm-dots" data-tsm-dots></div>
    </div>
  </div>
</section>

<!-- CTA - Nền gradient primary -->
<section class="section-py bg-white text-black text-center reveal">
  <div class="container-setting-md">
    <h2 class="display-5 fw-light mb-3" style="font-family: 'Google Sans', sans-serif;">
      Bắt đầu hành trình của bạn ngay hôm nay
    </h2>
    <p class="lead mb-4">Đăng ký miễn phí và nhận ngay ưu đãi 15% cho lần đặt phòng đầu tiên</p>
    <button class="btn btn-primary btn-lg rounded-pill px-5 fw-semibold">Đặt phòng ngay →</button>
  </div>
</section>

@push('scripts')
<script>
// Hero Background Slider (Auto-slide every 3 seconds)
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

// Scroll Reveal Animation
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.1 });

reveals.forEach(reveal => observer.observe(reveal));

// Testimonials slider
(function () {
  const slider = document.getElementById('testimonial-slider');
  if (!slider) return;

  const viewport = slider.querySelector('[data-tsm-viewport]');
  const track = slider.querySelector('[data-tsm-track]');
  const slides = Array.from(slider.querySelectorAll('.tsm-slide'));
  const prevBtn = slider.querySelector('[data-tsm-prev]');
  const nextBtn = slider.querySelector('[data-tsm-next]');
  const dotsWrap = slider.querySelector('[data-tsm-dots]');
  if (!viewport || !track || slides.length === 0) return;

  const perView = () => (window.innerWidth < 768 ? 1 : 3);
  let index = 0;
  let autoTimer = null;
  let startX = 0;
  let dragDelta = 0;
  let dragging = false;

  const buildDots = () => {
    if (!dotsWrap) return;
    const pages = Math.max(1, slides.length - perView() + 1);
    dotsWrap.innerHTML = '';
    for (let i = 0; i < pages; i++) {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'tsm-dot' + (i === index ? ' is-active' : '');
      btn.setAttribute('aria-label', 'Tới đánh giá ' + (i + 1));
      btn.addEventListener('click', () => {
        index = i;
        update(true);
        startAuto();
      });
      dotsWrap.appendChild(btn);
    }
  };

  const updateDots = () => {
    if (!dotsWrap) return;
    dotsWrap.querySelectorAll('.tsm-dot').forEach((dot, i) => {
      dot.classList.toggle('is-active', i === index);
    });
  };

  const update = (animate = true) => {
    const max = Math.max(0, slides.length - perView());
    index = Math.max(0, Math.min(index, max));
    const step = slides[0].offsetWidth + 20;
    track.style.transition = animate ? 'transform .45s ease' : 'none';
    track.style.transform = `translateX(-${index * step}px)`;
    updateDots();
  };

  const next = () => {
    const max = Math.max(0, slides.length - perView());
    index = index >= max ? 0 : index + 1;
    update(true);
  };

  const prev = () => {
    const max = Math.max(0, slides.length - perView());
    index = index <= 0 ? max : index - 1;
    update(true);
  };

  const startAuto = () => {
    stopAuto();
    autoTimer = setInterval(next, 3600);
  };

  const stopAuto = () => {
    if (!autoTimer) return;
    clearInterval(autoTimer);
    autoTimer = null;
  };

  prevBtn?.addEventListener('click', () => { prev(); startAuto(); });
  nextBtn?.addEventListener('click', () => { next(); startAuto(); });

  viewport.addEventListener('mouseenter', stopAuto);
  viewport.addEventListener('mouseleave', startAuto);
  viewport.addEventListener('touchstart', (e) => {
    dragging = true;
    startX = e.touches[0].clientX;
    dragDelta = 0;
    stopAuto();
    track.style.transition = 'none';
  }, { passive: true });
  viewport.addEventListener('touchmove', (e) => {
    if (!dragging) return;
    dragDelta = e.touches[0].clientX - startX;
    const step = slides[0].offsetWidth + 20;
    track.style.transform = `translateX(-${index * step - dragDelta}px)`;
  }, { passive: true });
  viewport.addEventListener('touchend', () => {
    if (!dragging) return;
    dragging = false;
    if (dragDelta < -50) next();
    else if (dragDelta > 50) prev();
    else update(true);
    startAuto();
  });
  window.addEventListener('resize', () => {
    buildDots();
    update(false);
  });

  buildDots();
  update(false);
  startAuto();
})();

</script>
@endpush

@endsection