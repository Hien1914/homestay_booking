<footer class="site-footer">
  <div class="container-footer">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="footer-logo">
          <x-logo-icon width="28" height="28" aria-hidden="true" />
          <span>NestAway</span>
        </div>
        <p class="footer-desc">
          Nền tảng đặt phòng homestay uy tín tại Việt Nam. 
          Kết nối du khách với những không gian lưu trú đẹp, độc đáo và đáng nhớ.
        </p>
        <div class="footer-contact">
          <p><i class="fas fa-phone"></i> 0967 798 825</p>
          <p><i class="fas fa-envelope"></i> support@nestaway.vn</p>
          <p><i class="fas fa-map-marker-alt"></i> Hà Nội, Việt Nam</p>
        </div>
      </div>

      <div class="footer-cols">
        <div class="footer-col">
          <h4 class="footer-col-title">Khám phá</h4>
          <a href="{{ route('pages.about') }}" class="footer-link">Giới thiệu</a>
          <a href="{{ route('blog.index') }}" class="footer-link">Bài viết về du lịch</a>
        </div>

        <div class="footer-col">
          <h4 class="footer-col-title">Hỗ trợ</h4>
          <a href="{{ route('pages.faq') }}" class="footer-link">Câu hỏi thường gặp (FAQ)</a>
          <a href="{{ route('pages.policy_booking') }}" class="footer-link">Chính sách đặt phòng & Hủy phòng</a>
          <a href="{{ route('pages.terms') }}" class="footer-link">Điều khoản dịch vụ</a>
          <a href="{{ route('pages.privacy_policy') }}" class="footer-link">Chính sách bảo mật</a>
        </div>
      </div>
    </div>
  </div>
  
  <div class="footer-bottom">
    <div class="container-footer">
      <div class="footer-bottom-content">
        <p class="text-center">© {{ date('Y') }} NestAway. Bảo lưu mọi quyền.</p>
      </div>
    </div>
  </div>
</footer>
