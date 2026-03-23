<!-- FEATURED ROOMS - Nền trắng -->
<section class="section-py bg-white reveal featured-rooms-section">
  <div class="container-setting">
    <div class="d-flex justify-content-between align-items-center mb-5">
      <div>
        <h4 class="text-primary text-uppercase fw-bold mb-2">Nổi bật</h4>
        <h2 class="display-6 fw-light" style="font-family: 'Google Sans', sans-serif;">
          Homestay <span class="text-soft-blue">được yêu thích</span><br/>nhất tuần này
        </h2>
      </div>
      <a href="{{ route('rooms.search') }}" class="text-primary fw-semibold text-decoration-none">Xem tất cả →</a>
    </div>

    @php
      $featuredRooms = [
        [
          'id' => 'd1',
          'img' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80',
          'badge' => ['class' => 'bg-primary', 'text' => 'Mới'],
          'location' => 'Đà Lạt, Lâm Đồng',
          'name' => 'Pine Valley Retreat',
          'amenities' => [
            ['icon' => 'bed.svg', 'text' => '2 phòng ngủ'],
            ['icon' => 'bath.svg', 'text' => '2 WC'],
            ['icon' => 'leaf.svg', 'text' => 'Sân vườn'],
          ],
          'price' => '800.000đ',
          'rating' => '4.9',
          'reviews' => '(128)',
          'reviewItems' => [
            [
              'avatar' => 'L',
              'name' => 'Linh Nguyễn',
              'date' => '2 ngày trước',
              'rating' => '5.0',
              'comment' => 'Phòng sạch, view đẹp và chủ nhà phản hồi rất nhanh. Mình ở 2 đêm mà thấy cực kỳ thoải mái.',
            ],
            [
              'avatar' => 'M',
              'name' => 'Minh Trần',
              'date' => '1 tuần trước',
              'rating' => '4.8',
              'comment' => 'Vị trí thuận tiện, đi lại dễ dàng. Tiện ích đầy đủ đúng như mô tả, không gian yên tĩnh để nghỉ ngơi.',
            ],
            [
              'avatar' => 'A',
              'name' => 'Anh Anh',
              'date' => '2 tuần trước',
              'rating' => '4.9',
              'comment' => 'Không khí trong phòng dễ chịu, giường ngủ thoải mái. Đặc biệt thích khu vực sân vườn buổi sáng.',
            ],
          ],
        ],
        [
          'id' => 'd2',
          'img' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&q=80',
          'badge' => ['class' => 'bg-danger', 'text' => 'Hot'],
          'location' => 'Hội An, Quảng Nam',
          'name' => 'Lantern House Hội An',
          'amenities' => [
            ['icon' => 'bed.svg', 'text' => '3 phòng ngủ'],
            ['icon' => 'bath.svg', 'text' => '2 WC'],
            ['icon' => 'pool.svg', 'text' => 'Hồ bơi'],
          ],
          'price' => '1.200.000đ',
          'rating' => '4.8',
          'reviews' => '(94)',
          'reviewItems' => [
            [
              'avatar' => 'N',
              'name' => 'Ngọc Nhi',
              'date' => '3 ngày trước',
              'rating' => '4.9',
              'comment' => 'Đúng kiểu “chill” luôn. Nhà có hồ bơi sạch, chụp ảnh đẹp, nhân viên hỗ trợ thân thiện.',
            ],
            [
              'avatar' => 'K',
              'name' => 'Khánh Kỳ',
              'date' => '5 ngày trước',
              'rating' => '4.7',
              'comment' => 'Trang trí dễ thương, phòng đủ đồ dùng cơ bản. Đi ra khu trung tâm cũng không quá xa.',
            ],
            [
              'avatar' => 'T',
              'name' => 'Thảo Tiên',
              'date' => '2 tuần trước',
              'rating' => '4.8',
              'comment' => 'Mọi thứ vận hành ổn định, hệ thống WC sạch sẽ. Gia đình mình rất hài lòng.',
            ],
          ],
        ],
        [
          'id' => 'd3',
          'img' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=600&q=80',
          'badge' => null,
          'location' => 'Sapa, Lào Cai',
          'name' => 'Cloud Nine Sapa Lodge',
          'amenities' => [
            ['icon' => 'bed.svg', 'text' => '1 phòng ngủ'],
            ['icon' => 'bath.svg', 'text' => '1 WC'],
            ['icon' => 'fire.svg', 'text' => 'Lò sưởi'],
          ],
          'price' => '650.000đ',
          'rating' => '4.7',
          'reviews' => '(76)',
          'reviewItems' => [
            [
              'avatar' => 'H',
              'name' => 'Hương Phạm',
              'date' => '1 ngày trước',
              'rating' => '4.8',
              'comment' => 'Sapa se lạnh nên mình rất thích lò sưởi. Phòng ấm áp, sạch sẽ và yên tĩnh về đêm.',
            ],
            [
              'avatar' => 'S',
              'name' => 'Sơn Lê',
              'date' => '1 tuần trước',
              'rating' => '4.6',
              'comment' => 'Đường vào hơi nhỏ nhưng đi êm, chỗ ở đúng chuẩn. Nhân viên hướng dẫn tận tình.',
            ],
            [
              'avatar' => 'V',
              'name' => 'Việt Vương',
              'date' => '2 tuần trước',
              'rating' => '4.7',
              'comment' => 'Chăn ga thơm, không gian thoáng. Mình ở nghỉ dưỡng rất ổn.',
            ],
          ],
        ],
        [
          'id' => 'd4',
          'img' => 'https://images.unsplash.com/photo-1613977257365-aaae5a9817ff?w=600&q=80',
          'badge' => ['class' => 'bg-primary', 'text' => 'Mới'],
          'location' => 'Ninh Bình',
          'name' => 'Lotus Valley Retreat',
          'amenities' => [
            ['icon' => 'bed.svg', 'text' => '2 phòng ngủ'],
            ['icon' => 'bath.svg', 'text' => '2 WC'],
            ['icon' => 'leaf.svg', 'text' => 'View núi'],
          ],
          'price' => '920.000đ',
          'rating' => '4.9',
          'reviews' => '(112)',
          'reviewItems' => [
            [
              'avatar' => 'T',
              'name' => 'Thảo My',
              'date' => '2 ngày trước',
              'rating' => '5.0',
              'comment' => 'Ở cực kỳ thích. View núi sáng sớm đẹp hơn ảnh. Nội thất mới, cảm giác “như ở nhà”.',
            ],
            [
              'avatar' => 'Q',
              'name' => 'Quang Huy',
              'date' => '1 tuần trước',
              'rating' => '4.8',
              'comment' => 'Không gian thoáng, yên tĩnh. Phù hợp đi cặp hoặc đi nhóm nhỏ.',
            ],
            [
              'avatar' => 'B',
              'name' => 'Bảo Bình',
              'date' => '2 tuần trước',
              'rating' => '4.9',
              'comment' => 'Chủ nhà hỗ trợ nhanh, hướng dẫn check-in rõ ràng. Mình sẽ quay lại nếu có dịp.',
            ],
          ],
        ],
        [
          'id' => 'd5',
          'img' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=600&q=80',
          'badge' => ['class' => 'bg-danger', 'text' => 'Hot'],
          'location' => 'Phú Quốc, Kiên Giang',
          'name' => 'Sea Breeze Villa',
          'amenities' => [
            ['icon' => 'bed.svg', 'text' => '3 phòng ngủ'],
            ['icon' => 'bath.svg', 'text' => '3 WC'],
            ['icon' => 'pool.svg', 'text' => 'Hồ bơi riêng'],
          ],
          'price' => '1.550.000đ',
          'rating' => '4.8',
          'reviews' => '(89)',
          'reviewItems' => [
            [
              'avatar' => 'H',
              'name' => 'Hà Hoàng',
              'date' => '3 ngày trước',
              'rating' => '4.9',
              'comment' => 'Phòng rộng, hồ bơi riêng đúng như mong đợi. Buổi tối ra ban công ngắm cảnh rất chill.',
            ],
            [
              'avatar' => 'Đ',
              'name' => 'Đức Dũng',
              'date' => '1 tuần trước',
              'rating' => '4.7',
              'comment' => 'Sạch sẽ và tiện nghi. Đặc biệt là điều hòa mát nhanh, ngủ rất ngon.',
            ],
            [
              'avatar' => 'G',
              'name' => 'Giang Nguyên',
              'date' => '2 tuần trước',
              'rating' => '4.8',
              'comment' => 'Gia đình mình có một kỳ nghỉ tuyệt vời. Nhân viên hỗ trợ nhanh, không làm mình chờ.',
            ],
          ],
        ],
        [
          'id' => 'd6',
          'img' => 'https://images.unsplash.com/photo-1475855581690-80accde3a8a1?w=600&q=80',
          'badge' => null,
          'location' => 'Mộc Châu, Sơn La',
          'name' => 'Pine Hill Cabin',
          'amenities' => [
            ['icon' => 'bed.svg', 'text' => '1 phòng ngủ'],
            ['icon' => 'bath.svg', 'text' => '1 WC'],
            ['icon' => 'fire.svg', 'text' => 'Lò sưởi'],
          ],
          'price' => '780.000đ',
          'rating' => '4.7',
          'reviews' => '(64)',
          'reviewItems' => [
            [
              'avatar' => 'M',
              'name' => 'Mai Linh',
              'date' => '2 ngày trước',
              'rating' => '4.8',
              'comment' => 'Cảm giác như ở homestay trên núi thật sự. Lò sưởi giữ ấm tốt, đêm ngủ cực êm.',
            ],
            [
              'avatar' => 'P',
              'name' => 'Phúc Minh',
              'date' => '5 ngày trước',
              'rating' => '4.6',
              'comment' => 'Phòng sạch, có đủ đồ dùng. View đẹp, đi dạo buổi sáng rất thích.',
            ],
            [
              'avatar' => 'R',
              'name' => 'Rita',
              'date' => '2 tuần trước',
              'rating' => '4.7',
              'comment' => 'Chất lượng ổn, không gian riêng tư. Nếu đi nhóm nhỏ thì quá hợp.',
            ],
          ],
        ],
      ];
    @endphp

    <div class="featured-slider" id="featured-slider">
      <button type="button" class="featured-slider-nav prev" aria-label="Phòng trước" data-featured-prev>
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button type="button" class="featured-slider-nav next" aria-label="Phòng tiếp theo" data-featured-next>
        <i class="fa-solid fa-chevron-right"></i>
      </button>
      <div class="featured-slider-viewport" data-featured-viewport>
        <div class="featured-slider-track" data-featured-track>
      @foreach($featuredRooms as $room)
          <article class="featured-slide">
          <a href="{{ route('homestay.show', ['id' => $room['id'] ?? 'd1']) }}" class="text-decoration-none text-body d-block h-100">
          <div class="room-card card border-0 shadow-sm h-100">
            <div class="position-relative">
              <img src="{{ $room['img'] }}" alt="Room" class="room-img card-img-top">
              @if(!empty($room['badge']))
                <span class="room-badge badge {{ $room['badge']['class'] }}">{{ $room['badge']['text'] }}</span>
              @endif
                  <button class="room-fav" type="button" aria-label="Thêm vào yêu thích" data-favorite-toggle>
                    <img src="{{ asset('img/icon/heart-outline.svg') }}" alt="" class="room-fav-icon room-fav-icon--default">
                    <img src="{{ asset('img/icon/heart-filled.svg') }}" alt="" class="room-fav-icon room-fav-icon--active">
                  </button>
            </div>
            <div class="card-body">
                  <p class="small text-muted mb-2 room-meta-item">
                    <img src="{{ asset('img/icon/location.svg') }}" alt="" class="room-meta-icon">
                    <span>{{ $room['location'] }}</span>
                  </p>
              <h5 class="card-title fw-semibold mb-3" style="font-family: 'Google Sans', sans-serif;">{{ $room['name'] }}</h5>
                  <div class="d-flex gap-3 mb-3 small text-muted room-amenity-list">
                @foreach($room['amenities'] as $am)
                      <span class="room-meta-item">
                        <img src="{{ asset('img/icon/' . $am['icon']) }}" alt="" class="room-meta-icon">
                        <span>{{ $am['text'] }}</span>
                      </span>
                @endforeach
              </div>
              <div class="d-flex justify-content-between align-items-center pt-3 border-top">
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
          return (
            !!targetEl.closest('[data-favorite-toggle]') ||
            !!targetEl.closest('.room-fav')
          );
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
          const threshold = 50;
          if (dragDeltaX <= -threshold) next();
          else if (dragDeltaX >= threshold) prev();
          else updateSlider(true);
          startAuto();
        };

        nextBtn?.addEventListener('click', () => {
          next();
          startAuto();
        });
        prevBtn?.addEventListener('click', () => {
          prev();
          startAuto();
        });

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

