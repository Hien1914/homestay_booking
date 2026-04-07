<section class="section-py bg-white reveal featured-rooms-section">
  <div class="container-setting">
    <div class="d-flex justify-content-between align-items-center mb-5 gap-3 flex-wrap">
      <div>
        <h2 class="display-6 fw-light" style="font-family: 'Google Sans', sans-serif;">
          Danh sách Homestay <span class="text-soft-blue"> nổi bật</span>
        </h2>
      </div>
      <a href="{{ route('rooms.search') }}" class="text-primary fw-semibold text-decoration-none">Xem tất cả →</a>
    </div>

    <div class="featured-slider" id="featured-slider">
      <button type="button" class="featured-slider-nav prev" aria-label="Phòng trước" data-featured-prev>
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button type="button" class="featured-slider-nav next" aria-label="Phòng tiếp theo" data-featured-next>
        <i class="fa-solid fa-chevron-right"></i>
      </button>

      <div class="featured-slider-viewport" data-featured-viewport>
        <div class="featured-slider-track" data-featured-track>
          @foreach(($featuredHomestays ?? []) as $room)
            <article class="featured-slide">
              <a href="{{ route('homestay.show', ['id' => $room['id']]) }}" class="text-decoration-none text-body d-block h-100">
                <div class="room-card card border-0 shadow-sm h-100">
                  <div class="position-relative">
                    <img src="{{ $room['img'] }}" alt="{{ $room['name'] }}" class="room-img card-img-top">
                    @if(!empty($room['badge']))
                      <span class="room-badge badge {{ $room['badge']['class'] }}">{{ $room['badge']['text'] }}</span>
                    @endif
                    <button class="room-fav" type="button" aria-label="Thêm vào yêu thích" data-favorite-toggle>
                      <img src="{{ asset('img/icon/heart-outline.svg') }}" alt="" class="room-fav-icon room-fav-icon--default">
                      <img src="{{ asset('img/icon/heart-filled.svg') }}" alt="" class="room-fav-icon room-fav-icon--active">
                    </button>
                  </div>

                  <div class="card-body d-flex flex-column">
                    <p class="small text-muted mb-2 room-meta-item">
                      <img src="{{ asset('img/icon/location.svg') }}" alt="" class="room-meta-icon">
                      <span>{{ $room['location'] }}</span>
                    </p>

                    <h5 class="card-title fw-semibold mb-2" style="font-family: 'Google Sans', sans-serif;">{{ $room['name'] }}</h5>
                    <p class="text-muted small mb-3 room-summary">{{ $room['description'] }}</p>

                    <div class="d-flex gap-3 mb-3 small text-muted room-amenity-list">
                      @foreach($room['amenities'] as $am)
                        <span class="room-meta-item">
                          <img src="{{ asset('img/icon/' . $am['icon']) }}" alt="" class="room-meta-icon">
                          <span>{{ $am['text'] }}</span>
                        </span>
                      @endforeach
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                      <div>
                        <span class="h5 text-primary fw-bold mb-0">{{ $room['price'] }}</span>
                        <small class="text-muted"> / đêm</small>
                      </div>
                      <div class="small fw-semibold room-rating">
                        <img src="{{ asset('img/icon/star.svg') }}" alt="" class="room-meta-icon room-meta-icon--star">
                        <span>{{ $room['rating'] }}</span>
                        <span class="text-muted">{{ $room['reviews'] }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </article>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

@once
  @push('scripts')
    <script>
      (function () {
        const slider = document.getElementById('featured-slider');
        if (!slider) return;

        const track = slider.querySelector('[data-featured-track]');
        const viewport = slider.querySelector('[data-featured-viewport]');
        const slides = Array.from(slider.querySelectorAll('.featured-slide'));
        const prevBtn = slider.querySelector('[data-featured-prev]');
        const nextBtn = slider.querySelector('[data-featured-next]');

        if (!track || !viewport || slides.length === 0) return;

        const shouldIgnoreDrag = (targetEl) => {
          if (!targetEl || !targetEl.closest) return false;
          return !!targetEl.closest('[data-favorite-toggle]') || !!targetEl.closest('.room-fav');
        };

        const getPerView = () => {
          if (window.innerWidth < 768) return 1;
          if (window.innerWidth < 1200) return 2;
          return 3;
        };

        let index = 0;
        let perView = getPerView();
        let maxIndex = Math.max(0, slides.length - perView);
        let autoTimer = null;
        let startX = 0;
        let isDragging = false;
        let dragDeltaX = 0;

        const updateSlider = (animate = true) => {
          perView = getPerView();
          maxIndex = Math.max(0, slides.length - perView);
          index = Math.min(index, maxIndex);

          const step = slides[0].offsetWidth + 20;
          track.style.transition = animate ? 'transform 0.45s ease' : 'none';
          track.style.transform = `translateX(-${index * step}px)`;
        };

        const next = () => {
          index = index >= maxIndex ? 0 : index + 1;
          updateSlider(true);
        };

        const prev = () => {
          index = index <= 0 ? maxIndex : index - 1;
          updateSlider(true);
        };

        const startAuto = () => {
          stopAuto();
          autoTimer = setInterval(next, 3500);
        };

        const stopAuto = () => {
          if (!autoTimer) return;
          clearInterval(autoTimer);
          autoTimer = null;
        };

        const dragStart = (clientX) => {
          isDragging = true;
          startX = clientX;
          dragDeltaX = 0;
          stopAuto();
          track.style.transition = 'none';
        };

        const dragMove = (clientX) => {
          if (!isDragging) return;
          dragDeltaX = clientX - startX;
          const step = slides[0].offsetWidth + 20;
          track.style.transform = `translateX(-${index * step - dragDeltaX}px)`;
        };

        const dragEnd = () => {
          if (!isDragging) return;
          isDragging = false;
          if (dragDeltaX <= -50) next();
          else if (dragDeltaX >= 50) prev();
          else updateSlider(true);
          startAuto();
        };

        nextBtn?.addEventListener('click', () => { next(); startAuto(); });
        prevBtn?.addEventListener('click', () => { prev(); startAuto(); });

        viewport.addEventListener('mouseenter', stopAuto);
        viewport.addEventListener('mouseleave', startAuto);
        viewport.addEventListener('touchstart', (e) => {
          if (shouldIgnoreDrag(e.target)) return;
          dragStart(e.touches[0].clientX);
        }, { passive: true });
        viewport.addEventListener('touchmove', (e) => dragMove(e.touches[0].clientX), { passive: true });
        viewport.addEventListener('touchend', dragEnd);
        viewport.addEventListener('mousedown', (e) => {
          if (shouldIgnoreDrag(e.target)) return;
          dragStart(e.clientX);
        });
        window.addEventListener('mousemove', (e) => dragMove(e.clientX));
        window.addEventListener('mouseup', dragEnd);
        window.addEventListener('resize', () => updateSlider(false));

        slider.querySelectorAll('[data-favorite-toggle]').forEach((btn) => {
          btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            btn.classList.toggle('is-active');
          });
        });

        updateSlider(false);
        startAuto();
      })();
    </script>
  @endpush
@endonce
