<section class="section-py bg-light-blue reveal dest-section">
  <div class="container-setting">
    <div class="dest-section-head">
      <div>
        <h4 class="text-primary text-uppercase fw-bold mb-2">Điểm đến nổi bật</h4>
        <h2 class="display-5 fw-light mb-0" style="font-family: 'Google Sans', sans-serif;">
          Khám phá những <span class="text-soft-blue">điểm đến</span><br>được đặt nhiều trên NestAway
        </h2>
      </div>
      <div class="dest-slider-nav">
        <button type="button" class="dest-nav-btn dest-nav-prev" id="dest-prev" aria-label="Trước" hidden>
          <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button type="button" class="dest-nav-btn dest-nav-next" id="dest-next" aria-label="Tiếp" hidden>
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div>
    </div>

    <div class="dest-slider-viewport">
      <div class="dest-slider-track" id="dest-track">
        @forelse($featuredDestinations ?? [] as $dest)
          <div class="dest-slide">
            <a href="{{ route('destinations.show', $dest['slug']) }}" class="dest-card">
              <div class="dest-card-media">
                <img src="{{ $dest['image'] }}" alt="{{ $dest['name'] }}" class="dest-card-img" loading="lazy">
                <div class="dest-card-overlay"></div>
              </div>
              <div class="dest-card-content">
                <span class="dest-card-name">{{ $dest['name'] }}</span>
              </div>
            </a>
          </div>
        @empty
          <p style="color:#6b7280; padding: 24px 0;">Chưa có điểm đến nào.</p>
        @endforelse
      </div>
    </div>
  </div>
</section>

<script>
(function () {
  var track   = document.getElementById('dest-track');
  var btnPrev = document.getElementById('dest-prev');
  var btnNext = document.getElementById('dest-next');
  if (!track) return;

  var slides  = Array.from(track.children);
  var total   = slides.length;
  var current = 0;

  function getPerView() {
    var w = window.innerWidth;
    if (w >= 1200) return 5;
    if (w >= 768)  return 3;
    return 2;
  }

  function render() {
    var pv         = getPerView();
    var pct        = 100 / pv;
    var needsSlide = total > pv;

    slides.forEach(function (s) {
      s.style.flex     = '0 0 ' + pct + '%';
      s.style.maxWidth = pct + '%';
    });

    if (btnPrev) btnPrev.hidden = !needsSlide;
    if (btnNext) btnNext.hidden = !needsSlide;

    var maxIdx = needsSlide ? total - pv : 0;
    if (current > maxIdx) current = 0;
    moveTo(current, false);
  }

  function moveTo(idx, animate) {
    var pv     = getPerView();
    var maxIdx = Math.max(0, total - pv);
    current    = Math.max(0, Math.min(idx, maxIdx));

    if (!animate) track.style.transition = 'none';
    track.style.transform = 'translateX(-' + (current * (100 / pv)) + '%)';
    if (!animate) requestAnimationFrame(function () { track.style.transition = ''; });
  }

  if (btnPrev) btnPrev.addEventListener('click', function () {
    var pv     = getPerView();
    var maxIdx = Math.max(0, total - pv);
    moveTo(current <= 0 ? maxIdx : current - 1, true);
  });

  if (btnNext) btnNext.addEventListener('click', function () {
    var pv     = getPerView();
    var maxIdx = Math.max(0, total - pv);
    moveTo(current >= maxIdx ? 0 : current + 1, true);
  });

  var resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () { current = 0; render(); }, 150);
  });

  render();
})();
</script>
