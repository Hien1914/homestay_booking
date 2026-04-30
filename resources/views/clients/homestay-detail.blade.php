@extends('clients.layout.app')

@section('title', $homestay->title ?? 'Chi tiết homestay')

@section('content')
@php
    $cancelLabels = [
        'flexible' => 'Linh hoạt — Hoàn tiền miễn phí trước 1 ngày nhận phòng',
        'moderate' => 'Trung bình — Hoàn tiền 50% khi hủy trước 5 ngày',
        'strict' => 'Nghiêm ngặt — Chỉ hoàn tiền khi hủy trước 14 ngày',
    ];
    $policy = $homestay->cancellation_policy ?? 'moderate';
    $policyText = $cancelLabels[$policy] ?? $cancelLabels['moderate'];
@endphp
<style>
  @import url('{{ asset('css/clients/homestay-detail.css') }}');
</style>

<section class="homestay-detail-page">
    <div class="container-setting">
      {{-- Breadcrumb --}}
      <nav class="homestay-detail-breadcrumb" aria-label="Breadcrumb">
        <ol class="homestay-detail-breadcrumb__list">
          @foreach($breadcrumbs ?? [] as $i => $crumb)
            <li class="homestay-detail-breadcrumb__item">
              @if(isset($crumb['url']) && $i < count($breadcrumbs ?? []) - 1)
                <a href="{{ $crumb['url'] }}" class="homestay-detail-breadcrumb__link">{{ $crumb['label'] }}</a>
              @else
                <span class="homestay-detail-breadcrumb__current" aria-current="page">{{ $crumb['label'] }}</span>
              @endif
              @if($i < count($breadcrumbs ?? []) - 1)
                <i class="fa-solid fa-chevron-right homestay-detail-breadcrumb__sep" aria-hidden="true"></i>
              @endif
            </li>
          @endforeach
        </ol>
      </nav>
  
      <div class="homestay-detail-success" id="booking-success-msg" role="status" hidden>
        <i class="fa-solid fa-circle-check"></i>
        <span>Đặt phòng thành công! Mã đặt phòng: <strong id="booking-code-display"></strong>. Vui lòng tiến hành thanh toán.</span>
      </div>
  
      {{-- Hero: Tên, địa chỉ, rating --}}
      <header class="homestay-detail-hero">
        <h1 class="homestay-detail-hero__title">{{ $homestay->title }}</h1>
        <div class="homestay-detail-hero__stars">
          @for($i = 1; $i <= 5; $i++)
            <i class="fa-solid fa-star homestay-detail-hero__star {{ $i <= round($homestay->reviews_avg_rating ?? 0) ? 'is-filled' : '' }}"></i>
          @endfor
          <span class="ms-2 text-muted" style="font-size: 0.9rem;">({{ number_format((float) ($homestay->reviews_avg_rating ?? 0), 1, ',', '.') }})</span>
        </div>
        <p class="homestay-detail-hero__location">
          <i class="fa-solid fa-location-dot"></i>
          {{ $homestay->address ?? '' }}
          @if(!empty($homestay->province))
            <span class="homestay-detail-hero__province">— {{ $homestay->province }}</span>
          @endif
        </p>
        <div class="homestay-detail-hero__meta">
          <span><i class="fa-solid fa-users"></i> {{ (int) ($homestay->max_guests ?? 0) }} khách</span>
          @if(!empty($homestay->rooms_array))
            @foreach($homestay->rooms_array as $type => $qty)
              @if($qty > 0 && isset(\App\Models\HomestayRoom::ROOM_TYPES[$type]))
                <span>
                  {{ $qty }} {{ \App\Models\HomestayRoom::ROOM_TYPES[$type] }}
                </span>
              @endif
            @endforeach
          @endif
        </div>
      </header>
  
      {{-- Gallery --}}
      @php $imgs = $homestay->images; @endphp
      <div class="homestay-detail-gallery-slider">
        @if($imgs->count() > 0)
          <div class="homestay-detail-gallery-track" id="homestay-gallery-track">
            @foreach($imgs as $index => $image)
              <div class="homestay-detail-gallery-slide">
                <img
                  src="{{ asset('storage/' . $image->image_url) }}"
                  alt="{{ $homestay->title }} - ảnh {{ $index + 1 }}"
                  loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                  data-gallery-image
                  data-gallery-index="{{ $index }}"
                >
              </div>
            @endforeach
          </div>
          @if(count($imgs) > 1)
            <button type="button" class="homestay-detail-gallery-nav homestay-detail-gallery-nav--prev" id="gallery-prev" aria-label="Ảnh trước">
              <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button type="button" class="homestay-detail-gallery-nav homestay-detail-gallery-nav--next" id="gallery-next" aria-label="Ảnh sau">
              <i class="fa-solid fa-chevron-right"></i>
            </button>
            <div class="homestay-detail-gallery-counter" id="gallery-counter">1 / {{ count($imgs) }}</div>
          @endif
          <div class="homestay-detail-gallery-thumbs" id="homestay-gallery-thumbs">
            @foreach($imgs as $index => $image)
              <button type="button" class="homestay-detail-gallery-thumb {{ $index === 0 ? 'is-active' : '' }}" data-gallery-thumb="{{ $index }}">
                <img src="{{ asset('storage/' . $image->image_url) }}" alt="Thumbnail {{ $index + 1 }}">
              </button>
            @endforeach
          </div>
        @else
          <div class="homestay-detail-gallery-slide">
            <img src="https://placehold.co/1200x700" alt="{{ $homestay->title }}">
          </div>
        @endif
      </div>

      <div class="homestay-detail-lightbox" id="homestay-lightbox" hidden>
        <button type="button" class="homestay-detail-lightbox-close" id="lightbox-close" aria-label="Đóng xem ảnh">
          <i class="fa-solid fa-xmark"></i>
        </button>
        <img src="" alt="Ảnh chi tiết homestay" id="lightbox-image">
        @if(count($imgs) > 1)
          <button type="button" class="homestay-detail-lightbox-nav homestay-detail-lightbox-nav--prev" id="lightbox-prev" aria-label="Ảnh trước">
            <i class="fa-solid fa-chevron-left"></i>
          </button>
          <button type="button" class="homestay-detail-lightbox-nav homestay-detail-lightbox-nav--next" id="lightbox-next" aria-label="Ảnh sau">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
        @endif
      </div>
  
      <div class="homestay-detail-layout">
        <div class="homestay-detail-main">
  
          <div class="homestay-detail-section homestay-detail-card" id="mo-ta">
            <h2 class="homestay-detail-card__title">Mô tả</h2>
            <p>{{ $homestay->description ?? 'Không có mô tả.' }}</p>
          </div>

          {{-- New Section: Rooms & Amenities --}}
          <div class="homestay-detail-section homestay-detail-card" id="tien-nghi">
            <h2 class="homestay-detail-card__title">Phòng & Tiện nghi</h2>
            
            <div class="homestay-detail-rooms-grid mb-4">
                <h3 class="homestay-detail-sub-title mb-3" style="font-size: 1.1rem; color: #333;"><i class="bi bi-door-open me-2"></i>Các phòng hiện có</h3>
                <div class="row row-cols-2 row-cols-md-3 g-3">
                    @foreach($homestay->rooms as $room)
                        @if($room->quantity > 0)
                            <div class="col">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    <span>{{ $room->quantity }} {{ \App\Models\HomestayRoom::ROOM_TYPES[$room->feature_type] ?? $room->feature_type }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="homestay-detail-amenities-list">
                <h3 class="homestay-detail-sub-title mb-3" style="font-size: 1.1rem; color: #333;"><i class="bi bi-stars me-2"></i>Tiện nghi chỗ nghỉ</h3>
                <div class="row row-cols-2 row-cols-md-3 g-3">
                    @foreach($homestay->amenities as $amenity)
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check2 text-success me-2" style="font-size: 1.2rem;"></i>
                                <span>{{ $amenity->name }} @if($amenity->pivot->quantity > 1) (x{{ $amenity->pivot->quantity }}) @endif</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
          </div>
  
          <div class="homestay-detail-section homestay-detail-card" id="danh-gia">
            <h2 class="homestay-detail-card__title">Đánh giá & bình luận</h2>
            <div class="homestay-detail-reviews-header">
              <div class="homestay-detail-reviews-score">
                <i class="fa-solid fa-star"></i>
                <span>{{ number_format((float) ($homestay->reviews_avg_rating ?? 0), 1, ',', '.') }}</span>
              </div>
              <span class="homestay-detail-reviews-count">{{ (int) ($homestay->reviews_count ?? 0) }} đánh giá</span>
            </div>

            @if(count($reviewBreakdown) > 0)
              <div class="homestay-detail-review-breakdown">
                @foreach($reviewBreakdown as $row)
                  <div class="homestay-detail-review-breakdown-row">
                    <span class="homestay-detail-review-breakdown-left">
                      {{ $row['star'] }} <i class="fa-solid fa-star"></i> — {{ $row['label'] }}
                    </span>
                    <div class="homestay-detail-review-breakdown-bar">
                      <span style="width: {{ $row['percent'] }}%"></span>
                    </div>
                    <span class="homestay-detail-review-breakdown-count">{{ $row['count'] }}</span>
                  </div>
                @endforeach
              </div>
            @endif

            @auth
              @if($reviewableBooking)
              <form method="POST" action="{{ route('homestay.review.store', ['slug' => $homestay->slug, 'booking' => $reviewableBooking->id]) }}" class="homestay-detail-review-form">
                @csrf
                <h3 class="homestay-detail-review-form__title">Viết đánh giá của bạn</h3>
                <div class="homestay-detail-review-form__stars">
                  @for($star = 5; $star >= 1; $star--)
                    <label>
                      <input type="radio" name="rating" value="{{ $star }}" {{ old('rating') == $star ? 'checked' : '' }} required>
                      <span>{{ $star }} sao</span>
                    </label>
                  @endfor
                </div>
                <textarea name="comment" rows="3" maxlength="1000" placeholder="Chia sẻ trải nghiệm thực tế của bạn..." required>{{ old('comment') }}</textarea>
                <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
              </form>
              @elseif(auth()->user())
                <p class="homestay-detail-empty-reviews">Bạn chỉ có thể đánh giá sau khi đã hoàn thành một booking tại homestay này.</p>
              @endif
            @else
              <p class="homestay-detail-empty-reviews">Vui lòng <a href="{{ route('login.page') }}?redirect={{ urlencode(request()->fullUrl()) }}">đăng nhập</a> để gửi đánh giá.</p>
            @endauth
   
            @if($reviews->count() > 0)
              @foreach($reviews as $r)
                <div class="homestay-detail-review">
                  <div class="homestay-detail-review-header">
                    <div class="homestay-detail-review-avatar">{{ mb_substr($r->user?->full_name ?? 'K', 0, 1) }}</div>
                    <div class="homestay-detail-review-meta">
                      <div class="homestay-detail-review-name">{{ $r->user?->full_name ?? 'Khách' }}</div>
                      <div class="homestay-detail-review-date">{{ optional($r->created_at)->format('d/m/Y') }}</div>
                    </div>
                    <div class="homestay-detail-review-stars">
                      @for($i = 1; $i <= 5; $i++)
                        <i class="fa-{{ $i <= (int)($r->rating ?? 0) ? 'solid' : 'regular' }} fa-star"></i>
                      @endfor
                    </div>
                  </div>
                  <p class="homestay-detail-review-comment">{{ $r->comment ?? '' }}</p>
                </div>
              @endforeach
              <div class="mt-3">
                {{ $reviews->links() }}
              </div>
            @else
              <p class="homestay-detail-empty-reviews">Chưa có đánh giá nào. Hãy là người đầu tiên trải nghiệm và đánh giá homestay này.</p>
            @endif
          </div>
        </div>
  
        <aside class="homestay-detail-sidebar" id="dat-phong">
          {{-- Rating summary card --}}
  
          {{-- Check-in/out card --}}
          <div class="homestay-detail-sidebar-card">
            <p class="homestay-detail-sidebar-text"><strong>Nhận phòng:</strong> {{ $homestay['check_in_time'] ?? '14:00' }}</p>
            <p class="homestay-detail-sidebar-text"><strong>Trả phòng:</strong> {{ $homestay['check_out_time'] ?? '12:00' }}</p>
          </div>
  
          {{-- Booking form card --}}
          @php
            $isDemo = false;
            $isLoggedIn = auth()->check();
            $pricePerNight = (int) $homestay->discounted_price;
            $originalPricePerNight = (int) $homestay->price_per_night;
            $hasDiscount = $pricePerNight < $originalPricePerNight;
            $maxGuests = (int) ($homestay->max_guests ?? 4);
            $extraGuestFee = 100_000;
          @endphp
          <div class="homestay-detail-card homestay-detail-card--booking">
            <form id="homestay-booking-form" class="homestay-booking-form">
              <div class="homestay-booking-price">
                <span class="homestay-detail-booking-label">Từ</span>
                <span class="homestay-detail-booking-amount">{{ number_format($pricePerNight) }}đ</span>
                @if($hasDiscount)
                  <span style="margin-left:8px;font-size:.86rem;color:#9ca3af;text-decoration:line-through;">{{ number_format($originalPricePerNight) }}đ</span>
                @endif
              </div>
              <p class="homestay-detail-booking-hint">Giá mỗi đêm, chưa gồm phí dịch vụ</p>
  
              <div class="homestay-booking-fields">
                <div class="homestay-booking-row d-flex flex-column gap-1">
                  <div class="homestay-booking-field">
                    <label for="check_in">Nhận phòng</label>
                    <input type="date" id="check_in" name="check_in_date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                  </div>
                  <div class="homestay-booking-field">
                    <label for="check_out">Trả phòng</label>
                    <input type="date" id="check_out" name="check_out_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                  </div>
                </div>
  
                <div class="homestay-booking-field">
                  <label for="num_guests">Số khách</label>
                  <div class="homestay-booking-stepper">
                    <button type="button" class="homestay-booking-stepper-btn" data-delta="-1" aria-label="Giảm">−</button>
                    <input type="number" id="num_guests" name="num_guests" value="1" min="1" max="{{ $maxGuests + 10 }}" readonly>
                    <button type="button" class="homestay-booking-stepper-btn" data-delta="1" aria-label="Tăng">+</button>
                  </div>
                  <span class="homestay-booking-stepper-hint">Tiêu chuẩn {{ $maxGuests }} khách. Vượt quá: +{{ number_format($extraGuestFee, 0, ',', '.') }}đ/người/đêm</span>
                </div>
  
                <div class="homestay-booking-field">
                  <label>Phương thức thanh toán</label>
                  <div class="homestay-booking-payment">
                    <label class="homestay-booking-radio">
                      <input type="radio" name="payment_method" value="bank_transfer" checked>
                      <span><i class="fa-solid fa-building-columns"></i> Chuyển khoản ngân hàng</span>
                    </label>
                  </div>
                </div>
              </div>
  
              <div class="homestay-booking-breakdown">
                <div class="homestay-booking-breakdown-row">
                  <span><span data-bk-nights>0</span> đêm × {{ number_format($pricePerNight, 0, ',', '.') }}đ</span>
                  <span data-bk-subtotal>0đ</span>
                </div>
                <div class="homestay-booking-breakdown-row" data-bk-extra-row style="display: none;">
                  <span>Phí khách vượt quy định</span>
                  <span data-bk-extra>0đ</span>
                </div>
                <div class="homestay-booking-breakdown-row homestay-booking-breakdown-row--total">
                  <span>Tổng cộng</span>
                  <span data-bk-total>0đ</span>
                </div>
              </div>
  
              <div class="homestay-detail-login-notice" id="homestay-login-notice" style="{{ $isLoggedIn ? 'display: none;' : '' }}">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>Vui lòng <a href="{{ route('login.page') }}?redirect={{ urlencode(request()->fullUrl()) }}">Đăng nhập</a> để đặt phòng.</span>
              </div>
              <div class="homestay-booking-error" id="homestay-booking-error" role="alert" hidden></div>
  
              <button type="submit" class="btn btn-primary homestay-detail-book-btn" id="homestay-booking-submit" @if(!$isDemo && !$isLoggedIn) style="display: none;" @endif>
                Thanh toán
              </button>
            </form>
          </div>
        </aside>
      </div>
    </div>
</section>

@if($related->count() > 0)
<section class="py-5">
  <div class="container-setting">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold mb-0" style="font-size: 1.5rem;">Các homestay khác bạn có thể thích</h2>
      <a href="{{ route('pages.search') }}" class="text-decoration-none fw-semibold" style="color: var(--green-900);">Xem thêm</a>
    </div>
    <div class="row g-4">
      @foreach($related as $item)
        @php
          $coverImage = $item->images->first()?->image_url;
        @endphp
        <div class="col-12 col-md-6 col-lg-4">
          <a href="{{ route('homestay.show', ['slug' => $item->slug]) }}" class="text-decoration-none text-dark d-block h-100">
            <article class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
              <div style="height: 200px; overflow: hidden;">
                @if($coverImage)
                  <img
                    src="{{ asset('storage/' . $coverImage) }}"
                    alt="{{ $item->title }}"
                    class="w-100 h-100"
                    style="object-fit: cover;"
                    loading="lazy"
                  >
                @else
                  <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted">
                    <i class="bi bi-house fs-3"></i>
                  </div>
                @endif
              </div>
              <div class="card-body d-flex flex-column gap-3 p-4">
                <h3 class="fw-bold mb-0">{{ $item->title }}</h3>
                <p class="text-muted small mb-0">
                  <i class="fa-solid fa-location-dot me-1"></i>{{ $item->province ?? 'Việt Nam' }}
                </p>
                <p class="text-muted small mb-0">{{ \Illuminate\Support\Str::limit($item->description, 100) }}</p>
                <div class="d-flex align-items-center justify-content-between mt-auto">
                  <div class="small text-warning fw-semibold">
                    <i class="fa-solid fa-star me-1"></i>{{ number_format((float) ($item->reviews_avg_rating ?? 0), 1, ',', '.') }}
                    <span class="text-muted">({{ (int) ($item->reviews_count ?? 0) }})</span>
                  </div>
                  <div class="fw-bold" style="color: #14532d;">
                    {{ number_format((int) $item->discounted_price, 0, ',', '.') }}đ
                    <span class="small text-muted fw-normal">/ đêm</span>
                  </div>
                </div>
              </div>
            </article>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@push('scripts')
<script>
(function () {
  var params = new URLSearchParams(window.location.search);
  var code = params.get('booking_created');
  if (code) {
    var msg = document.getElementById('booking-success-msg');
    var display = document.getElementById('booking-code-display');
    if (msg && display) {
      display.textContent = decodeURIComponent(code);
      msg.hidden = false;
      history.replaceState({}, document.title, window.location.pathname);
    }
  }

  var homestayData = {
    id: '{{ $homestay->id }}',
    pricePerNight: {{ $pricePerNight ?? 0 }},
    maxGuests: {{ $maxGuests ?? 4 }},
    extraGuestFee: {{ $extraGuestFee ?? 100000 }},
    isDemo: false,
    bookingUrl: '{{ route('homestay.booking.store', ['slug' => $homestay->slug]) }}',
    demoBookingUrl: '{{ route('homestay.booking.demo', ['slug' => $homestay->slug]) }}',
    loginUrl: '{{ route('login.page') }}',
    isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
    csrf: '{{ csrf_token() }}'
  };
  var galleryImages = Array.prototype.slice.call(document.querySelectorAll('[data-gallery-image]'));
  var galleryThumbs = Array.prototype.slice.call(document.querySelectorAll('[data-gallery-thumb]'));
  var galleryTrack = document.getElementById('homestay-gallery-track');
  var galleryCounter = document.getElementById('gallery-counter');
  var galleryPrev = document.getElementById('gallery-prev');
  var galleryNext = document.getElementById('gallery-next');
  var lightbox = document.getElementById('homestay-lightbox');
  var lightboxImage = document.getElementById('lightbox-image');
  var lightboxClose = document.getElementById('lightbox-close');
  var lightboxPrev = document.getElementById('lightbox-prev');
  var lightboxNext = document.getElementById('lightbox-next');
  var activeSlide = 0;

  function setGalleryIndex(index) {
    if (!galleryImages.length || !galleryTrack) return;
    activeSlide = (index + galleryImages.length) % galleryImages.length;
    galleryTrack.style.transform = 'translateX(-' + (activeSlide * 100) + '%)';
    if (galleryCounter) galleryCounter.textContent = (activeSlide + 1) + ' / ' + galleryImages.length;
    galleryThumbs.forEach(function (thumb, idx) {
      thumb.classList.toggle('is-active', idx === activeSlide);
    });
  }

  function openLightbox(index) {
    if (!lightbox || !lightboxImage || !galleryImages.length) return;
    setGalleryIndex(index);
    lightbox.hidden = false;
    lightboxImage.src = galleryImages[activeSlide].src;
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    if (!lightbox) return;
    lightbox.hidden = true;
    document.body.style.overflow = '';
  }

  function setLightboxIndex(index) {
    setGalleryIndex(index);
    if (lightboxImage && galleryImages[activeSlide]) {
      lightboxImage.src = galleryImages[activeSlide].src;
    }
  }

  if (galleryPrev) galleryPrev.addEventListener('click', function () { setGalleryIndex(activeSlide - 1); });
  if (galleryNext) galleryNext.addEventListener('click', function () { setGalleryIndex(activeSlide + 1); });
  galleryThumbs.forEach(function (thumb) {
    thumb.addEventListener('click', function () {
      setGalleryIndex(parseInt(thumb.getAttribute('data-gallery-thumb'), 10) || 0);
    });
  });
  galleryImages.forEach(function (img, idx) {
    img.addEventListener('click', function () { openLightbox(idx); });
  });
  if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
  if (lightboxPrev) lightboxPrev.addEventListener('click', function () { setLightboxIndex(activeSlide - 1); });
  if (lightboxNext) lightboxNext.addEventListener('click', function () { setLightboxIndex(activeSlide + 1); });
  if (lightbox) {
    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox) closeLightbox();
    });
  }
  document.addEventListener('keydown', function (e) {
    if (lightbox && !lightbox.hidden) {
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') setLightboxIndex(activeSlide - 1);
      if (e.key === 'ArrowRight') setLightboxIndex(activeSlide + 1);
    }
  });
  setGalleryIndex(0);

  function fmt(n) { return new Intl.NumberFormat('vi-VN').format(n) + 'đ'; }

  function addDays(dateString, days) {
    var date = new Date(dateString);
    if (Number.isNaN(date.getTime())) return '';
    date.setDate(date.getDate() + days);
    return date.toISOString().split('T')[0];
  }

  function syncBookingDates() {
    var checkInInput = document.getElementById('check_in');
    var checkOutInput = document.getElementById('check_out');
    if (!checkInInput || !checkOutInput) return;

    var checkIn = checkInInput.value;
    if (!checkIn) {
      checkOutInput.min = '';
      return;
    }

    var minCheckOut = addDays(checkIn, 1);
    checkOutInput.min = minCheckOut;

    if (checkOutInput.value && checkOutInput.value <= checkIn) {
      checkOutInput.value = '';
      showError('Ngày trả phòng phải sau ngày nhận phòng.');
    }
  }

  function getNights() {
    var ci = document.getElementById('check_in').value;
    var co = document.getElementById('check_out').value;
    if (!ci || !co) return 0;
    var a = new Date(ci), b = new Date(co);
    return Math.max(0, Math.ceil((b - a) / 86400000));
  }

  function getGuests() {
    var inp = document.getElementById('num_guests');
    return Math.max(1, parseInt(inp.value, 10) || 1);
  }

  function updatePrice() {
    syncBookingDates();

    var nights = getNights();
    var guests = getGuests();
    var price = homestayData.pricePerNight;
    var subtotal = nights * price;
    var extra = Math.max(0, guests - homestayData.maxGuests) * homestayData.extraGuestFee * nights;
    var total = subtotal + extra;

    document.querySelector('[data-bk-nights]').textContent = nights;
    document.querySelector('[data-bk-subtotal]').textContent = fmt(subtotal);
    var extraRow = document.querySelector('[data-bk-extra-row]');
    extraRow.style.display = extra > 0 ? '' : 'none';
    document.querySelector('[data-bk-extra]').textContent = fmt(extra);
    document.querySelector('[data-bk-total]').textContent = fmt(total);
  }

  function setupStepper() {
    var inp = document.getElementById('num_guests');
    var max = homestayData.maxGuests + 10;
    document.querySelectorAll('.homestay-booking-stepper-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var d = parseInt(btn.getAttribute('data-delta'), 10);
        inp.value = Math.min(max, Math.max(1, getGuests() + d));
        updatePrice();
      });
    });
  }

  var form = document.getElementById('homestay-booking-form');
  form.querySelectorAll('#check_in, #check_out, #num_guests').forEach(function (el) {
    el.addEventListener('change', updatePrice);
    el.addEventListener('input', updatePrice);
  });
  document.getElementById('check_in').addEventListener('change', syncBookingDates);
  setupStepper();
  updatePrice();

  var isLoggedIn = !!homestayData.isLoggedIn;
  var loginNotice = document.getElementById('homestay-login-notice');
  var submitBtn = document.getElementById('homestay-booking-submit');
  var errorEl = document.getElementById('homestay-booking-error');

  if (homestayData.isDemo) {
    if (loginNotice) loginNotice.style.display = 'none';
    if (submitBtn) submitBtn.style.display = '';
  } else {
    if (loginNotice) loginNotice.style.display = isLoggedIn ? 'none' : '';
    if (submitBtn) submitBtn.style.display = isLoggedIn ? '' : 'none';
  }

  function showError(msg) {
    if (errorEl) {
      errorEl.textContent = msg || '';
      errorEl.hidden = !msg;
    }
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    showError('');

    if (!homestayData.isDemo && !isLoggedIn) {
      window.location.href = homestayData.loginUrl + '?redirect=' + encodeURIComponent(window.location.href);
      return;
    }

    var checkIn = document.getElementById('check_in').value;
    var checkOut = document.getElementById('check_out').value;
    var nights = getNights();
    if (!checkIn || !checkOut || nights <= 0) {
      showError('Vui lòng chọn ngày nhận và trả phòng hợp lệ.');
      return;
    }

    var method = form.querySelector('input[name="payment_method"]:checked').value;
    submitBtn.disabled = true;

    if (homestayData.isDemo) {
      fetch(homestayData.demoBookingUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': homestayData.csrf,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          check_in_date: checkIn,
          check_out_date: checkOut,
          num_guests: getGuests(),
          payment_method: method,
          _token: homestayData.csrf
        })
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.success && data.redirect) {
            window.location.href = data.redirect;
          } else {
            showError(data.message || 'Đặt phòng thất bại.');
            submitBtn.disabled = false;
          }
        })
        .catch(function () {
          showError('Không thể kết nối. Vui lòng thử lại.');
          submitBtn.disabled = false;
        });
      return;
    }

    fetch(homestayData.bookingUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': homestayData.csrf,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        check_in: checkIn,
        check_out: checkOut,
        num_guests: getGuests(),
        payment_method: method
      })
    })
      .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
      .then(function (res) {
        if (res.data && res.data.success && res.data.redirect) {
          window.location.href = res.data.redirect;
        } else if (res.data && res.data.message) {
          showError(res.data.message);
          submitBtn.disabled = false;
        } else {
          showError('Đặt phòng thất bại. Vui lòng thử lại.');
          submitBtn.disabled = false;
        }
      })
      .catch(function () {
        showError('Không thể kết nối. Vui lòng thử lại.');
        submitBtn.disabled = false;
      });
  });

  var tabs = document.getElementById('homestay-tabs');
  if (tabs) {
    function setActiveTab(hash) {
      tabs.querySelectorAll('.homestay-detail-tab').forEach(function (t) {
        t.classList.toggle('is-active', t.getAttribute('href') === (hash || '#tong-quan'));
      });
    }
    if (window.location.hash) {
      setActiveTab(window.location.hash);
    }
    tabs.querySelectorAll('.homestay-detail-tab').forEach(function (tab) {
      tab.addEventListener('click', function (e) {
        var href = this.getAttribute('href');
        if (href && href.startsWith('#')) {
          e.preventDefault();
          var el = document.querySelector(href);
          if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
          setActiveTab(href);
        }
      });
    });
  }
})();
</script>
@endpush
@endsection

